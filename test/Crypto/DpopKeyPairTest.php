<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Crypto;

use MyParcelNL\Sdk\Crypto\DpopKeyInterface;
use MyParcelNL\Sdk\Crypto\DpopKeyPair;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use RuntimeException;

class DpopKeyPairTest extends TestCase
{
    /**
     * RFC 9449. The public key in figure 4 and its thumbprint in figure 9. MyParcel identifies a
     * shop by this value, so ours has to come out byte for byte the same as theirs.
     */
    private const RFC9449_JWK = [
        'kty' => 'EC',
        'x'   => 'l8tFrhx-34tV3hRICRDY9zCkDlpBhF42UQUfWVAWBFs',
        'y'   => '9VE4jf_Ok_o64zbTTlcuNJajHmt6v9TDVrU0CdvGRDA',
        'crv' => 'P-256',
    ];

    private const RFC9449_JKT = '0ZcOCORZNYy-DWpqq30jZyJGHTN0d2HglBV3uiguA4I';

    /**
     * A real P-256 key whose y coordinate is 31 bytes, so it needs padding.
     */
    private const SHORT_COORDINATE_PEM = <<<PEM
        -----BEGIN PRIVATE KEY-----
        MIGHAgEAMBMGByqGSM49AgEGCCqGSM49AwEHBG0wawIBAQQgqZxQr7HkC6Mvf9kO
        pgfWyVDlKNconxwhdshPNxDX9HGhRANCAATQH04iBrkETGZDiLlanck1EwklhdkJ
        x3IqhVOKB9GZogB6Dv+5luEMQo8+ZVgk6Ppjt9zRdgZ3J2KlmrvUYUxS
        -----END PRIVATE KEY-----
        PEM;

    public function testThumbprintOfTheRfc9449KeyMatchesThePublishedValue(): void
    {
        self::assertSame(self::RFC9449_JKT, DpopKeyPair::thumbprintOfJwk(self::RFC9449_JWK));
    }

    public function testThumbprintIgnoresTheMemberOrderOfTheInputJwk(): void
    {
        $reordered = [
            'crv' => self::RFC9449_JWK['crv'],
            'y'   => self::RFC9449_JWK['y'],
            'kty' => self::RFC9449_JWK['kty'],
            'x'   => self::RFC9449_JWK['x'],
        ];

        self::assertSame(self::RFC9449_JKT, DpopKeyPair::thumbprintOfJwk($reordered));
    }

    public function testGenerateProducesAnEcKeyOnTheP256Curve(): void
    {
        $details = openssl_pkey_get_details(DpopKeyPair::generate()->getKeyMaterial());

        self::assertSame(OPENSSL_KEYTYPE_EC, $details['type']);
        self::assertSame('prime256v1', $details['ec']['curve_name']);
    }

    public function testPublicJwkHoldsOnlyTheFourRequiredMembersInThumbprintOrder(): void
    {
        $jwk = DpopKeyPair::generate()->getPublicJwk();

        self::assertSame(['crv', 'kty', 'x', 'y'], array_keys($jwk));
        self::assertSame('P-256', $jwk['crv']);
        self::assertSame('EC', $jwk['kty']);
    }

    public function testPublicJwkNeverCarriesThePrivateKey(): void
    {
        // RFC 9449 forbids a private key in the jwk header member of a proof.
        self::assertArrayNotHasKey('d', DpopKeyPair::generate()->getPublicJwk());
    }

    public function testPublicJwkPadsACoordinateThatOpensslReturnsAByteShort(): void
    {
        // This key's y is 31 bytes on OpenSSL 1.1.1. Generating keys until a short one turns up
        // would only catch a missing pad about a third of the time, so the key is a fixture.
        $key = DpopKeyPair::fromPem(self::SHORT_COORDINATE_PEM);
        $jwk = $key->getPublicJwk();

        self::assertSame(43, strlen($jwk['x']), 'x must be 32 bytes');
        self::assertSame(43, strlen($jwk['y']), 'y must be 32 bytes');
    }

    public function testThumbprintOfTheShortCoordinateKeyIsTheSameOnEveryOpensslVersion(): void
    {
        // Pinned so a change in padding shows up here rather than as a shop losing its identity.
        self::assertSame(
            'SptVKt3wOR7OKbcJ-joeon3G7dRyDVCFf96loC6OUSc',
            DpopKeyPair::fromPem(self::SHORT_COORDINATE_PEM)->getThumbprint()
        );
    }

    public function testThumbprintRejectsAJwkThatIsMissingAMember(): void
    {
        $this->expectException(RuntimeException::class);

        DpopKeyPair::thumbprintOfJwk(['kty' => 'EC', 'crv' => 'P-256', 'x' => 'abc']);
    }

    public function testThumbprintRejectsAMemberThatIsNotAString(): void
    {
        $this->expectException(RuntimeException::class);

        DpopKeyPair::thumbprintOfJwk(['kty' => 'EC', 'crv' => 'P-256', 'x' => 'abc', 'y' => 123]);
    }

    public function testFromPemRejectsAFilePathEvenWhenTheFileHoldsAValidKey(): void
    {
        // openssl_pkey_get_private() reads a file:// value from disk. The parameter is PEM text, and
        // it can come from consumer storage a shop admin edits, so a path must not be followed.
        $path = tempnam(sys_get_temp_dir(), 'dpop') ?: '';
        file_put_contents($path, self::SHORT_COORDINATE_PEM);

        try {
            $this->expectException(RuntimeException::class);

            DpopKeyPair::fromPem('file://' . $path);
        } finally {
            unlink($path);
        }
    }

    public function testFromPemRestoresTheSameKey(): void
    {
        $original = DpopKeyPair::generate();
        $restored = DpopKeyPair::fromPem($original->getPrivateKeyPem());

        self::assertSame($original->getPublicJwk(), $restored->getPublicJwk());
        self::assertSame($original->getThumbprint(), $restored->getThumbprint());
    }

    public function testThumbprintMatchesTheHashOfTheOwnPublicJwk(): void
    {
        $key = DpopKeyPair::generate();

        self::assertSame(DpopKeyPair::thumbprintOfJwk($key->getPublicJwk()), $key->getThumbprint());
    }

    public function testAlgorithmIsEs256(): void
    {
        self::assertSame('ES256', DpopKeyPair::generate()->getAlgorithm());
    }

    public function testIsADpopKey(): void
    {
        self::assertInstanceOf(DpopKeyInterface::class, DpopKeyPair::generate());
    }

    public function testFromPemRejectsAKeyThatIsNotEc(): void
    {
        $rsa = openssl_pkey_new(['private_key_type' => OPENSSL_KEYTYPE_RSA, 'private_key_bits' => 2048]);
        openssl_pkey_export($rsa, $pem);

        $this->expectException(RuntimeException::class);

        DpopKeyPair::fromPem($pem);
    }

    public function testFromPemRejectsAKeyOnAnotherCurve(): void
    {
        $other = openssl_pkey_new(['private_key_type' => OPENSSL_KEYTYPE_EC, 'curve_name' => 'secp384r1']);
        openssl_pkey_export($other, $pem);

        $this->expectException(RuntimeException::class);

        DpopKeyPair::fromPem($pem);
    }

    public function testFromPemRejectsTextThatIsNotAKey(): void
    {
        $this->expectException(RuntimeException::class);

        DpopKeyPair::fromPem('not a pem');
    }
}
