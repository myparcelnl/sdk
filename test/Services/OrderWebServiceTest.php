<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services;

use MyParcelNL\Sdk\src\Services\Web\AccountWebService;
use MyParcelNL\Sdk\src\Services\Web\OrderWebService;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class OrderWebServiceTest extends TestCase
{
    /**
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @throws \Exception
     */
    public function testGetOrder(): void
    {
        $result = (new OrderWebService())
            ->setApiKey($this->getApiKey())
            ->getOrder($this->getUuid());

        self::assertIsArray($result);
        self::assertNotEmpty($result);
        self::assertArrayHasKey('uuid', $result);
        self::assertArrayHasKey('order_date', $result);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testGetOrders(): void
    {
        $result = (new OrderWebService())
            ->setApiKey($this->getApiKey())
            ->getOrders();

        self::assertIsArray($result);
        self::assertNotEmpty($result);
        self::assertArrayHasKey('uuid', $result[0]);
        self::assertArrayHasKey('order_date', $result[0]);
    }

    /**
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @throws \Exception
     */
    private function getUuid(): string
    {
        return (new OrderWebService())
            ->setApiKey($this->getApiKey())
            ->getOrders()[0]['uuid'];
    }
}
