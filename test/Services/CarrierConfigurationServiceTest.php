<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services;

use MyParcelNL\Sdk\Model\Account\Shop;
use MyParcelNL\Sdk\Services\Web\AccountWebService;
use MyParcelNL\Sdk\Services\Web\CarrierConfigurationWebService;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class CarrierConfigurationServiceTest extends TestCase
{
    /**
     * @before
     * @return void
     */
    public function before(): void
    {
    }

    /**
     * @throws \MyParcelNL\Sdk\Exception\ApiException
     * @throws \MyParcelNL\Sdk\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\Exception\MissingFieldException
     * @throws \Exception
     */
    public function testGetConfiguration(): void
    {
        // Mock response for GET /accounts (getShop)
        $accountMockResponse = [
            'response' => json_encode([
                'data' => [
                    'accounts' => [
                        [
                            'id' => 12345,
                            'platform_id' => 1,
                            'shops' => [
                                [
                                    'id' => 67890,
                                    'name' => 'Test Shop'
                                ]
                            ]
                        ]
                    ]
                ]
            ]),
            'headers' => [],
            'code' => 200
        ];

        // Mock response for GET /carrier_configuration
        $carrierConfigMockResponse = [
            'response' => json_encode([
                'data' => [
                    'carrier_configurations' => [
                        [
                            'carrier_id' => 1,
                            'default_drop_off_point_identifier' => '217171',
                            'default_cutoff_time' => '09:30'
                        ]
                    ]
                ]
            ]),
            'headers' => [],
            'code' => 200
        ];

        $mockCurl = $this->mockCurl();
        
        // First call: getShop() -> AccountWebService
        $mockCurl->shouldReceive('write')->once()->andReturnSelf();
        $mockCurl->shouldReceive('getResponse')->once()->andReturn($accountMockResponse);
        $mockCurl->shouldReceive('close')->once()->andReturnSelf();
        
        // Second call: getCarrierConfiguration()
        $mockCurl->shouldReceive('write')->once()->andReturnSelf();
        $mockCurl->shouldReceive('getResponse')->once()->andReturn($carrierConfigMockResponse);
        $mockCurl->shouldReceive('close')->once()->andReturnSelf();

        $shop = $this->getShop();
        $carrierConfigurationService = (new CarrierConfigurationWebService())->setApiKey($this->getApiKey());
        $result = $carrierConfigurationService->getCarrierConfiguration(
            $shop->getId(),
            1
        );

        self::assertNotEmpty($result->getDefaultDropOffPointIdentifier());
        self::assertEquals('217171', $result->getDefaultDropOffPointIdentifier());
    }

    /**
     * @throws \MyParcelNL\Sdk\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\Exception\ApiException
     * @throws \MyParcelNL\Sdk\Exception\MissingFieldException
     * @throws \Exception
     */
    public function testGetConfigurations(): void
    {
        // Mock response for GET /accounts (getShop)
        $accountMockResponse = [
            'response' => json_encode([
                'data' => [
                    'accounts' => [
                        [
                            'id' => 12345,
                            'platform_id' => 1,
                            'shops' => [
                                [
                                    'id' => 67890,
                                    'name' => 'Test Shop'
                                ]
                            ]
                        ]
                    ]
                ]
            ]),
            'headers' => [],
            'code' => 200
        ];

        // Mock response for GET /carrier_configurations (multiple)
        $carrierConfigsMockResponse = [
            'response' => json_encode([
                'data' => [
                    'carrier_configurations' => [
                        [
                            'carrier_id' => 1,
                            'default_drop_off_point_identifier' => '217171',
                            'default_cutoff_time' => '09:30'
                        ],
                        [
                            'carrier_id' => 2,
                            'default_drop_off_point_identifier' => '123456',
                            'default_cutoff_time' => '15:00'
                        ]
                    ]
                ]
            ]),
            'headers' => [],
            'code' => 200
        ];

        // Mock de cURL client
        $mockCurl = $this->mockCurl();
        
        // First call: getShop() -> AccountWebService
        $mockCurl->shouldReceive('write')->once()->andReturnSelf();
        $mockCurl->shouldReceive('getResponse')->once()->andReturn($accountMockResponse);
        $mockCurl->shouldReceive('close')->once()->andReturnSelf();
        
        // Second call: getCarrierConfigurations()
        $mockCurl->shouldReceive('write')->once()->andReturnSelf();
        $mockCurl->shouldReceive('getResponse')->once()->andReturn($carrierConfigsMockResponse);
        $mockCurl->shouldReceive('close')->once()->andReturnSelf();

        $shop = $this->getShop();
        $carrierConfigurationService = (new CarrierConfigurationWebService())->setApiKey($this->getApiKey());
        $result = $carrierConfigurationService->getCarrierConfigurations($shop->getId());

        self::assertNotEmpty(
            $result
                ->first()
                ->getDefaultDropOffPointIdentifier()
        );
        
        self::assertEquals('217171', $result->first()->getDefaultDropOffPointIdentifier());
        self::assertCount(2, $result);
    }

    /**
     * @return \MyParcelNL\Sdk\Model\Account\Shop
     * @throws \MyParcelNL\Sdk\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\Exception\ApiException
     * @throws \MyParcelNL\Sdk\Exception\MissingFieldException
     * @throws \Exception
     */
    private function getShop(): Shop
    {
        $accountService = (new AccountWebService())->setApiKey($this->getApiKey());
        return $accountService->getAccount()
            ->getShops()
            ->first();
    }
}
