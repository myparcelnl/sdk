<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Connect;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException as GuzzleConnectException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use MyParcelNL\Sdk\Exception\CommandCheckUnavailableException;
use MyParcelNL\Sdk\Model\Connect\ConnectConfig;
use MyParcelNL\Sdk\Model\Connect\ConnectPlatform;
use MyParcelNL\Sdk\Services\Connect\JwksProvider;
use MyParcelNL\Sdk\Test\Bootstrap\InMemoryJwksStorage;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

final class JwksProviderTest extends TestCase
{
    /**
     * The test key of MyParcel's test vector for signed commands.
     */
    private const JWK = [
        'kty' => 'EC',
        'crv' => 'P-256',
        'x'   => 'asFsz9G-vPTdSU8vaN4Kx4fPKLQgaddM0inPXprUFvs',
        'y'   => 'u0fQb-7jTfCYBjsjvRzyVXoI0TVJCnsLHZJqaHKns0c',
        'kid' => '-Yh7UvHZxy_W_29MrByY0Wd2XDRbcWFRLkiZKMkTRc0',
        'alg' => 'ES256',
        'use' => 'sig',
    ];

    private const KID = self::JWK['kid'];

    /**
     * @var \MyParcelNL\Sdk\Test\Bootstrap\InMemoryJwksStorage
     */
    private $storage;

