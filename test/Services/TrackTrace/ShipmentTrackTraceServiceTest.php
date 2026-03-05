<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\TrackTrace;

use InvalidArgumentException;
use Mockery;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Api\ShipmentApi;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentDefsTrackTrace;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentResponsesTracktraces;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentResponsesTracktracesData;
use MyParcelNL\Sdk\Services\TrackTrace\ShipmentTrackTraceService;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

final class ShipmentTrackTraceServiceTest extends TestCase
{
    public function testFetchTrackTraceDataThrowsOnEmptyIds(): void
    {
        $service = new ShipmentTrackTraceService($this->getApiKey(), Mockery::mock(ShipmentApi::class));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('At least one shipment ID is required');

        $service->fetchTrackTraceData([]);
    }

    public function testFetchTrackTraceDataCallsGeneratedEndpointAndMapsByShipmentId(): void
    {
        $api = Mockery::mock(ShipmentApi::class);
        $service = new ShipmentTrackTraceService($this->getApiKey(), $api);
        $service->setUserAgentForProposition('Magento', '2.4.7');

        $trackTrace1 = (new ShipmentDefsTrackTrace())
            ->setShipmentId(123)
            ->setLinkTracktrace('https://track.test/123');
        $trackTrace2 = (new ShipmentDefsTrackTrace())
            ->setShipmentId(456)
            ->setLinkTracktrace('https://track.test/456');

        $responseData = (new ShipmentResponsesTracktracesData())
            ->setTracktraces([$trackTrace1, $trackTrace2]);
        $response = (new ShipmentResponsesTracktraces())
            ->setData($responseData);

        $api->shouldReceive('getTrackTracesByIds')
            ->once()
            ->with(
                '123;456',
                Mockery::on(static function ($userAgent): bool {
                    return is_string($userAgent)
                        && false !== strpos($userAgent, 'Magento/2.4.7');
                }),
            )
            ->andReturn($response);

        $result = $service->fetchTrackTraceData([123, 456]);

        self::assertArrayHasKey(123, $result);
        self::assertArrayHasKey(456, $result);
        self::assertSame('https://track.test/123', $result[123]->getLinkTracktrace());
        self::assertSame('https://track.test/456', $result[456]->getLinkTracktrace());
    }
}
