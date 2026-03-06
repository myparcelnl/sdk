<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\TrackTrace;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Api\ShipmentApi;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentDefsShipmentRecipient;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentDefsTrackTrace;
use MyParcelNL\Sdk\Services\TrackTrace\ShipmentTrackTraceService;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use Psr\Http\Client\ClientInterface;

final class ShipmentTrackTraceServiceTest extends TestCase
{
    public function testFetchTrackTraceDataThrowsOnEmptyIds(): void
    {
        $api = $this->createMock(ShipmentApi::class);
        $httpClient = $this->createMock(ClientInterface::class);

        $service = new ShipmentTrackTraceService($this->getApiKey(), $api, $httpClient);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('At least one shipment ID is required');

        $service->fetchTrackTraceData([]);
    }

    public function testFetchTrackTraceDataCallsGeneratedEndpointAndMapsByShipmentId(): void
    {
        $api = $this->createMock(ShipmentApi::class);
        $api->expects(self::once())
            ->method('getTrackTracesByIdsRequest')
            ->with(self::identicalTo('123;456'), self::isType('string'))
            ->willReturn(new Request('GET', 'https://api.myparcel.nl/tracktraces/123;456'));

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects(self::once())
            ->method('sendRequest')
            ->willReturn($this->createJsonResponse([
                'data' => ['tracktraces' => [
                    [
                        'shipment_id' => 123,
                        'link_tracktrace' => 'https://track.test/123',
                        'code' => 'DELIVERED',
                    ],
                    [
                        'shipment_id' => 456,
                        'link_tracktrace' => 'https://track.test/456',
                        'code' => 'IN_TRANSIT',
                    ],
                ]],
            ]));

        $service = new ShipmentTrackTraceService($this->getApiKey(), $api, $httpClient);
        $service->setUserAgentForProposition('Magento', '2.4.7');

        $result = $service->fetchTrackTraceData([123, 456]);

        self::assertArrayHasKey(123, $result);
        self::assertArrayHasKey(456, $result);
        self::assertInstanceOf(ShipmentDefsTrackTrace::class, $result[123]);
        self::assertInstanceOf(ShipmentDefsTrackTrace::class, $result[456]);
        self::assertSame('https://track.test/123', $result[123]->getLinkTracktrace());
        self::assertSame('https://track.test/456', $result[456]->getLinkTracktrace());
        self::assertSame('DELIVERED', $result[123]->getCode());
        self::assertSame('IN_TRANSIT', $result[456]->getCode());
    }

    public function testRecipientStreetIsPreservedViaConstructorDeserialization(): void
    {
        $api = $this->createMock(ShipmentApi::class);
        $api->method('getTrackTracesByIdsRequest')
            ->willReturn(new Request('GET', 'https://api.myparcel.nl/tracktraces/99'));

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->method('sendRequest')
            ->willReturn($this->createJsonResponse([
                'data' => ['tracktraces' => [
                    [
                        'shipment_id' => 99,
                        'code' => 'DELIVERED',
                        'description' => 'Package delivered',
                        'recipient' => [
                            'cc' => 'NL',
                            'street' => 'Antareslaan',
                            'number' => '31',
                            'postal_code' => '2132JE',
                            'city' => 'Hoofddorp',
                            'person' => 'Test Person',
                        ],
                        'status' => ['current' => 7, 'final' => true],
                        'history' => [['code' => 'A', 'time' => '2024-01-01']],
                    ],
                ]],
            ]));

        $service = new ShipmentTrackTraceService($this->getApiKey(), $api, $httpClient);
        $result = $service->fetchTrackTraceData([99]);

        self::assertArrayHasKey(99, $result);
        $trackTrace = $result[99];

        self::assertSame('DELIVERED', $trackTrace->getCode());
        self::assertSame('Package delivered', $trackTrace->getDescription());

        $recipient = $trackTrace->getRecipient();
        self::assertInstanceOf(ShipmentDefsShipmentRecipient::class, $recipient);
        self::assertSame('Antareslaan', $recipient->getStreet());
        self::assertSame('NL', $recipient->getCc());
        self::assertSame('31', $recipient->getNumber());
        self::assertSame('2132JE', $recipient->getPostalCode());
        self::assertSame('Hoofddorp', $recipient->getCity());
        self::assertSame('Test Person', $recipient->getPerson());
    }

    public function testEmptyTrackTracesResponseReturnsEmptyArray(): void
    {
        $api = $this->createMock(ShipmentApi::class);
        $api->method('getTrackTracesByIdsRequest')
            ->willReturn(new Request('GET', 'https://api.myparcel.nl/tracktraces/1'));

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->method('sendRequest')
            ->willReturn($this->createJsonResponse(['data' => ['tracktraces' => []]]));

        $service = new ShipmentTrackTraceService($this->getApiKey(), $api, $httpClient);

        self::assertSame([], $service->fetchTrackTraceData([1]));
    }

    private function createJsonResponse(array $data): Response
    {
        return new Response(200, ['Content-Type' => 'application/json'], json_encode($data));
    }
}
