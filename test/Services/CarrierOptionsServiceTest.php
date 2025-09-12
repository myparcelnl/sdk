<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services;

use MyParcelNL\Sdk\Model\Account\CarrierOptions;
use MyParcelNL\Sdk\Model\Account\Shop;
use MyParcelNL\Sdk\Services\Web\AccountWebService;
use MyParcelNL\Sdk\Services\Web\CarrierOptionsWebService;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class CarrierOptionsServiceTest extends TestCase
{
    /**
     * @throws \MyParcelNL\Sdk\Exception\ApiException
     * @throws \MyParcelNL\Sdk\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\Exception\MissingFieldException
     * @throws \Exception
     * @throws \Exception
     */
    public function testGetAccounts(): void
    {
        // Mock response for GET /accounts
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

        // Mock response for GET /carrier_management/shops/67890/carrier_options
        $carrierOptionsMockResponse = [
            'response' => json_encode([
                'data' => [
                    'carrier_options' => [
                        [
                            'enabled' => true,
                            'optional' => false,
                            'carrier_id' => 1,
                            'carrier' => [
                                'id' => 1
                            ],
                            'label' => 'PostNL',
                            'type' => 'delivery'
                        ],
                        [
                            'enabled' => true,
                            'optional' => true,
                            'carrier_id' => 8,
                            'carrier' => [
                                'id' => 8
                            ],
                            'label' => 'DPD',
                            'type' => 'delivery'
                        ]
                    ]
                ]
            ]),
            'headers' => [],
            'code' => 200
        ];

        $mockCurl = $this->mockCurl();
        
        // First call: AccountWebService->getAccount()
        $mockCurl->shouldReceive('write')->once()->andReturnSelf();
        $mockCurl->shouldReceive('getResponse')->once()->andReturn($accountMockResponse);
        $mockCurl->shouldReceive('close')->once()->andReturnSelf();
        
        // Second call: CarrierOptionsWebService->getCarrierOptions()
        $mockCurl->shouldReceive('write')->once()->andReturnSelf();
        $mockCurl->shouldReceive('getResponse')->once()->andReturn($carrierOptionsMockResponse);
        $mockCurl->shouldReceive('close')->once()->andReturnSelf();

        $accountService        = (new AccountWebService())->setApiKey($this->getApiKey());
        $carrierOptionsService = (new CarrierOptionsWebService())->setApiKey($this->getApiKey());

        $accountService->getAccount()
            ->getShops()
            ->first(static function (Shop $shop) use ($carrierOptionsService) {
                $result = $carrierOptionsService->getCarrierOptions($shop->getId());

                self::assertInstanceOf(CarrierOptions::class, $result->first());
            });
    }
}
