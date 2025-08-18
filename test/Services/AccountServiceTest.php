<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services;

use MyParcelNL\Sdk\Services\Web\AccountWebService;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class AccountServiceTest extends TestCase
{
    /**
     * @throws \MyParcelNL\Sdk\Exception\ApiException
     * @throws \MyParcelNL\Sdk\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\Exception\MissingFieldException
     * @throws \Exception
     */
    public function testGetAccount(): void
    {
        $mockResponse = [
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

        $mockCurl = $this->mockCurl();
        $mockCurl->shouldReceive('write')->once()->andReturnSelf();
        $mockCurl->shouldReceive('getResponse')->once()->andReturn($mockResponse);
        $mockCurl->shouldReceive('close')->once()->andReturnSelf();

        $result = (new AccountWebService())
            ->setApiKey($this->getApiKey())
            ->getAccount();

        self::assertArrayHasKey('id', $result->toArray());
        self::assertArrayHasKey('platform_id', $result->toArray());
        self::assertArrayHasKey('shops', $result->toArray());
        
        self::assertEquals(12345, $result->getId());
        self::assertEquals(1, $result->getPlatformId());
        self::assertCount(1, $result->getShops());
        self::assertEquals(67890, $result->getShops()->first()->getId());
        self::assertEquals('Test Shop', $result->getShops()->first()->getName());
    }
}
