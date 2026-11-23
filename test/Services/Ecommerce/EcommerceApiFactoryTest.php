<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Ecommerce;

use DateTimeImmutable;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Client;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Endpoint\PostConnectRefresh;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Exception\BadResponseException;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Exception\PostWebhookOrderBadRequestException;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\Order;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\OrderStatus;
use MyParcelNL\Sdk\Exception\ConnectException;
use MyParcelNL\Sdk\Services\Connect\ConnectService;
use MyParcelNL\Sdk\Services\Ecommerce\EcommerceApiFactory;
use MyParcelNL\Sdk\Services\Ecommerce\PushResultReader;
use MyParcelNL\Sdk\Support\Str;
use MyParcelNL\Sdk\Test\Services\Connect\ConnectServiceTestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

/**
 * The factory builds the generated client for a connected shop. A mock stands in for the
 * e-commerce host and records what went out.
 */
final class EcommerceApiFactoryTest extends ConnectServiceTestCase
{
    private const ORDERS_URL = self::HOST . '/webhook/orders';

    /**
     * What went to the e-commerce host, one entry per attempt.
     *
     * @var array<int, array{method: string, url: string, body: mixed, headers: array<string, string[]>, maxRedirects: mixed}>
     */
    private $sent = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->sent = [];
        $this->seedConnectedShop();
    }

    public function testItBuildsTheGeneratedClient(): void
    {
        self::assertInstanceOf(Client::class, EcommerceApiFactory::make($this->service()));
    }

    public function testTheHostComesFromTheConnectConfig(): void
    {
        $this->api($this->service())->postWebhookOrder(requestBody: [self::order()]);

        self::assertSame(self::ORDERS_URL, $this->sent[0]['url']);
    }

    public function testAHostArgumentOverridesItForResourceCallsOnly(): void
    {
        $connect = $this->service();

        $this->api($connect, 'https://tunnel.example.test')->postWebhookOrder(requestBody: [self::order()]);

        self::assertSame('https://tunnel.example.test/webhook/orders', $this->sent[0]['url']);
        self::assertSame(
            self::HOST,
            $connect->getConfig()->getHost(),
            'the connect calls keep going to the host that ConnectConfig builds'
        );
    }

    public function testAnEmptyHostReadsAsNotProvided(): void
    {
        // An unset setting of a consumer arrives as '' and not as null. The other API factories
        // read it in the same way.
        $this->api($this->service(), '')->postWebhookOrder(requestBody: [self::order()]);

        self::assertSame(self::ORDERS_URL, $this->sent[0]['url']);
    }

    public function testResourceCallsCarryTheSdkUserAgent(): void
    {
        $this->api($this->service())->postWebhookOrder(requestBody: [self::order()]);

        self::assertStringContainsString('MyParcelNL-SDK/', $this->userAgent());
    }

    public function testAnEmptyUserAgentReadsAsNotProvided(): void
    {
        $this->api($this->service(), null, '')->postWebhookOrder(requestBody: [self::order()]);

        self::assertStringContainsString('MyParcelNL-SDK/', $this->userAgent());
    }

    public function testAPluginNameGoesInFrontOfTheSdkUserAgent(): void
    {
        $connect = $this->service();
        $connect->setUserAgentForProposition('MyParcelNL-WooCommerce', '5.1.0');

        $this->api($connect)->postWebhookOrder(requestBody: [self::order()]);

        self::assertStringStartsWith('MyParcelNL-WooCommerce/5.1.0 MyParcelNL-SDK/', $this->userAgent());
    }

    public function testAUserAgentArgumentWins(): void
    {
        $this->api($this->service(), null, 'my-app/1.0')->postWebhookOrder(requestBody: [self::order()]);

        self::assertSame('my-app/1.0', $this->userAgent());
    }

    public function testAPushGoesOutSignedForItsFullUrl(): void
    {
        $this->api($this->service())->postWebhookOrder(requestBody: [self::order()]);

        $proof  = substr($this->sent[0]['headers']['dpop'][0], strlen('DPoP: '));
        $claims = (array) json_decode(Str::base64UrlDecode(explode('.', $proof)[1] ?? ''), true);

        self::assertSame(['Authorization: DPoP a.b.c'], $this->sent[0]['headers']['authorization']);
        self::assertSame('POST', $claims['htm']);
        self::assertSame(self::ORDERS_URL, $claims['htu'], 'the proof signs the host and the path');
    }

    public function testAPushSendsTheOrderAsJson(): void
    {
        $this->api($this->service())->postWebhookOrder(requestBody: [self::order()]);

        $body = json_decode((string) $this->sent[0]['body'], true);

        self::assertSame('order-1', $body[0]['sourceId']);
        self::assertSame('OPEN', $body[0]['status']);
        self::assertSame(['Content-Type: application/json'], $this->sent[0]['headers']['content-type']);
    }

    public function testAShopThatIsNotConnectedThrowsAndSendsNothing(): void
    {
        // Nothing is stored, so no call can be signed. The call stops before it goes out.
        $this->storage = new \MyParcelNL\Sdk\Test\Bootstrap\InMemoryConnectStorage();
        $api           = $this->api($this->service());

        try {
            $api->postWebhookOrder(requestBody: [self::order()]);
            self::fail('The push went out without a signature.');
        } catch (ConnectException $exception) {
            self::assertStringContainsString('This shop is not connected to MyParcel', $exception->getMessage());
        }

        self::assertCount(0, $this->sent);
    }

    public function testA401RefreshesTheTokenAndSendsTheSignedPushOnceMore(): void
    {
        $api = $this->api($this->service($this->refreshResponse()), null, null, [
            new MockResponse('{"message":"Unauthorized"}', ['http_code' => 401]),
            self::accepted(),
        ]);

        $response = $api->postWebhookOrder(requestBody: [self::order()]);

        self::assertCount(1, $response, 'the answer of the second attempt came back');
        self::assertCount(2, $this->sent, 'exactly one more attempt');
        self::assertNotSame(
            $this->sent[0]['headers']['authorization'],
            $this->sent[1]['headers']['authorization'],
            'the second attempt carries the refreshed token'
        );
        self::assertNotSame($this->sent[0]['headers']['dpop'], $this->sent[1]['headers']['dpop'], 'and a new proof');
        self::assertSame($this->sent[0]['body'], $this->sent[1]['body'], 'and the same orders');
    }

    public function testARedirectIsNotFollowed(): void
    {
        // A redirect would send the order body to the URL in the Location header.
        $api = $this->api($this->service(), null, null, [
            new MockResponse('', ['http_code' => 307, 'response_headers' => ['location' => 'https://elsewhere.example.test/collect']]),
            self::accepted(),
        ]);

        try {
            $api->postWebhookOrder(requestBody: [self::order()]);
            self::fail('The 307 was followed.');
        } catch (BadResponseException $exception) {
            self::assertSame(307, $exception->getCode(), 'the redirect itself comes back');
        }

        self::assertCount(1, $this->sent);
        self::assertSame(0, $this->sent[0]['maxRedirects']);
    }

    public function testTheAnswerOfAPushReadsIntoOneResultPerOrder(): void
    {
        $api = $this->api($this->service(), null, null, [
            self::json(202, '[{"status":202},{"type":"urn:problem:client","status":400,"title":"Bad Request","detail":"The order is not valid.","instance":"urn:uuid:1","errors":[{"detail":"The status is not allowed.","pointer":"/body/1"}]}]'),
        ]);

        $results = (new PushResultReader())->read($api->postWebhookOrder(requestBody: [self::order(), self::order()]));

        self::assertTrue($results[0]->isAccepted());
        self::assertFalse($results[1]->isAccepted());
        self::assertSame('The order is not valid.', $results[1]->getDetail());
    }

    /**
     * The service on acceptance answers a push with HTTP 200. Its spec says HTTP 202.
     */
    public function testAPushThatMyParcelAnswersWith200ReadsIntoResultsToo(): void
    {
        $api = $this->api($this->service(), null, null, [self::json(200, '[{"status":202}]')]);

        $results = (new PushResultReader())->read($api->postWebhookOrder(requestBody: [self::order()]));

        self::assertTrue($results[0]->isAccepted());
    }

    public function testACallThatMyParcelRefusesAsAWholeThrowsTheGeneratedException(): void
    {
        $api = $this->api($this->service(), null, null, [
            new MockResponse(
                '{"type":"urn:problem:invalid-request-syntax","status":400,"title":"Bad Request","detail":"The body is not valid.","instance":"urn:uuid:1","errors":[{"detail":"Required.","pointer":"/body/0/status"}]}',
                ['http_code' => 400, 'response_headers' => ['content-type' => 'application/problem+json']]
            ),
        ]);

        try {
            $api->postWebhookOrder(requestBody: [self::order()]);
            self::fail('The 400 did not throw.');
        } catch (PostWebhookOrderBadRequestException $exception) {
            self::assertSame('The body is not valid.', $exception->getProblemDetailsInvalidRequestSyntax()->detail);
        }
    }

    public function testAProblemTypeThatTheSpecDoesNotHaveReadsAsTheRawValue(): void
    {
        // MyParcel can add a value before the SDK knows it. Reading it must not throw.
        $api = $this->api($this->service(), null, null, [
            new MockResponse(
                '{"type":"urn:problem:a-new-type","status":400,"title":"Bad Request","detail":"The body is not valid.","instance":"urn:uuid:1"}',
                ['http_code' => 400, 'response_headers' => ['content-type' => 'application/problem+json']]
            ),
        ]);

        try {
            $api->postWebhookOrder(requestBody: [self::order()]);
            self::fail('The 400 did not throw.');
        } catch (PostWebhookOrderBadRequestException $exception) {
            self::assertSame('urn:problem:a-new-type', $exception->getProblemDetailsInvalidRequestSyntax()->type);
        }
    }

    public function testACallThatTheSpecDoesNotSecureGoesOutWithoutATokenAndIsNotSentAgain(): void
    {
        // The connect calls have `security: []` in the spec. The proof in the header is the one
        // that the caller made, and a 401 does not start a refresh.
        $api = $this->api($this->service(), null, null, [
            new MockResponse('{"error":"invalid_dpop_proof"}', ['http_code' => 401]),
        ]);

        $api->executeRawEndpoint(new PostConnectRefresh(headerParameters: ['dpop' => 'the.proof.of-the-caller']));

        self::assertCount(1, $this->sent, 'a 401 on a call without a token is not sent again');
        self::assertSame(0, $this->callCount(), 'no refresh');
        self::assertArrayNotHasKey('authorization', $this->sent[0]['headers']);
        self::assertSame(['dpop: the.proof.of-the-caller'], $this->sent[0]['headers']['dpop']);
    }

    /**
     * The client of the factory, on a mock that answers in order and records what went out. Without
     * answers, the mock accepts one order.
     *
     * @param \Symfony\Component\HttpClient\Response\MockResponse[]|null $answers
     */
    private function api(ConnectService $connect, ?string $host = null, ?string $userAgent = null, ?array $answers = null): Client
    {
        $answers = $answers ?? [self::accepted()];

        $mock = new MockHttpClient(function (string $method, string $url, array $options) use (&$answers) {
            $this->sent[] = [
                'method'       => $method,
                'url'          => $url,
                'body'         => $options['body'] ?? null,
                'headers'      => $options['normalized_headers'] ?? [],
                'maxRedirects' => $options['max_redirects'] ?? null,
            ];

            return array_shift($answers);
        });

        return EcommerceApiFactory::make($connect, $host, $userAgent, $mock);
    }

    private function userAgent(): string
    {
        return substr($this->sent[0]['headers']['user-agent'][0], strlen('User-Agent: '));
    }

    /**
     * An order with only the fields that the spec requires.
     */
    private static function order(): Order
    {
        $order            = new Order();
        $order->sourceId  = 'order-1';
        $order->orderedAt = new DateTimeImmutable('2026-11-23T10:00:00+01:00');
        $order->status    = OrderStatus::Open;

        return $order;
    }

    private static function accepted(): MockResponse
    {
        return self::json(202, '[{"status":202}]');
    }

    private static function json(int $status, string $body): MockResponse
    {
        return new MockResponse($body, ['http_code' => $status, 'response_headers' => ['content-type' => 'application/json']]);
    }
}
