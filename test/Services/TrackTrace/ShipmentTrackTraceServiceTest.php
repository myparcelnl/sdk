<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\TrackTrace;

use InvalidArgumentException;
use Mockery;
use MyParcelNL\Sdk\Services\TrackTrace\ShipmentTrackTraceService;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

final class ShipmentTrackTraceServiceTest extends TestCase
{
    public function testFetchTrackTraceDataThrowsOnEmptyIds(): void
    {
        $service = new ShipmentTrackTraceService($this->getApiKey());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('At least one shipment ID is required');

        $service->fetchTrackTraceData([]);
    }

    public function testFetchTrackTraceDataRequestsCorrectUriAndMapsByShipmentId(): void
    {
        $service = new ShipmentTrackTraceService($this->getApiKey());
        $service->setUserAgentForProposition('Magento', '2.4.7');

        $curlMock = $this->mockCurl();

        $curlMock->shouldReceive('write')
            ->once()
            ->with(
                'GET',
                Mockery::on(static function ($url): bool {
                    return is_string($url)
                        && false !== strpos($url, '/tracktraces/123;456');
                }),
                Mockery::on(static function ($headers): bool {
                    return is_array($headers)
                        && isset($headers['User-Agent'])
                        && false !== strpos($headers['User-Agent'], 'Magento/2.4.7');
                }),
                Mockery::any()
            );

        $curlMock->shouldReceive('getResponse')
            ->once()
            ->andReturn([
                'response' => json_encode([
                    'data' => [
                        'tracktraces' => [
                            [
                                'shipment_id' => 123,
                                'link_tracktrace' => 'https://track.test/123',
                                'history' => [['event' => 'created']],
                            ],
                            [
                                'shipment_id' => 456,
                                'link_tracktrace' => 'https://track.test/456',
                                'history' => [['event' => 'created']],
                            ],
                        ],
                    ],
                ]),
                'code' => 200,
            ]);

        $curlMock->shouldReceive('close')->once();

        $result = $service->fetchTrackTraceData([123, 456]);

        self::assertArrayHasKey(123, $result);
        self::assertArrayHasKey(456, $result);
        self::assertSame('https://track.test/123', $result[123]['link_tracktrace']);
        self::assertSame('https://track.test/456', $result[456]['link_tracktrace']);
    }
}
