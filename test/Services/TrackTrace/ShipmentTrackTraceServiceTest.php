<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\TrackTrace;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Api\ShipmentApi;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\FixedShipmentRecipient;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentDefsTrackTrace;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentResponsesTracktraces;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentResponsesTracktracesData;
use MyParcelNL\Sdk\Services\TrackTrace\ShipmentTrackTraceService;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

final class ShipmentTrackTraceServiceTest extends TestCase
{
    public function testFetchTrackTraceDataThrowsOnEmptyIds(): void
    {
        $api = $this->createMock(ShipmentApi::class);

        $service = new ShipmentTrackTraceService($this->getApiKey(), $api);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('At least one shipment ID is required');

        $service->fetchTrackTraceData([]);
    }

    public function testFetchTrackTraceDataCallsGeneratedEndpointAndMapsByShipmentId(): void
    {
        $tt1 = new ShipmentDefsTrackTrace([
            'shipment_id' => 123,
            'link_tracktrace' => 'https://track.test/123',
            'code' => 'DELIVERED',
        ]);
        $tt2 = new ShipmentDefsTrackTrace([
            'shipment_id' => 456,
            'link_tracktrace' => 'https://track.test/456',
            'code' => 'IN_TRANSIT',
        ]);

        $api = $this->createMock(ShipmentApi::class);
        $api->expects(self::once())
            ->method('getTrackTracesByIds')
            ->with(self::identicalTo('123;456'), self::isType('string'))
            ->willReturn($this->buildTracktracesResponse([$tt1, $tt2]));

        $service = new ShipmentTrackTraceService($this->getApiKey(), $api);
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

    public function testRecipientIsDeserializedAsFixedShipmentRecipient(): void
    {
        $recipient = new FixedShipmentRecipient([
            'cc' => 'NL',
            'street' => 'Antareslaan',
            'number' => '31',
            'postal_code' => '2132JE',
            'city' => 'Hoofddorp',
            'person' => 'Test Person',
        ]);
        $tt = new ShipmentDefsTrackTrace([
            'shipment_id' => 99,
            'code' => 'DELIVERED',
            'description' => 'Package delivered',
            'recipient' => $recipient,
        ]);

        $api = $this->createMock(ShipmentApi::class);
        $api->method('getTrackTracesByIds')
            ->willReturn($this->buildTracktracesResponse([$tt]));

        $service = new ShipmentTrackTraceService($this->getApiKey(), $api);
        $result = $service->fetchTrackTraceData([99]);

        self::assertArrayHasKey(99, $result);
        $trackTrace = $result[99];

        self::assertSame('DELIVERED', $trackTrace->getCode());
        self::assertSame('Package delivered', $trackTrace->getDescription());

        $resultRecipient = $trackTrace->getRecipient();
        self::assertInstanceOf(FixedShipmentRecipient::class, $resultRecipient);
        self::assertSame('Antareslaan', $resultRecipient->getStreet());
        self::assertSame('NL', $resultRecipient->getCc());
    }

    public function testEmptyTrackTracesResponseReturnsEmptyArray(): void
    {
        $api = $this->createMock(ShipmentApi::class);
        $api->method('getTrackTracesByIds')
            ->willReturn($this->buildTracktracesResponse([]));

        $service = new ShipmentTrackTraceService($this->getApiKey(), $api);

        self::assertSame([], $service->fetchTrackTraceData([1]));
    }

    private function buildTracktracesResponse(array $tracktraces): ShipmentResponsesTracktraces
    {
        $data = new ShipmentResponsesTracktracesData();
        $data->setTracktraces($tracktraces);

        $response = new ShipmentResponsesTracktraces();
        $response->setData($data);

        return $response;
    }
}
