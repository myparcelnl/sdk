<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Crypto;

use MyParcelNL\Sdk\Crypto\DpopKeyPair;
use MyParcelNL\Sdk\Crypto\DpopProofFactory;
use MyParcelNL\Sdk\Crypto\EcdsaSignature;
use MyParcelNL\Sdk\Support\Str;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use RuntimeException;

class DpopProofFactoryTest extends TestCase
{
    /**
     * RFC 9449 section 7.1. The access token from figure 13 and the ath it produces in figure 14.
     */
    private const RFC9449_TOKEN = 'Kz~8mXK1EalYznwH-LC-1fBAo.4Ljp~zsPE_NeO.gxU';
    private const RFC9449_ATH   = 'fUHyO2r2Z3DZ53EsNrWBb0xWXoaNy59IiKCAqksmQEo';

    public function testTokenEndpointProofHasTheRequiredClaimsAndNoAth(): void
    {
        $claims = $this->claims($this->factory()->createForTokenEndpoint(
            $this->key,
            'POST',
            'https://account.acceptance.myparcel.nl/oauth/token'
        ));

        self::assertSame('POST', $claims['htm']);
        self::assertSame('https://account.acceptance.myparcel.nl/oauth/token', $claims['htu']);
        self::assertGreaterThanOrEqual(time() - 5, $claims['iat'], 'iat is when the proof was made');
        self::assertLessThanOrEqual(time() + 5, $claims['iat']);
        self::assertArrayHasKey('jti', $claims);

        // There is no access token yet at the token endpoint, so there is nothing to hash.
        self::assertArrayNotHasKey('ath', $claims);
        self::assertArrayNotHasKey('nonce', $claims);
    }

    public function testTokenEndpointProofCarriesTheNonceWhenOneIsGiven(): void
    {
        $claims = $this->claims($this->factory()->createForTokenEndpoint(
            $this->key,
            'POST',
            'https://account.acceptance.myparcel.nl/oauth/token',
            'server-issued-nonce'
        ));

        self::assertSame('server-issued-nonce', $claims['nonce']);
        self::assertArrayNotHasKey('ath', $claims);
    }

    public function testResourceProofHashesTheAccessTokenIntoAth(): void
    {
        $claims = $this->claims($this->factory()->createForResourceRequest(
            $this->key,
            'POST',
            'https://generic.ecommerce.api.acceptance.myparcel.nl/orders',
            self::RFC9449_TOKEN
        ));

        self::assertSame(self::RFC9449_ATH, $claims['ath']);

        // The resource server issues no nonces, so a resource proof never carries one.
        self::assertArrayNotHasKey('nonce', $claims);
    }

    public function testHeaderIsTypeDpopJwtWithThePublicKey(): void
    {
        $proof  = $this->factory()->createForTokenEndpoint($this->key, 'POST', 'https://example.test/token');
        $header = json_decode(Str::base64UrlDecode(explode('.', $proof)[0]), true);

        self::assertSame('dpop+jwt', $header['typ']);
        self::assertSame('ES256', $header['alg']);
        self::assertSame($this->key->getPublicJwk(), $header['jwk']);
        self::assertArrayNotHasKey('d', $header['jwk']);
    }

    public function testJtiIsThirtyTwoRandomBytesAndDiffersPerProof(): void
    {
        $factory = new DpopProofFactory();

        $first  = $this->claims($factory->createForTokenEndpoint($this->key, 'POST', 'https://example.test/token'));
        $second = $this->claims($factory->createForTokenEndpoint($this->key, 'POST', 'https://example.test/token'));

        self::assertSame(32, strlen(Str::base64UrlDecode($first['jti'])));
        self::assertNotSame($first['jti'], $second['jti'], 'every proof needs its own jti');
    }

    public function testProofVerifiesAgainstTheKeyThatSignedIt(): void
    {
        $proof = $this->factory()->createForTokenEndpoint($this->key, 'POST', 'https://example.test/token');

        [$header, $payload, $signature] = explode('.', $proof);

        self::assertSame(
            1,
            openssl_verify(
                "$header.$payload",
                EcdsaSignature::signatureToDER(Str::base64UrlDecode($signature)),
                openssl_pkey_get_details($this->key->getKeyMaterial())['key'],
                OPENSSL_ALGO_SHA256
            )
        );
    }

    public function testRejectsAnHtuThatCarriesAQueryOrFragment(): void
    {
        // RFC 9449 says htu is the target URL without query and fragment. The caller strips them,
        // because only the caller knows the request. Passing one through would fail verification.
        $this->expectException(RuntimeException::class);

        $this->factory()->createForResourceRequest(
            $this->key,
            'GET',
            'https://example.test/orders?page=2',
            self::RFC9449_TOKEN
        );
    }

    /**
     * @var \MyParcelNL\Sdk\Crypto\DpopKeyPair
     */
    private $key;

    protected function setUp(): void
    {
        parent::setUp();

        $this->key = DpopKeyPair::generate();
    }

    private function factory(): DpopProofFactory
    {
        return new DpopProofFactory();
    }

    /**
     * @return array<string, mixed>
     */
    private function claims(string $proof): array
    {
        return json_decode(Str::base64UrlDecode(explode('.', $proof)[1]), true);
    }
}
