<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\IamApi;

use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Client;
use MyParcelNL\Sdk\Services\IamApi\IamApiFactory;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

final class IamApiFactoryTest extends TestCase
{
    /**
     * @var array<int, array{method: string, url: string, options: array<string, mixed>}>
     */
    private array $requests = [];

    public function testMakeCreatesTheGeneratedIamClient(): void
    {
        self::assertInstanceOf(Client::class, IamApiFactory::make('plain_api_key', 'https://iam.example.test', 'my-app/1.0'));
    }

    public function testMakeSendsTheApiKeyTheHostTheUserAgentAndTheHeaders(): void
    {
        $client = IamApiFactory::make('plain_api_key', 'https://iam.example.test', 'my-app/1.0', ['x-dmp-test-feature' => 'enabled'], $this->mockHttpClient());

        $client->getWhoami();

        self::assertSame('GET', $this->requests[0]['method']);
        self::assertSame('https://iam.example.test/whoami', $this->requests[0]['url']);
        self::assertSame('Bearer ' . base64_encode('plain_api_key'), $this->header('authorization'));
        self::assertSame('my-app/1.0', $this->header('user-agent'));
        self::assertSame('enabled', $this->header('x-dmp-test-feature'));
    }

    public function testMakeThrowsWhenApiKeyIsEmpty(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('API key cannot be empty');

        IamApiFactory::make('');
    }

    private function mockHttpClient(): MockHttpClient
    {
        return new MockHttpClient(function (string $method, string $url, array $options): MockResponse {
            $this->requests[] = ['method' => $method, 'url' => $url, 'options' => $options];

            return new MockResponse('{"type":"SHOP","id":"1"}', ['http_code' => 200, 'response_headers' => ['content-type' => 'application/json']]);
        });
    }

    private function header(string $name): ?string
    {
        $line = $this->requests[0]['options']['normalized_headers'][$name][0] ?? null;

        return null === $line ? null : substr($line, strlen($name) + 2);
    }
}
