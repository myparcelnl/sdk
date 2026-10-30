<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\TrackTrace;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Jane\JaneClientFactory;
use MyParcelNL\Sdk\Model\TrackTrace\TrackTrace;
use MyParcelNL\Sdk\Services\TrackTrace\ShipmentTrackTraceService;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

final class ShipmentTrackTraceServiceTest extends TestCase
{
    /**
     * @var array<int, array{method: string, url: string, headers: string[]}>
     */
    private array $requests = [];

    public function testFetchTrackTraceDataThrowsOnEmptyIds(): void
    {
        $service = $this->createService([]);

        try {
            $service->fetchTrackTraceData([]);
            self::fail('Empty shipment IDs must throw.');
        } catch (InvalidArgumentException $exception) {
            self::assertSame('At least one shipment ID is required', $exception->getMessage());
        }

        self::assertSame([], $this->requests);
    }

    public function testFetchTrackTraceDataMapsTheResultsByShipmentId(): void
    {
        $service = $this->createService([
            ['shipment_id' => 123, 'link_tracktrace' => 'https://track.test/123', 'code' => 'DELIVERED'],
            ['shipment_id' => 456, 'link_tracktrace' => 'https://track.test/456', 'code' => 'IN_TRANSIT'],
        ]);
        $service->setUserAgentForProposition('Magento', '2.4.7');

        $result = $service->fetchTrackTraceData([123, 456]);

        self::assertSame('GET', $this->requests[0]['method']);
        self::assertSame('https://api.myparcel.nl/tracktraces/123%3B456', $this->requests[0]['url']);
        self::assertContains('User-Agent: ' . $service->getUserAgentHeader(), $this->requests[0]['headers']);
        self::assertSame([123, 456], array_keys($result));
        self::assertInstanceOf(TrackTrace::class, $result[123]);
        self::assertSame('https://track.test/123', $result[123]->getLinkTracktrace());
        self::assertSame('https://track.test/456', $result[456]->getLinkTracktrace());
        self::assertSame('DELIVERED', $result[123]->getCode());
        self::assertSame('IN_TRANSIT', $result[456]->getCode());
    }

    public function testRecipientIsRead(): void
    {
        $result = $this->createService([[
            'shipment_id' => 99,
            'code'        => 'DELIVERED',
            'description' => 'Package delivered',
            'recipient'   => [
                'cc'          => 'NL',
                'street'      => 'Antareslaan',
                'number'      => '31',
                'postal_code' => '2132JE',
                'city'        => 'Hoofddorp',
                'person'      => 'Test Person',
            ],
        ]])->fetchTrackTraceData([99]);

        self::assertSame('Package delivered', $result[99]->getDescription());
        self::assertSame('Antareslaan', $result[99]->getRecipient()->street);
        self::assertSame('NL', $result[99]->getRecipient()->cc);
    }

    public function testATrackTraceEncodesToJsonWithTheApiNames(): void
    {
        $result = $this->createService([['shipment_id' => 7, 'link_tracktrace' => 'https://track.test/7', 'code' => 'DELIVERED']])
            ->fetchTrackTraceData([7]);

        $json = json_decode(json_encode($result[7], JSON_THROW_ON_ERROR), true);

        self::assertSame(7, $json['shipment_id']);
        self::assertSame('DELIVERED', $json['code']);
        self::assertSame('https://track.test/7', $json['link_tracktrace']);
    }

    public function testATrackTraceWithoutShipmentIdIsSkipped(): void
    {
        $result = $this->createService([['code' => 'DELIVERED'], ['shipment_id' => 7, 'code' => 'DELIVERED']])
            ->fetchTrackTraceData([7]);

        self::assertSame([7], array_keys($result));
    }

    public function testEmptyTrackTracesResponseReturnsEmptyArray(): void
    {
        self::assertSame([], $this->createService([])->fetchTrackTraceData([1]));
    }

    /**
     * @param array<int, array<string, mixed>> $tracktraces The track & trace items in the response.
     */
    private function createService(array $tracktraces): ShipmentTrackTraceService
    {
        $body = json_encode(['data' => ['tracktraces' => $tracktraces]], JSON_THROW_ON_ERROR);

        $httpClient = new MockHttpClient(function (string $method, string $url, array $options) use ($body): MockResponse {
            $this->requests[] = ['method' => $method, 'url' => $url, 'headers' => $options['headers']];

            return new MockResponse($body, ['http_code' => 200, 'response_headers' => ['content-type' => 'application/json']]);
        });

        return new ShipmentTrackTraceService($this->getApiKey(), JaneClientFactory::coreApi('my-api-key', null, null, [], $httpClient));
    }
}
