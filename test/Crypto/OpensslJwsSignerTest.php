<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Crypto;

use MyParcelNL\Sdk\Crypto\DpopKeyInterface;
use MyParcelNL\Sdk\Crypto\DpopKeyPair;
use MyParcelNL\Sdk\Crypto\EcdsaSignature;
use MyParcelNL\Sdk\Crypto\JwsSignerInterface;
use MyParcelNL\Sdk\Crypto\OpensslJwsSigner;
use MyParcelNL\Sdk\Support\Str;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use RuntimeException;

class OpensslJwsSignerTest extends TestCase
{
    public function testProducesThreeBase64UrlSegments(): void
    {
        $jws = $this->sign();

        self::assertCount(3, explode('.', $jws));
        self::assertSame(1, preg_match('/^[A-Za-z0-9_-]+\.[A-Za-z0-9_-]+\.[A-Za-z0-9_-]+$/', $jws));
    }

    public function testHeaderCarriesTheGivenMembersAndTheAlgorithmFromTheKey(): void
    {
        $key    = DpopKeyPair::generate();
        $header = json_decode($this->segment($this->sign($key), 0), true);

        self::assertSame('dpop+jwt', $header['typ']);
        self::assertSame($key->getPublicJwk(), $header['jwk']);
        self::assertSame('ES256', $header['alg']);
    }

    public function testTheKeyDecidesTheAlgorithmWhateverTheCallerPutInTheHeader(): void
    {
        $header = json_decode($this->segment($this->sign(null, ['typ' => 'dpop+jwt', 'alg' => 'HS256']), 0), true);

        self::assertSame('ES256', $header['alg']);
    }

    public function testPayloadIsTheGivenClaims(): void
    {
        $claims = ['jti' => 'abc', 'htm' => 'POST', 'htu' => 'https://example.test/x', 'iat' => 1735689600];

        self::assertSame($claims, json_decode($this->segment($this->sign(null, null, $claims), 1), true));
    }

    public function testSignatureIsSixtyFourBytesAndVerifiesOverTheFirstTwoSegments(): void
    {
        $key = DpopKeyPair::generate();
        $jws = $this->sign($key);

        [$header, $payload, $signature] = explode('.', $jws);

        $raw = Str::base64UrlDecode($signature);

        self::assertSame(64, strlen($raw));
        self::assertSame(
            1,
            openssl_verify(
                "$header.$payload",
                EcdsaSignature::signatureToDER($raw),
                openssl_pkey_get_details($key->getKeyMaterial())['key'],
                OPENSSL_ALGO_SHA256
            )
        );
    }

    public function testJsonNeverEscapesSlashes(): void
    {
        // An escaped slash in htu would change the bytes the far side verifies.
        $jws = $this->sign(null, null, ['htu' => 'https://example.test/orders']);

        self::assertStringNotContainsString('\\/', $this->segment($jws, 1));
    }

    public function testRejectsAKeyThatIsNotEs256(): void
    {
        $this->expectException(RuntimeException::class);

        (new OpensslJwsSigner())->sign([], $this->keyWithAlgorithm('RS256'), ['typ' => 'dpop+jwt']);
    }

    public function testIsAJwsSigner(): void
    {
        self::assertInstanceOf(JwsSignerInterface::class, new OpensslJwsSigner());
    }

    private function sign(?DpopKeyPair $key = null, ?array $header = null, ?array $payload = null): string
    {
        $key = $key ?? DpopKeyPair::generate();

        return (new OpensslJwsSigner())->sign(
            $payload ?? ['jti' => 'abc', 'iat' => 1735689600],
            $key,
            $header ?? ['typ' => 'dpop+jwt', 'alg' => $key->getAlgorithm(), 'jwk' => $key->getPublicJwk()]
        );
    }

    private function segment(string $jws, int $index): string
    {
        return Str::base64UrlDecode(explode('.', $jws)[$index]);
    }

    private function keyWithAlgorithm(string $algorithm): DpopKeyInterface
    {
        $real = DpopKeyPair::generate();

        return new class($real, $algorithm) implements DpopKeyInterface {
            /** @var DpopKeyPair */
            private $real;

            /** @var string */
            private $algorithm;

            public function __construct(DpopKeyPair $real, string $algorithm)
            {
                $this->real      = $real;
                $this->algorithm = $algorithm;
            }

            public function getKeyMaterial()
            {
                return $this->real->getKeyMaterial();
            }

            public function getAlgorithm(): string
            {
                return $this->algorithm;
            }

            public function getPublicJwk(): array
            {
                return $this->real->getPublicJwk();
            }

            public function getThumbprint(): string
            {
                return $this->real->getThumbprint();
            }
        };
    }
}
