<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services;

use Exception;
use MyParcelNL\Sdk\Model\Carrier\CarrierPostNL;
use MyParcelNL\Sdk\Services\Web\DropOffPointWebService;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class DropOffPointServiceTest extends TestCase
{
    /**
     * @throws \MyParcelNL\Sdk\Exception\ApiException
     * @throws \MyParcelNL\Sdk\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\Exception\MissingFieldException
     * @throws \Exception
     */
    public function testGetDropOffPoint(): void
    {
        // Mock response for GET /drop_off_points?external_identifier=171963&carrier_id=1
        $mockResponse = [
            'response' => json_encode([
                'data' => [
                    'drop_off_points' => [
                        [
                            'location_code' => '171963',
                            'location_name' => 'PostNL Punt Arnhem',
                            'street' => 'Steenstraat',
                            'number' => '24',
                            'number_suffix' => null,
                            'postal_code' => '6825HT',
                            'city' => 'Arnhem',
                            'cc' => 'NL',
                            'region' => null,
                            'state' => null,
                            'retail_network_id' => 'PNPNL-01'
                        ]
                    ]
                ]
            ]),
            'headers' => [],
            'code' => 200
        ];

        $mockCurl = $this->mockCurl();
        $mockCurl->shouldReceive('write')->once()->andReturnSelf();
        $mockCurl->shouldReceive('getResponse')->once()->andReturn($mockResponse);
        $mockCurl->shouldReceive('close')->once()->andReturnSelf();

        $service = (new DropOffPointWebService(new CarrierPostNL()))->setApiKey($this->getApiKey());
        $result  = $service->getDropOffPoint('171963');

        if ($result) {
            self::assertEquals('171963', $result->getLocationCode());
        } else {
            throw new Exception('Not one drop off point returned for external identifier');
        }
    }

    /**
     * @throws \MyParcelNL\Sdk\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\Exception\ApiException
     * @throws \MyParcelNL\Sdk\Exception\MissingFieldException
     * @throws \Exception
     */
    public function testGetDropOffPoints(): void
    {
        // Mock response for GET /drop_off_points?postal_code=6825ME&carrier_id=1
        $mockResponse = [
            'response' => json_encode([
                'data' => [
                    'drop_off_points' => [
                        [
                            'location_code' => '171963',
                            'location_name' => 'PostNL Punt Arnhem',
                            'street' => 'Steenstraat',
                            'number' => '24',
                            'number_suffix' => null,
                            'postal_code' => '6825HT',
                            'city' => 'Arnhem',
                            'cc' => 'NL',
                            'region' => null,
                            'state' => null,
                            'retail_network_id' => 'PNPNL-01'
                        ],
                        [
                            'location_code' => '173445',
                            'location_name' => 'Primera Arnhem',
                            'street' => 'Noordsingel',
                            'number' => '156',
                            'number_suffix' => 'A',
                            'postal_code' => '6825NZ',
                            'city' => 'Arnhem',
                            'cc' => 'NL',
                            'region' => null,
                            'state' => null,
                            'retail_network_id' => 'PNPNL-02'
                        ]
                    ]
                ]
            ]),
            'headers' => [],
            'code' => 200
        ];

        $mockCurl = $this->mockCurl();
        $mockCurl->shouldReceive('write')->once()->andReturnSelf();
        $mockCurl->shouldReceive('getResponse')->once()->andReturn($mockResponse);
        $mockCurl->shouldReceive('close')->once()->andReturnSelf();

        $service       = (new DropOffPointWebService(new CarrierPostNL()))->setApiKey($this->getApiKey());
        $dropOffPoints = $service->getDropOffPoints('6825ME');

        self::assertNotEmpty($dropOffPoints->all(), 'No dropoff points found');
    }
}
