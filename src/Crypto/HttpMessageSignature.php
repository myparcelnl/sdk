<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Crypto;

use gapple\StructuredFields\Dictionary;
use gapple\StructuredFields\InnerList;
use gapple\StructuredFields\Serializer;
use OpenSSLAsymmetricKey;
use RuntimeException;

/**
 * The two generic steps of RFC 9421: build the signature base, and check an ecdsa-p256-sha256
 * signature over it.
 *
 * Nothing in here knows MyParcel's profile. The caller decides which components are allowed and
 * supplies their values, so the same code verifies the RFC's own examples.
 */
final class HttpMessageSignature
{
    /**
     * An ECDSA P-256 signature on the wire: r then s, 32 bytes each.
     */
    private const SIGNATURE_LENGTH = 64;

    /**
     * Build the signature base of RFC 9421 §2.5.
     *
     * @param  InnerList             $signatureInput The one member of Signature-Input as
     *                                               gapple/structured-fields parses it: the covered
     *                                               components as Items, the signature parameters
     *                                               as its Parameters.
     * @param  array<string, string> $components     The value of every covered component, by its
     *                                               name as it appears in the inner list.
     * @return string Lines joined by LF, no LF after the last one.
     * @throws \RuntimeException When the inner list names a component that is not in $components.
     */
    public static function signatureBase(InnerList $signatureInput, array $components): string
    {
        $lines = [];

        foreach ($signatureInput->getValue() as $item) {
            $name = $item->getValue();

            if (!is_string($name) || !array_key_exists($name, $components)) {
                throw new RuntimeException(sprintf('No value for the covered component %s', var_export($name, true)));
            }

            $lines[] = sprintf('"%s": %s', $name, $components[$name]);
        }

        $lines[] = '"@signature-params": ' . self::serializeSignatureParams($signatureInput);

        return implode("\n", $lines);
    }

    /**
     * Check an ecdsa-p256-sha256 signature. Only an explicit success from openssl counts.
     *
     * @param  string                      $signatureBase What signatureBase() returned.
     * @param  string                      $signature     The 64 raw bytes from the Signature header.
     * @param  \OpenSSLAsymmetricKey|string $publicKey    The key, or its PEM.
     */
    public static function verifyEcdsaP256Sha256(string $signatureBase, string $signature, OpenSSLAsymmetricKey|string $publicKey): bool
    {
        if (self::SIGNATURE_LENGTH !== strlen($signature)) {
            return false;
        }

        $key = is_string($publicKey) ? openssl_pkey_get_public($publicKey) : $publicKey;

        if (false === $key) {
            return false;
        }

        // openssl_verify() returns 1, 0 or false. Anything but 1 is a failed check.
        return 1 === openssl_verify($signatureBase, EcdsaSignature::signatureToDER($signature), $key, OPENSSL_ALGO_SHA256);
    }

    /**
     * The inner list with its parameters, re-serialized as RFC 8941 writes it.
     *
     * The library only serializes a whole dictionary, so wrap the list in one and take the part
     * after the key.
     */
    private static function serializeSignatureParams(InnerList $signatureInput): string
    {
        $serialized = Serializer::serializeDictionary(Dictionary::fromArray(['p' => $signatureInput]));

        return substr($serialized, strlen('p='));
    }
}
