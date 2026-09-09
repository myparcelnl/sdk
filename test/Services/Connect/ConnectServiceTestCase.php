<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Connect;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use MyParcelNL\Sdk\Crypto\AesGcmCipher;
use MyParcelNL\Sdk\Model\Connect\ConnectConfig;
use MyParcelNL\Sdk\Model\Connect\ConnectInstallation;
use MyParcelNL\Sdk\Model\Connect\ConnectNonces;
use MyParcelNL\Sdk\Model\Connect\ConnectPlatform;
use MyParcelNL\Sdk\Model\Connect\ConnectState;
use MyParcelNL\Sdk\Model\Connect\ConnectToken;
use MyParcelNL\Sdk\Services\Connect\ConnectService;
use MyParcelNL\Sdk\Support\Str;
use MyParcelNL\Sdk\Test\Bootstrap\InMemoryConnectStorage;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use Psr\Http\Message\RequestInterface;
use RuntimeException;

/**
 * Shared setup for the ConnectService tests: an in-memory storage, a known key pair, and a Guzzle
 * client whose answers the test queues.
 *
 * Queue what MyParcel would answer, then read what went out with request() or proofClaims(). Time is
 * the real clock, so assert an expiry with assertExpiresIn() rather than against a fixed value.
 */
abstract class ConnectServiceTestCase extends TestCase
{
    protected const KEY = 'an encryption key';

    protected const HOST = 'https://generic.ecommerce.api.acceptance.myparcel.nl';

    protected const HTU = 'https://account.acceptance.myparcel.nl/oauth/token';

    protected const CONNECTION_ID = '6f1e6f3e-0000-4000-8000-000000000000';

    /**
     * A real P-256 key and its thumbprint, so a test can mint a token bound to it.
     */
    protected const PEM = <<<PEM
        -----BEGIN PRIVATE KEY-----
        MIGHAgEAMBMGByqGSM49AgEGCCqGSM49AwEHBG0wawIBAQQgqZxQr7HkC6Mvf9kO
        pgfWyVDlKNconxwhdshPNxDX9HGhRANCAATQH04iBrkETGZDiLlanck1EwklhdkJ
        x3IqhVOKB9GZogB6Dv+5luEMQo8+ZVgk6Ppjt9zRdgZ3J2KlmrvUYUxS
        -----END PRIVATE KEY-----
        PEM;

    protected const THUMBPRINT = 'SptVKt3wOR7OKbcJ-joeon3G7dRyDVCFf96loC6OUSc';

    /**
     * @var \MyParcelNL\Sdk\Test\Bootstrap\InMemoryConnectStorage
     */
    protected $storage;

