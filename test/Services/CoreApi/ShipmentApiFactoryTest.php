<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\CoreApi;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Client;
use MyParcelNL\Sdk\Services\CoreApi\ShipmentApiFactory;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class ShipmentApiFactoryTest extends TestCase
{
    /**
     * @var array{url: string, headers: array<string, string[]>}|null
     */
    private ?array $request = null;

    public function testMakeReturnsTheJaneClient(): void
    {
        $this->assertInstanceOf(Client::class, ShipmentApiFactory::make('test-key'));
    }

    public function testMakeThrowsWhenApiKeyIsEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);

        ShipmentApiFactory::make('');
    }

    public function testMakeSendsTheKeyHostUserAgentAndHeaders(): void
    {
        ShipmentApiFactory::make(
            'test-key',
            'https://api.example.com',
            'MyApp/1.0',
            ['x-dmp-no-tracking' => 'true'],
            $this->mockHttpClient()
        )->deleteShipments('1');

        $this->assertSame('https://api.example.com/shipments/1', $this->request['url']);
        $this->assertSame(['Bearer ' . base64_encode('test-key')], $this->request['headers']['authorization']);
        $this->assertSame(['MyApp/1.0'], $this->request['headers']['user-agent']);
        $this->assertSame(['true'], $this->request['headers']['x-dmp-no-tracking']);
    }

    public function testMakeWithoutHeadersSendsNoFeatureFlags(): void
    {
        ShipmentApiFactory::make('test-key', null, null, [], $this->mockHttpClient())->deleteShipments('1');

        $this->assertSame('https://api.myparcel.nl/shipments/1', $this->request['url']);
        $this->assertArrayNotHasKey('x-dmp-no-tracking', $this->request['headers']);
    }

    public function testMakeReadsTheApiKeyFromTheEnvironment(): void
    {
        $previous = getenv('API_KEY');
        putenv('API_KEY=env-key');

        try {
            ShipmentApiFactory::make(null, null, null, [], $this->mockHttpClient())->deleteShipments('1');
        } finally {
            putenv(false === $previous ? 'API_KEY' : 'API_KEY=' . $previous);
        }

        $this->assertSame(['Bearer ' . base64_encode('env-key')], $this->request['headers']['authorization']);
    }

    public function testDefaultHttpTimeoutConstant(): void
    {
        $this->assertSame(10, ShipmentApiFactory::DEFAULT_HTTP_TIMEOUT);
    }

    private function mockHttpClient(): MockHttpClient
    {
        return new MockHttpClient(function (string $method, string $url, array $options): MockResponse {
            $headers = [];

            foreach ($options['normalized_headers'] as $name => $lines) {
                $headers[$name] = array_map(
                    static fn(string $line): string => substr($line, strlen($name) + 2),
                    $lines
                );
            }

            $this->request = ['url' => $url, 'headers' => $headers];

            return new MockResponse('', ['http_code' => 204]);
        });
    }
}
