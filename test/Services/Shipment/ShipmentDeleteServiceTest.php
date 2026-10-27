<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Shipment;

use MyParcelNL\Sdk\Client\Jane\JaneClientFactory;
use MyParcelNL\Sdk\Services\Shipment\ShipmentDeleteService;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

final class ShipmentDeleteServiceTest extends TestCase
{
    /**
     * @var array<int, array{method: string, url: string, headers: string[]}>
     */
    private array $requests = [];

    public function testDeleteManyReturnsEarlyOnEmptyIds(): void
    {
        $this->createService()->deleteMany([]);

        self::assertSame([], $this->requests);
    }

    public function testDeleteManySendsOneRequestWithAllIds(): void
    {
        $service = $this->createService();
        $service->setUserAgentForProposition('Magento', '2.4.7');

        $service->deleteMany([10, 20]);

        self::assertCount(1, $this->requests);
        self::assertSame('DELETE', $this->requests[0]['method']);
        self::assertSame('https://api.myparcel.nl/shipments/10%3B20', $this->requests[0]['url']);
        self::assertContains('User-Agent: ' . $service->getUserAgentHeader(), $this->requests[0]['headers']);
    }

    private function createService(): ShipmentDeleteService
    {
        $httpClient = new MockHttpClient(function (string $method, string $url, array $options): MockResponse {
            $this->requests[] = ['method' => $method, 'url' => $url, 'headers' => $options['headers']];

            return new MockResponse('', ['http_code' => 204]);
        });

        return new ShipmentDeleteService($this->getApiKey(), JaneClientFactory::coreApi('my-api-key', null, null, [], $httpClient));
    }
}