    /**
     * @var array<int, array{request: RequestInterface, response: mixed}>
     */
    protected $history = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->storage = new InMemoryConnectStorage();
        $this->history = [];
    }

    protected function config(): ConnectConfig
    {
        return (new ConnectConfig(ConnectPlatform::GENERIC, self::KEY))->withAcceptance(true);
    }

    /**
     * A service whose HTTP client answers with what you queue, in order. Queue nothing and it
     * answers one successful exchange.
     *
     * @param \GuzzleHttp\Psr7\Response|\Throwable ...$answers
     */
    protected function service(...$answers): ConnectService
    {
        return new ConnectService(
            $this->config(),
            $this->storage,
            $this->httpAnswering(...($answers ?: [$this->tokenResponse()]))
        );
    }

    /**
     * An HTTP client that answers with what you queue and records what went out.
     *
     * @param \GuzzleHttp\Psr7\Response|\Throwable ...$answers
     */
    protected function httpAnswering(...$answers): Client
    {
        $handler = HandlerStack::create(new MockHandler($answers));
        $handler->push(Middleware::history($this->history));

        return new Client(['handler' => $handler]);
    }

    /**
     * What /connect/token answers.
     */
    protected function tokenResponse(?string $dpopNonce = null, ?string $thumbprint = null): Response
    {
        return self::jsonBody(
            200,
            ['connectionId' => self::CONNECTION_ID] + $this->tokenFields($thumbprint),
            self::nonceHeader($dpopNonce)
        );
    }

    /**
     * What /connect/refresh answers: the same fields, minus the connectionId it issued once.
     */
    protected function refreshResponse(?string $dpopNonce = null, ?string $thumbprint = null): Response
    {
        return self::jsonBody(200, $this->tokenFields($thumbprint), self::nonceHeader($dpopNonce));
    }

    /**
     * The fields both answers share.
     *
     * @return array<string, mixed>
     */
    private function tokenFields(?string $thumbprint): array
    {
        return [
            'accessToken' => $this->jwt(['cnf' => ['jkt' => $thumbprint ?? self::THUMBPRINT]]),
            'tokenType'   => 'DPoP',
            'expiresIn'   => 3600,
            'scope'       => 'integration',
        ];
    }

    /**
     * @return array<string, string>
     */
    private static function nonceHeader(?string $dpopNonce): array
    {
        return null === $dpopNonce ? [] : ['DPoP-Nonce' => $dpopNonce];
    }

    /**
     * A failure carrying the error member MyParcel writes.
     *
     * @param array<string, string> $headers
     */
    protected static function errorResponse(int $status, string $error, array $headers = []): Response
    {
        return self::jsonBody($status, ['error' => $error], $headers);
    }

    /**
     * MyParcel refusing a proof until it carries the nonce it names.
     */
    protected static function nonceDemand(string $nonce = 'server-issued-nonce'): Response
    {
        return self::errorResponse(400, 'use_dpop_nonce', ['DPoP-Nonce' => $nonce]);
    }

    /**
     * Put a known key pair in storage, so a test knows the thumbprint a token must be bound to.
     */
    protected function seedInstallation(?string $connectionId = null, ?string $htm = null, ?string $htu = null): void
    {
        $this->storage->saveInstallation(new ConnectInstallation(
            (new AesGcmCipher(self::KEY))->encrypt(self::PEM),
            $connectionId,
            $htm,
            $htu
        ));
    }

    protected function seedToken(string $accessToken, int $expiresAt, string $scope = 'integration'): void
    {
        $this->storage->saveToken(new ConnectToken(
            (new AesGcmCipher(self::KEY))->encrypt($accessToken),
            $expiresAt,
            $scope
        ));
    }

    protected function seedNonces(?string $startNonce, ?int $expiresAt, ?string $dpopNonce): void
    {
        $this->storage->saveNonces(new ConnectNonces($startNonce, $expiresAt, $dpopNonce));
    }

    /**
     * A shop that has already connected: key, connection id, challenge and a fresh token.
     */
    protected function seedConnectedShop(?int $expiresAt = null): void
    {
        $this->seedInstallation(self::CONNECTION_ID, 'POST', self::HTU);
        $this->seedToken('a.b.c', $expiresAt ?? time() + 3600);
    }

    /**
     * An access token shaped like the real one. The SDK reads the payload and never the signature.
     *
     * @param array<string, mixed> $claims
     */
    protected function jwt(array $claims): string
    {
        return 'a.' . Str::base64UrlEncode((string) json_encode($claims)) . '.c';
    }

    /**
     * Run a whole connect flow and return the state it produced.
     *
     * @param \GuzzleHttp\Psr7\Response|\Throwable ...$answers What /connect/token answers.
     */
    protected function connect(...$answers): ConnectState
    {
        // The known key, so the token the answer carries is bound to a thumbprint the test can name.
        if (null === $this->storage->row('installation')) {
            $this->seedInstallation();
        }

        $service = $this->service(...$answers);
        $service->start('My Shop', 'https://shop.example.test');

        return $service->handleCallback([
            'nonce' => $this->storage->row('nonces')['startNonce'],
            'code'  => 'a-code',
            'htm'   => 'POST',
            'htu'   => self::HTU,
        ]);
    }

    protected function callCount(): int
    {
        return count($this->history);
    }

    protected function request(int $index): RequestInterface
    {
        if (!isset($this->history[$index])) {
            throw new RuntimeException(sprintf('There was no call %d, only %d', $index, count($this->history)));
        }

        return $this->history[$index]['request'];
    }

    /**
     * The claims of the proof sent on one call, so a test can look at nonce, htm and htu.
     *
     * @return array<string, mixed>
     */
    protected function proofClaims(int $index): array
    {
        $segments = explode('.', $this->request($index)->getHeaderLine('DPoP'));

        return (array) json_decode(Str::base64UrlDecode($segments[1] ?? ''), true);
    }

    /**
     * Assert a stored expiry is the given number of seconds from now.
     *
     * A window, because the clock is the real one and a run takes a moment.
     */
    protected static function assertExpiresIn(int $seconds, int $actual, string $message = ''): void
    {
        $expected = time() + $seconds;

        self::assertGreaterThanOrEqual($expected - 5, $actual, $message);
        self::assertLessThanOrEqual($expected + 5, $actual, $message);
    }

    /**
     * @return array<string, string>
     */
    protected function query(string $url): array
    {
        parse_str((string) parse_url($url, PHP_URL_QUERY), $query);

        return $query;
    }

    /**
     * @param  array<string, mixed>  $body
     * @param  array<string, string> $headers
     */
    protected static function jsonBody(int $status, array $body, array $headers = []): Response
    {
        return new Response($status, $headers + ['Content-Type' => 'application/json'], (string) json_encode($body));
    }
}
