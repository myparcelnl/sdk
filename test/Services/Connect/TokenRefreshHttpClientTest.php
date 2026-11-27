<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Connect;

use Jane\Component\OpenApiRuntime\Client\Plugin\AuthenticationRegistry;
use MyParcelNL\Sdk\Exception\ConnectException;
use MyParcelNL\Sdk\Services\Connect\ConnectService;
use MyParcelNL\Sdk\Services\Connect\DpopAuthentication;
use MyParcelNL\Sdk\Services\Connect\TokenRefreshHttpClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * The client sends a request once more after a 401. Two clients are mocked here: the one that it
 * wraps, and the one that ConnectService uses to refresh the token.
 */
final class TokenRefreshHttpClientTest extends ConnectServiceTestCase
{
    private const RESOURCE = self::HOST . '/webhook/orders';

    private const ORDERS = '[{"sourceId":"order-42"}]';

    /**
     * The generated client marks a request of a secured operation in this way.
     */
    private const SIGNED = ['extra' => [AuthenticationRegistry::SCOPES_OPTION => ['dpop']]];

    /**
     * What went to the e-commerce host, one entry per attempt.
     *
     * @var array<int, array{method: string, url: string, body: mixed, headers: array<string, string[]>}>
     */
    private $sent = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->sent = [];
        $this->seedConnectedShop();
    }

    public function testASuccessIsPassedStraightBack(): void
    {
        $response = $this->client([self::answer(202, '[{"status":202}]')])
            ->request('POST', self::RESOURCE, self::SIGNED + ['body' => self::ORDERS]);

        self::assertSame('[{"status":202}]', $response->getContent());
        self::assertCount(1, $this->sent);
        self::assertSame(0, $this->callCount(), 'no refresh');
    }

    public function testA401RefreshesTheTokenAndSendsOnceMore(): void
    {
        $response = $this->client([self::answer(401), self::answer(202, '[{"status":202}]')], [$this->refreshResponse()])
            ->request('POST', self::RESOURCE, self::SIGNED + ['body' => self::ORDERS]);

        self::assertSame(202, $response->getStatusCode());
        self::assertCount(2, $this->sent, 'exactly one more attempt');
        self::assertSame(1, $this->callCount(), 'one refresh');
    }

    public function testTheSecondAttemptIsTheSameRequest(): void
    {
        $this->client([self::answer(401), self::answer(202)], [$this->refreshResponse()])
            ->request('POST', self::RESOURCE, self::SIGNED + ['body' => self::ORDERS]);

        self::assertSame(self::ORDERS, $this->sent[0]['body']);
        self::assertSame($this->sent[0]['body'], $this->sent[1]['body'], 'the same body, not an empty one');
        self::assertSame('POST', $this->sent[1]['method']);
        self::assertSame(self::RESOURCE, $this->sent[1]['url']);
    }

    public function testASecond401IsNotSentAgain(): void
    {
        $response = $this->client([self::answer(401), self::answer(401)], [$this->refreshResponse()])
            ->request('POST', self::RESOURCE, self::SIGNED + ['body' => self::ORDERS]);

        self::assertSame(401, $response->getStatusCode());
        self::assertCount(2, $this->sent, 'one more attempt, not a loop');
    }

    public function testAFailureThatIsNotA401IsNotSentAgain(): void
    {
        // Only a 401 means that the token was refused. Another failure would go out twice.
        $response = $this->client([self::answer(500)])->request('POST', self::RESOURCE, self::SIGNED + ['body' => self::ORDERS]);

        self::assertSame(500, $response->getStatusCode());
        self::assertCount(1, $this->sent);
        self::assertSame(0, $this->callCount(), 'no refresh');
    }

    public function testA401OnARequestThatIsNotSignedIsNotSentAgain(): void
    {
        // A connect call carries no token, so a fresh token cannot change its answer.
        $response = $this->client([self::answer(401)])
            ->request('POST', self::HOST . '/connect/token', ['body' => '{"code":"a-code"}']);

        self::assertSame(401, $response->getStatusCode());
        self::assertCount(1, $this->sent, 'no second attempt');
        self::assertSame(0, $this->callCount(), 'no refresh');
    }

    public function testABodyThatCannotGoOutTwiceGivesThe401Back(): void
    {
        // The first attempt reads a stream to the end, so a second attempt would send no orders.
        $stream = fopen('php://memory', 'r+');
        fwrite($stream, self::ORDERS);
        rewind($stream);

        $response = $this->client([self::answer(401)])->request('POST', self::RESOURCE, self::SIGNED + ['body' => $stream]);

        self::assertSame(401, $response->getStatusCode());
        self::assertCount(1, $this->sent, 'no second attempt');
        self::assertSame(0, $this->callCount(), 'no refresh');
    }

    public function testARequestWithoutABodyIsSentAgain(): void
    {
        $response = $this->client([self::answer(401), self::answer(200)], [$this->refreshResponse()])
            ->request('GET', self::RESOURCE, self::SIGNED);

        self::assertSame(200, $response->getStatusCode());
        self::assertCount(2, $this->sent);
    }

    public function testARevokedConnectionThrowsAndDoesNotSendAgain(): void
    {
        // invalid_grant on a refresh means that MyParcel no longer has this connection.
        $client = $this->client([self::answer(401)], [self::errorResponse(400, 'invalid_grant')]);

        try {
            $client->request('POST', self::RESOURCE, self::SIGNED + ['body' => self::ORDERS]);
            self::fail('The 401 came back although the connection is revoked.');
        } catch (ConnectException $exception) {
            self::assertSame('invalid_grant', $exception->getErrorCode());
        }

        self::assertCount(1, $this->sent);
    }

    public function testAClientWithOptionsStillSendsOnceMore(): void
    {
        $client = $this->client([self::answer(401), self::answer(200)], [$this->refreshResponse()])
            ->withOptions(['headers' => ['User-Agent' => 'a-plugin/1.0']]);

        $response = $client->request('GET', self::RESOURCE, self::SIGNED);

        self::assertSame(200, $response->getStatusCode());
        self::assertSame(['User-Agent: a-plugin/1.0'], $this->sent[1]['headers']['user-agent']);
    }

    public function testTheSecondAttemptCarriesAFreshTokenAndANewProof(): void
    {
        $connect = new ConnectService($this->config(), $this->storage, $this->httpAnswering($this->refreshResponse()));
        $client  = $this->signedClient($this->mock([self::answer(401), self::answer(202)]), $connect);

        // The generated client marks a secured operation in this way.
        $client->request('POST', self::RESOURCE, [
            'body'  => self::ORDERS,
            'extra' => [AuthenticationRegistry::SCOPES_OPTION => ['dpop']],
        ]);

        self::assertCount(2, $this->sent);
        self::assertNotSame(
            $this->sent[0]['headers']['authorization'],
            $this->sent[1]['headers']['authorization'],
            'the second attempt must carry the refreshed token'
        );
        self::assertNotSame(
            $this->sent[0]['headers']['dpop'],
            $this->sent[1]['headers']['dpop'],
            'the second attempt must carry a new proof: a proof is single use'
        );
    }

    /**
     * The two clients of the e-commerce client together: the one that signs a request, and the one
     * that sends a request once more after a 401. The second one is on the outside, so a second
     * attempt goes through the signing again.
     */
    private function signedClient(HttpClientInterface $base, ConnectService $connect): HttpClientInterface
    {
        $signing = new AuthenticationRegistry([new DpopAuthentication($connect)]);

        return new TokenRefreshHttpClient($signing($base), $connect);
    }

    /**
     * The client under test, on a mock that answers in order and records what went out.
     *
     * @param \Symfony\Component\HttpClient\Response\MockResponse[] $answers        What the e-commerce host answers.
     * @param \GuzzleHttp\Psr7\Response[]                           $connectAnswers What /connect/refresh answers.
     */
    private function client(array $answers, array $connectAnswers = []): TokenRefreshHttpClient
    {
        return new TokenRefreshHttpClient(
            $this->mock($answers),
            new ConnectService($this->config(), $this->storage, $this->httpAnswering(...$connectAnswers))
        );
    }

    /**
     * A client that answers in order and records what went out.
     *
     * @param \Symfony\Component\HttpClient\Response\MockResponse[] $answers
     */
    private function mock(array $answers): MockHttpClient
    {
        return new MockHttpClient(function (string $method, string $url, array $options) use (&$answers) {
            $this->sent[] = [
                'method'  => $method,
                'url'     => $url,
                'body'    => $options['body'] ?? null,
                'headers' => $options['normalized_headers'] ?? [],
            ];

            return array_shift($answers);
        });
    }

    private static function answer(int $status, string $body = '{"message":"Unauthorized"}'): MockResponse
    {
        return new MockResponse($body, ['http_code' => $status]);
    }
}
