<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Crypto;

use RuntimeException;

/**
 * Converts an ECDSA signature between the two ways it is written down.
 *
 * A signature is two numbers, r and s. OpenSSL writes them in DER, which describes its own layout
 * and so varies in length. A JWT needs the IEEE P1363 form, which is fixed length. PHP only gives
 * us DER and offers no way to ask for the other one, so we convert.
 *
 * The method names and the $keySize argument in bits match the private methods of the same name in
 * Firebase\JWT\JWT, so a later swap to that library reads the same.
 *
 * Everything here is raw bytes, so it uses strlen() and substr(). Support\Str::length() and
 * Support\Str::substr() are mb_* based and return different data for bytes that happen to form a
 * valid multi-byte sequence.
 */
final class EcdsaSignature
{
    /**
     * DER tag for a sequence, the wrapper around the two numbers.
     */
    private const TAG_SEQUENCE = 0x30;

    /**
     * DER tag for one whole number.
     */
    private const TAG_INTEGER = 0x02;

    /**
     * Convert DER, what openssl_sign() returns, into the bytes a JWT needs.
     *
     * The input is 69 to 72 bytes for P-256 and differs per signature, because DER drops leading
     * zero bytes. The output pads both numbers back to a fixed width, so it is always 64 bytes.
     *
     * @param  string $der     The signature as openssl_sign() produced it.
     * @param  int    $keySize Key size in bits. 256 for the P-256 curve.
     * @return string 2 * ($keySize / 8) raw bytes: r then s.
     * @throws \RuntimeException When the input is not a DER ECDSA signature.
     */
    public static function signatureFromDER(string $der, int $keySize = 256): string
    {
        $partLength = intdiv($keySize, 8);
        $offset     = 0;

        $declared = self::readTag($der, $offset, self::TAG_SEQUENCE);
        $start    = $offset;

        $r = self::readInteger($der, $offset);
        $s = self::readInteger($der, $offset);

        // The two numbers must fill the sequence exactly. Anything else means this is not the
        // signature it claims to be, so do not read part of it and call the rest a result.
        if ($offset - $start !== $declared || $offset !== strlen($der)) {
            throw new RuntimeException('Malformed ECDSA signature');
        }

        return self::pad($r, $partLength) . self::pad($s, $partLength);
    }

    /**
     * Convert the 64 bytes back into DER, which is the only form openssl_verify() reads.
     *
     * @param  string $signature 2 * n raw bytes: r then s.
     * @return string
     * @throws \RuntimeException When the input length is odd.
     */
    public static function signatureToDER(string $signature): string
    {
        $length = strlen($signature);

        if (0 === $length || 0 !== $length % 2) {
            throw new RuntimeException('An ECDSA signature must hold r and s at equal length');
        }

        $half = intdiv($length, 2);

        $body = self::writeInteger(substr($signature, 0, $half))
            . self::writeInteger(substr($signature, $half));

        return chr(self::TAG_SEQUENCE) . self::writeLength(strlen($body)) . $body;
    }

    /**
     * Read r or s. $offset is by reference, so the next call continues where this one stopped.
     */
    private static function readInteger(string $der, int &$offset): string
    {
        $length = self::readTag($der, $offset, self::TAG_INTEGER);
        $value  = substr($der, $offset, $length);

        if (strlen($value) !== $length) {
            throw new RuntimeException('Truncated ECDSA signature');
        }

        $offset += $length;

        // DER puts a 0x00 in front of a number whose first bit is set, so it is not read as
        // negative. The raw form has no sign, so that byte comes off again.
        return ltrim($value, "\x00");
    }

    /**
     * Check the tag at the offset, then read the length that follows it.
     */
    private static function readTag(string $der, int &$offset, int $tag): int
    {
        if (!isset($der[$offset], $der[$offset + 1]) || ord($der[$offset]) !== $tag) {
            throw new RuntimeException('Malformed ECDSA signature');
        }

        $offset++;
        $length = ord($der[$offset++]);

        // A length over 127 does not fit in one byte. The high bit then marks how many bytes hold
        // the real length. A P-256 signature never gets there, but a wrong input might claim it.
        if ($length & 0x80) {
            $count  = $length & 0x7f;
            $length = 0;

            for ($i = 0; $i < $count; $i++) {
                if (!isset($der[$offset])) {
                    throw new RuntimeException('Truncated ECDSA signature');
                }

                $length = ($length << 8) | ord($der[$offset++]);
            }
        }

        return $length;
    }

    /**
     * Pad r or s to the fixed width the JWT form needs.
     */
    private static function pad(string $value, int $length): string
    {
        if (strlen($value) > $length) {
            throw new RuntimeException('ECDSA signature value is longer than the key size allows');
        }

        return str_pad($value, $length, "\x00", STR_PAD_LEFT);
    }

    /**
     * Write r or s as DER: tag, length, value.
     */
    private static function writeInteger(string $value): string
    {
        $value = ltrim($value, "\x00");

        if ('' === $value) {
            $value = "\x00";
        }

        // Add the sign byte back when the first bit is set.
        if (ord($value[0]) & 0x80) {
            $value = "\x00" . $value;
        }

        return chr(self::TAG_INTEGER) . self::writeLength(strlen($value)) . $value;
    }

    /**
     * Write a DER length. Everything here fits in one byte.
     */
    private static function writeLength(int $length): string
    {
        if ($length > 0x7f) {
            throw new RuntimeException('ECDSA signature is too long to encode');
        }

        return chr($length);
    }
}
