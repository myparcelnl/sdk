<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services;

use MyParcelNL\Sdk\Services\Web\PrinterGroupWebService;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class PrinterGroupServiceTest extends TestCase
{
    /**
     * @throws \MyParcelNL\Sdk\Exception\ApiException
     * @throws \MyParcelNL\Sdk\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\Exception\MissingFieldException
     * @throws \Exception
     */
    public function testGetPrinterGroups(): void
    {
        $mockResponse = [
            'response' => json_encode([
                'results' => [
                    [
                        'id' => '55b53b20-91aa-4a53-8bb2-c4c120df9921',
                        'name' => 'Test Printer Group'
                    ],
                    [
                        'id' => 'd72fd4bf-7d5a-4c25-bffc-140c4c817260',
                        'name' => 'Another Printer Group'
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

        $result = (new PrinterGroupWebService())
            ->setApiKey($this->getApiKey())
            ->getPrinterGroups();

        self::assertCount(2, $result);

        $firstGroup = $result->first();
        self::assertEquals('55b53b20-91aa-4a53-8bb2-c4c120df9921', $firstGroup->getId());
        self::assertEquals('Test Printer Group', $firstGroup->getName());

        $secondGroup = $result->last();
        self::assertEquals('d72fd4bf-7d5a-4c25-bffc-140c4c817260', $secondGroup->getId());
        self::assertEquals('Another Printer Group', $secondGroup->getName());
    }

    /**
     * @throws \MyParcelNL\Sdk\Exception\ApiException
     * @throws \MyParcelNL\Sdk\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\Exception\MissingFieldException
     * @throws \Exception
     */
    public function testGetPrinterGroupsEmpty(): void
    {
        $mockResponse = [
            'response' => json_encode([
                'results' => []
            ]),
            'headers' => [],
            'code' => 200
        ];

        $mockCurl = $this->mockCurl();
        $mockCurl->shouldReceive('write')->once()->andReturnSelf();
        $mockCurl->shouldReceive('getResponse')->once()->andReturn($mockResponse);
        $mockCurl->shouldReceive('close')->once()->andReturnSelf();

        $result = (new PrinterGroupWebService())
            ->setApiKey($this->getApiKey())
            ->getPrinterGroups();

        self::assertCount(0, $result);
    }

    /**
     * @throws \MyParcelNL\Sdk\Exception\ApiException
     * @throws \MyParcelNL\Sdk\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\Exception\MissingFieldException
     * @throws \Exception
     */
    public function testGetPrinterGroupsNullResponse(): void
    {
        $mockResponse = [
            'response' => json_encode([
                'results' => []
            ]),
            'headers' => [],
            'code' => 200
        ];

        $mockCurl = $this->mockCurl();
        $mockCurl->shouldReceive('write')->once()->andReturnSelf();
        $mockCurl->shouldReceive('getResponse')->once()->andReturn($mockResponse);
        $mockCurl->shouldReceive('close')->once()->andReturnSelf();

        $result = (new PrinterGroupWebService())
            ->setApiKey($this->getApiKey())
            ->getPrinterGroups();

        self::assertCount(0, $result);
    }
}
