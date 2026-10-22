<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Crypto;

use gapple\StructuredFields\Parser;
use MyParcelNL\Sdk\Crypto\HttpMessageSignature;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

/**
 * Two published vectors: MyParcel's own test vector and RFC 9421 Appendix B.2.4. Both carry a
 * fixed signature, so they prove the base is built byte for byte and that a real r‖s signature
 * verifies. Nothing here looks at time or at MyParcel's rules: that is CommandRequestVerifier.
 */
final class HttpMessageSignatureTest extends TestCase
{
    /**
     * MyParcel's test vector for signed commands, contract 3.0.0. A test key that is never used in
     * production. The same vector is in the signer's test suite at MyParcel.
     */
    private const VECTOR_JWK = [
        'kty' => 'EC',
        'crv' => 'P-256',
        'x'   => 'asFsz9G-vPTdSU8vaN4Kx4fPKLQgaddM0inPXprUFvs',
        'y'   => 'u0fQb-7jTfCYBjsjvRzyVXoI0TVJCnsLHZJqaHKns0c',
        'kid' => '-Yh7UvHZxy_W_29MrByY0Wd2XDRbcWFRLkiZKMkTRc0',
        'alg' => 'ES256',
    ];

    private const VECTOR_BODY = '{"type":"ORDER_SHIPMENTS","order":{"sourceId":"1001","status":"PARTIALLY_SHIPPED"},"shipment":{"shipmentId":"a3f1c0de-0000-4000-8000-000000000001","orderLines":[{"sourceId":"5001","quantity":2}]},"commandId":"6f1c2d3e-4a5b-4c6d-8e7f-0a1b2c3d4e5f","connectionId":"9c9e0d2c-2c0a-4bfa-97cf-8a4f42a0f001"}';

    private const VECTOR_DIGEST = 'sha-256=:DZMGsdBhwD5nmPui/dt5dfIYL0V2Z8VHQf2SBuljZM8=:';

    private const VECTOR_SIGNATURE_INPUT = 'myparcel=("@method" "@target-uri" "content-type" "content-digest");created=1791806400;expires=1791806460;keyid="-Yh7UvHZxy_W_29MrByY0Wd2XDRbcWFRLkiZKMkTRc0";alg="ecdsa-p256-sha256"';

    private const VECTOR_SIGNATURE = 'myparcel=:3cKROZe5Wx8l+mjuId67cKMypYxNvkq3oaFs1NRfYrgb2xYa81lEr2xLK/XTcQe0OTdIZqH6bSpIHw4pj0u/FQ==:';

    private const VECTOR_BASE = "\"@method\": POST\n"
        . "\"@target-uri\": https://shop.example.com/?route=myparcel/commands\n"
        . "\"content-type\": application/json\n"
        . "\"content-digest\": sha-256=:DZMGsdBhwD5nmPui/dt5dfIYL0V2Z8VHQf2SBuljZM8=:\n"
        . '"@signature-params": ("@method" "@target-uri" "content-type" "content-digest");created=1791806400;expires=1791806460;keyid="-Yh7UvHZxy_W_29MrByY0Wd2XDRbcWFRLkiZKMkTRc0";alg="ecdsa-p256-sha256"';

    /**
     * RFC 9421 Appendix B.1.3, test-key-ecc-p256.
     */
    private const RFC_PUBLIC_KEY_PEM = "-----BEGIN PUBLIC KEY-----\n"
        . "MFkwEwYHKoZIzj0CAQYIKoZIzj0DAQcDQgAEqIVYZVLCrPZHGHjP17CTW0/+D9Lf\n"
        . "w0EkjqF7xB4FivAxzic30tMM4GF+hR6Dxh71Z50VGGdldkkDXZCnTNnoXQ==\n"
        . "-----END PUBLIC KEY-----\n";

    /**
     * RFC 9421 Appendix B.2.4, the one ecdsa-p256-sha256 example. It signs a response, so its
     * components differ from ours: that is the point, the base builder takes any list.
     */
    private const RFC_SIGNATURE_INPUT = 'sig-b24=("@status" "content-type" "content-digest" "content-length");created=1618884473;keyid="test-key-ecc-p256"';

    private const RFC_SIGNATURE = 'sig-b24=:wNmSUAhwb5LxtOtOpNa6W5xj067m5hFrj0XQ4fvpaCLx0NKocgPquLgyahnzDnDAUy5eCdlYUEkLIj+32oiasw==:';

