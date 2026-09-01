<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Crypto;

use MyParcelNL\Sdk\Crypto\EcdsaSignature;
use MyParcelNL\Sdk\Support\Str;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use RuntimeException;

class EcdsaSignatureTest extends TestCase
{
    /**
     * RFC 7515 appendix A.3.1. The one published ES256 example: a key, a signing input and the
     * signature over it. Copy the base64url strings verbatim, the payload contains CRLF bytes.
     */
    private const RFC7515_X   = 'f83OJ3D2xF1Bg8vub9tLe1gHMzV76e8Tus9uPHvRVEU';
    private const RFC7515_Y   = 'x_FEzRu9m36HLN_tue659LNpXW6pCyStikYjKIWI5a0';
    private const RFC7515_IN  = 'eyJhbGciOiJFUzI1NiJ9.eyJpc3MiOiJqb2UiLA0KICJleHAiOjEzMDA4MTkzODAsDQogImh0dHA6Ly9leGFtcGxlLmNvbS9pc19yb290Ijp0cnVlfQ';
    private const RFC7515_SIG = 'DtEhU3ljbEg8L38VWAfUAqOyKAM6-Xx-F4GawxaepmXFCgfTjDxw5djxLa8ISlSApmWQxfKTUJqPP3-Kg6NU1Q';

    public function testTurnsTheRfc7515SignatureIntoDerThatOpensslAccepts(): void
    {
        $signature = Str::base64UrlDecode(self::RFC7515_SIG);

        self::assertSame(64, strlen($signature), 'precondition: the RFC signature is 64 raw bytes');

        // The signing input is signed as the literal ASCII string, dot included. Not decoded.
        $verified = openssl_verify(
            self::RFC7515_IN,
            EcdsaSignature::signatureToDER($signature),
            $this->rfc7515PublicKeyPem(),
            OPENSSL_ALGO_SHA256
        );

        self::assertSame(1, $verified, 'openssl must accept the re-encoded RFC 7515 signature');
    }

    public function testEveryRealSignatureConvertsToExactlySixtyFourBytesAndVerifies(): void
    {
        $key = $this->newKey();
        $pem = openssl_pkey_get_details($key)['key'];

        // r or s is short in about half of all signatures and both are short in 0.22%, so a
        // fixed-offset conversion passes a handful of runs and fails about 1 request in 450.
        $lengths = [];

        for ($i = 0; $i < 500; $i++) {
            $message = "message $i";
            $der     = '';

            // openssl_sign() writes the signature into its second argument, by reference.
            openssl_sign($message, $der, $key, OPENSSL_ALGO_SHA256);

            $signature      = EcdsaSignature::signatureFromDER($der);
            $lengths[]      = strlen($der);

            self::assertSame(64, strlen($signature), "signature $i was not 64 bytes");
            self::assertSame(
                1,
                openssl_verify($message, EcdsaSignature::signatureToDER($signature), $pem, OPENSSL_ALGO_SHA256),
                "signature $i did not verify after a round trip"
            );
        }

        self::assertGreaterThan(1, count(array_unique($lengths)), 'the run must cover more than one DER length');
    }

    public function testRoundTripReproducesTheOriginalDerByteForByte(): void
    {
        $key = $this->newKey();

        for ($i = 0; $i < 100; $i++) {
            $der = '';
            openssl_sign("message $i", $der, $key, OPENSSL_ALGO_SHA256);

            self::assertSame($der, EcdsaSignature::signatureToDER(EcdsaSignature::signatureFromDER($der)));
        }
    }

    public function testRejectsDerThatDoesNotStartWithASequence(): void
    {
        $this->expectException(RuntimeException::class);

        EcdsaSignature::signatureFromDER("\x31\x06\x02\x01\x01\x02\x01\x01");
    }

    public function testRejectsTruncatedDer(): void
    {
        $this->expectException(RuntimeException::class);

        EcdsaSignature::signatureFromDER("\x30\x06\x02\x01");
    }

    public function testRejectsDerWithBytesAfterTheTwoNumbers(): void
    {
        $this->expectException(RuntimeException::class);

        EcdsaSignature::signatureFromDER(hex2bin('3006020101020102') . 'GARBAGE');
    }

    public function testRejectsADeclaredSequenceLengthThatDoesNotMatchTheContent(): void
    {
        $this->expectException(RuntimeException::class);

        EcdsaSignature::signatureFromDER(hex2bin('3000020101020102'));
    }

    public function testRejectsASignatureWithAnOddLength(): void
    {
        $this->expectException(RuntimeException::class);

        EcdsaSignature::signatureToDER(str_repeat("\x01", 63));
    }

    /**
     * @return resource|\OpenSSLAsymmetricKey
     */
    private function newKey()
    {
        return openssl_pkey_new([
            'private_key_type' => OPENSSL_KEYTYPE_EC,
            'curve_name'       => 'prime256v1',
        ]);
    }

    /**
     * Builds a public key PEM from the RFC 7515 x and y. Every field of a P-256 SPKI is fixed
     * length, so the prefix is a constant and the coordinates follow an uncompressed point marker.
     */
    private function rfc7515PublicKeyPem(): string
    {
        $der = hex2bin('3059301306072a8648ce3d020106082a8648ce3d030107034200')
            . "\x04"
            . Str::base64UrlDecode(self::RFC7515_X)
            . Str::base64UrlDecode(self::RFC7515_Y);

        return "-----BEGIN PUBLIC KEY-----\n" . chunk_split(base64_encode($der), 64, "\n") . "-----END PUBLIC KEY-----\n";
    }
}