    /**
     * @var array<int, array{request: \Psr\Http\Message\RequestInterface, response: mixed}>
     */
    private $history = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->storage = new InMemoryJwksStorage();
        $this->history = [];
    }

    public function testFetchesFromTheWellKnownUrlOfTheConnectHostAndStoresTheSet(): void
    {
        $key = $this->provider(self::jwks([self::JWK]))->findKey(self::KID);

        self::assertNotNull($key);
        self::assertSame('ES256', $key->getAlgorithm());
        self::assertCount(1, $this->history);
        self::assertSame(
            'https://shopify.ecommerce.api.acceptance.myparcel.nl/.well-known/jwks.json',
            (string) $this->history[0]['request']->getUri()
        );
        self::assertSame(['keys' => [self::JWK]], $this->storage->row()['jwks']);
        self::assertEqualsWithDelta(time(), $this->storage->row()['fetchedAt'], 2);
    }

    public function testTheKeyVerifiesTheVectorSignature(): void
    {
        $key = $this->provider(self::jwks([self::JWK]))->findKey(self::KID);

        // The test vector's signature over its signature base, as DER for openssl.
        $base = "\"@method\": POST\n"
            . "\"@target-uri\": https://shop.example.com/?route=myparcel/commands\n"
            . "\"content-type\": application/json\n"
            . "\"content-digest\": sha-256=:DZMGsdBhwD5nmPui/dt5dfIYL0V2Z8VHQf2SBuljZM8=:\n"
            . '"@signature-params": ("@method" "@target-uri" "content-type" "content-digest");created=1791806400;expires=1791806460;keyid="-Yh7UvHZxy_W_29MrByY0Wd2XDRbcWFRLkiZKMkTRc0";alg="ecdsa-p256-sha256"';
        $raw = base64_decode('3cKROZe5Wx8l+mjuId67cKMypYxNvkq3oaFs1NRfYrgb2xYa81lEr2xLK/XTcQe0OTdIZqH6bSpIHw4pj0u/FQ==', true);

        self::assertSame(1, openssl_verify(
            $base,
            \MyParcelNL\Sdk\Crypto\EcdsaSignature::signatureToDER($raw),
            $key->getKeyMaterial(),
            OPENSSL_ALGO_SHA256
        ));
    }

    public function testUsesAStoredSetYoungerThanAnHourWithoutARequest(): void
    {
        $this->seed([self::JWK], time() - 3599);

        $key = $this->provider()->findKey(self::KID);

        self::assertNotNull($key);
        self::assertCount(0, $this->history);
        self::assertSame(0, $this->storage->saveCount());
    }

    public function testFetchesAgainWhenTheStoredSetIsAnHourOld(): void
    {
        $this->seed([], time() - 3600);

        $key = $this->provider(self::jwks([self::JWK]))->findKey(self::KID);

        self::assertNotNull($key);
        self::assertCount(1, $this->history);
    }

    public function testAFetchTimeInTheFutureCountsAsStale(): void
    {
        // A clock that stepped back, or a tampered row, must not freeze the set forever.
        $this->seed([], time() + 600);

        $key = $this->provider(self::jwks([self::JWK]))->findKey(self::KID);

        self::assertNotNull($key);
        self::assertCount(1, $this->history);
    }

    public function testDoesNotFollowARedirect(): void
    {
        $this->expectException(CommandCheckUnavailableException::class);

        $this->provider(new Response(302, ['Location' => 'http://elsewhere.test/jwks.json']), self::jwks([self::JWK]))
            ->findKey(self::KID);
    }

    public function testAnUnknownKidWithinAMinuteOfTheLastFetchIsNullWithoutARequest(): void
    {
        $this->seed([], time() - 59);

        self::assertNull($this->provider()->findKey(self::KID));
        self::assertCount(0, $this->history);
    }

    public function testAnUnknownKidAMinuteAfterTheLastFetchFetchesOnceMore(): void
    {
        $this->seed([], time() - 60);

        $key = $this->provider(self::jwks([self::JWK]))->findKey(self::KID);

        self::assertNotNull($key);
        self::assertCount(1, $this->history);
        self::assertEqualsWithDelta(time(), $this->storage->row()['fetchedAt'], 2);
    }

    public function testAKidThatIsStillUnknownAfterTheRefetchIsNull(): void
    {
        $this->seed([], time() - 60);

        self::assertNull($this->provider(self::jwks([self::JWK]))->findKey('nobody'));
        self::assertCount(1, $this->history);
    }

    public function testAnUnknownKidRightAfterAStaleFetchDoesNotFetchTwice(): void
    {
        // Stale set, fetched now, kid still unknown: the refetch window of a minute starts at this
        // fetch, so there is no second request in the same call.
        $this->seed([], time() - 7200);

        self::assertNull($this->provider(self::jwks([self::JWK]))->findKey('nobody'));
        self::assertCount(1, $this->history);
    }

    /**
     * @dataProvider provideKeysThatAreNotEs256OnP256
     */
    public function testAKeyThatIsNotAnEs256KeyOnP256IsNull(array $changes): void
    {
        $this->seed([array_merge(self::JWK, $changes)], time());

        self::assertNull($this->provider()->findKey(self::KID));
    }

    public function provideKeysThatAreNotEs256OnP256(): array
    {
        return [
            'RSA'          => [['kty' => 'RSA']],
            'P-384'        => [['crv' => 'P-384']],
            'ES384'        => [['alg' => 'ES384']],
            'no alg'       => [['alg' => null]],
            'no x'         => [['x' => null]],
        ];
    }

    /**
     * @dataProvider provideFailedFetches
     */
    public function testAFailedFetchIsUnavailableAndStoresNothing($answer): void
    {
        $this->expectException(CommandCheckUnavailableException::class);

        try {
            $this->provider($answer)->findKey(self::KID);
        } finally {
            self::assertNull($this->storage->row());
        }
    }

    public function provideFailedFetches(): array
    {
        return [
            'network'       => [new GuzzleConnectException('timed out', new Request('GET', '/'))],
            '500'           => [new Response(500, [], '{"error":"server_error"}')],
            'not JSON'      => [new Response(200, [], '<html>')],
            'no keys'       => [new Response(200, [], '{"foo":[]}')],
            'keys not list' => [new Response(200, [], '{"keys":"x"}')],
            'empty set'     => [new Response(200, [], '{"keys":[]}')],
            'no EC key'     => [new Response(200, [], '{"keys":[{"kty":"RSA","kid":"r","alg":"RS256"}]}')],
        ];
    }

    /**
     * @param \GuzzleHttp\Psr7\Response|\Throwable ...$answers
     */
    private function provider(...$answers): JwksProvider
    {
        $handler = HandlerStack::create(new MockHandler($answers));
        $handler->push(Middleware::history($this->history));

        return new JwksProvider(
            (new ConnectConfig(ConnectPlatform::SHOPIFY, 'an encryption key'))->withAcceptance(true),
            $this->storage,
            new Client(['handler' => $handler])
        );
    }

    private static function jwks(array $keys): Response
    {
        return new Response(200, ['Content-Type' => 'application/json'], (string) json_encode(['keys' => $keys]));
    }

    private function seed(array $keys, int $fetchedAt): void
    {
        $this->storage->seed(['version' => 1, 'jwks' => ['keys' => $keys], 'fetchedAt' => $fetchedAt]);
    }
}
