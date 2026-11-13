<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Client\Jane;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Client as CoreApiClient;
use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Client as IamApiClient;
use MyParcelNL\Sdk\Client\Jane\JaneClientFactory;
use MyParcelNL\Sdk\Test\Bootstrap\BuildsShipmentsResponse;
use MyParcelNL\Sdk\Test\Bootstrap\MocksHttpClient;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use ReflectionMethod;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;

final class JaneClientFactoryTest extends TestCase
{
    use BuildsShipmentsResponse;
    use MocksHttpClient;

    public function testSendsTheApiKeyTheUserAgentAndTheHeaders(): void
    {
        $client = JaneClientFactory::coreApi(
            'my-api-key',
            null,
            'MyParcelNL-SDK/12.0.0',
            ['x-dmp-test-feature' => 'enabled'],
            $this->mockHttpClient(self::shipmentsResponse([]))
        );

        $client->getShipments(queryParameters: ['reference_identifier' => 'order-1']);

        self::assertCount(1, $this->requests);
        self::assertSame('GET', $this->requests[0]['method']);
        self::assertSame('https://api.myparcel.nl/shipments?reference_identifier=order-1', $this->requests[0]['url']);
        self::assertSame('Bearer ' . base64_encode('my-api-key'), $this->header('authorization'));
        self::assertSame('MyParcelNL-SDK/12.0.0', $this->header('user-agent'));
        self::assertSame('enabled', $this->header('x-dmp-test-feature'));
    }

    public function testSendsTheRequestsToTheHostOverride(): void
    {
        $client = JaneClientFactory::coreApi(
            'my-api-key',
            'https://api.acceptance.myparcel.nl',
            null,
            [],
            $this->mockHttpClient(self::shipmentsResponse([]))
        );

        $client->getShipments();

        self::assertSame('https://api.acceptance.myparcel.nl/shipments', $this->requests[0]['url']);
    }

    public function testSendsTheIamRequestsWithTheApiKeyTheUserAgentAndTheHostOverride(): void
    {
        $client = JaneClientFactory::iamApi(
            'my-api-key',
            'https://iam.acceptance.myparcel.nl',
            'MyParcelNL-SDK/12.0.0',
            ['x-dmp-test-feature' => 'enabled'],
            $this->mockHttpClient('{"type":"SHOP"}')
        );

        $client->getWhoami();

        self::assertSame('https://iam.acceptance.myparcel.nl/whoami', $this->requests[0]['url']);
        self::assertSame('Bearer ' . base64_encode('my-api-key'), $this->header('authorization'));
        self::assertSame('MyParcelNL-SDK/12.0.0', $this->header('user-agent'));
        self::assertSame('enabled', $this->header('x-dmp-test-feature'));
    }

    public function testAddsNoTimeoutToAnHttpClientThatTheCallerPasses(): void
    {
        $httpClient = new class implements HttpClientInterface {
            /** @var array<string, mixed>|null The options of the last `withOptions()` call. */
            public ?array $options = null;

            public function request(string $method, string $url, array $options = []): ResponseInterface
            {
                throw new \LogicException('The test sends no request.');
            }

            public function stream(ResponseInterface|iterable $responses, ?float $timeout = null): ResponseStreamInterface
            {
                throw new \LogicException('The test sends no request.');
            }

            public function withOptions(array $options): static
            {
                $this->options = $options;

                return $this;
            }
        };

        JaneClientFactory::coreApi('my-api-key', null, null, ['x-dmp-example' => 'true'], $httpClient);

        self::assertNotNull($httpClient->options);
        self::assertArrayNotHasKey('timeout', $httpClient->options, 'The factory must not replace the timeout of a client that the caller passes.');
        self::assertSame(['x-dmp-example' => 'true'], $httpClient->options['headers']);
    }

    /**
     * The factory gives each client the host of its constant, so the constant must be the server URL
     * that the spec gives and that the generated Client::create() uses.
     *
     * @dataProvider provideGeneratedClients
     *
     * @param class-string $clientClass
     * @param string       $host        The host constant of the factory for this client
     */
    public function testTheHostConstantIsTheServerUrlOfTheGeneratedClient(string $clientClass, string $host): void
    {
        $create = new ReflectionMethod($clientClass, 'create');
        $lines  = file((string) $create->getFileName());
        $source = implode('', array_slice($lines, $create->getStartLine() - 1, $create->getEndLine() - $create->getStartLine() + 1));

        preg_match("/ServerUrlHttpClient\\('([^']+)'\\)/", $source, $serverUrl);

        self::assertSame(
            $host,
            rtrim($serverUrl[1] ?? '', '/'),
            'The generated Client::create() uses another server URL. Update the host constant of JaneClientFactory.'
        );
    }

    /**
     * @return array<string, array{class-string, string}>
     */
    public static function provideGeneratedClients(): array
    {
        return [
            'Core API' => [CoreApiClient::class, JaneClientFactory::CORE_API_HOST],
            'IAM API'  => [IamApiClient::class, JaneClientFactory::IAM_API_HOST],
        ];
    }
}