    private const RFC_COMPONENTS = [
        '@status'        => '200',
        'content-type'   => 'application/json',
        'content-digest' => 'sha-512=:mEWXIS7MaLRuGgxOBdODa3xqM1XdEvxoYhvlCFJ41QJgJc4GTsPp29l5oGX69wWdXymyU0rjJuahq4l5aGgfLQ==:',
        'content-length' => '23',
    ];

    public function testBuildsTheSignatureBaseOfTheVectorByteForByte(): void
    {
        $base = HttpMessageSignature::signatureBase(self::signatureInput(self::VECTOR_SIGNATURE_INPUT), [
            '@method'        => 'POST',
            '@target-uri'    => 'https://shop.example.com/?route=myparcel/commands',
            'content-type'   => 'application/json',
            'content-digest' => self::VECTOR_DIGEST,
        ]);

        self::assertSame(self::VECTOR_BASE, $base);
        self::assertSame(379, strlen($base), 'the vector says 379 bytes');
    }

    public function testTheVectorDigestIsTheSha256OfTheBody(): void
    {
        self::assertSame(301, strlen(self::VECTOR_BODY), 'the vector says 301 bytes');
        self::assertSame(
            self::VECTOR_DIGEST,
            'sha-256=:' . base64_encode(hash('sha256', self::VECTOR_BODY, true)) . ':'
        );
    }

    public function testVerifiesTheVectorSignatureWithTheVectorKey(): void
    {
        $key = \Firebase\JWT\JWK::parseKey(self::VECTOR_JWK);

        self::assertTrue(HttpMessageSignature::verifyEcdsaP256Sha256(
            self::VECTOR_BASE,
            self::signatureBytes(self::VECTOR_SIGNATURE),
            $key->getKeyMaterial()
        ));
    }

    public function testRejectsTheVectorSignatureOverAChangedBase(): void
    {
        $key = \Firebase\JWT\JWK::parseKey(self::VECTOR_JWK);

        self::assertFalse(HttpMessageSignature::verifyEcdsaP256Sha256(
            str_replace('POST', 'GET', self::VECTOR_BASE),
            self::signatureBytes(self::VECTOR_SIGNATURE),
            $key->getKeyMaterial()
        ));
    }

    public function testBuildsAndVerifiesTheRfc9421AppendixB24Example(): void
    {
        $base = HttpMessageSignature::signatureBase(self::signatureInput(self::RFC_SIGNATURE_INPUT), self::RFC_COMPONENTS);

        self::assertStringEndsWith(
            '"@signature-params": ("@status" "content-type" "content-digest" "content-length");created=1618884473;keyid="test-key-ecc-p256"',
            $base
        );
        self::assertTrue(HttpMessageSignature::verifyEcdsaP256Sha256(
            $base,
            self::signatureBytes(self::RFC_SIGNATURE),
            self::RFC_PUBLIC_KEY_PEM
        ));
    }

    public function testACoveredComponentWithoutAValueThrows(): void
    {
        $this->expectException(\RuntimeException::class);

        HttpMessageSignature::signatureBase(self::signatureInput(self::RFC_SIGNATURE_INPUT), ['@status' => '200']);
    }

    public function testASignatureThatIsNotSixtyFourBytesFailsInsteadOfThrowing(): void
    {
        self::assertFalse(HttpMessageSignature::verifyEcdsaP256Sha256(self::VECTOR_BASE, 'too short', self::RFC_PUBLIC_KEY_PEM));
        self::assertFalse(HttpMessageSignature::verifyEcdsaP256Sha256(self::VECTOR_BASE, str_repeat("\x00", 64), self::RFC_PUBLIC_KEY_PEM));
    }

    public function testAKeyThatOpensslCannotReadFailsInsteadOfThrowing(): void
    {
        self::assertFalse(HttpMessageSignature::verifyEcdsaP256Sha256(
            self::VECTOR_BASE,
            self::signatureBytes(self::VECTOR_SIGNATURE),
            'not a key'
        ));
    }

    private static function signatureInput(string $header): \gapple\StructuredFields\InnerList
    {
        $dictionary = Parser::parseDictionary($header);

        foreach ($dictionary as $member) {
            return $member;
        }

        self::fail('the header has no member');
    }

    private static function signatureBytes(string $header): string
    {
        $dictionary = Parser::parseDictionary($header);

        foreach ($dictionary as $member) {
            return (string) $member->getValue();
        }

        self::fail('the header has no member');
    }
}
