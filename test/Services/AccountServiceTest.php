<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services;

use MyParcelNL\Sdk\src\Services\Web\AccountWebService;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class AccountServiceTest extends TestCase
{
    /**
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @throws \Exception
     */
    public function testGetAccount(): void
    {
        $result = (new AccountWebService())
            ->setApiKey($this->getApiKey())
            ->getAccount();

        self::assertArrayHasKey('id', $result->toArray());
        self::assertArrayHasKey('platform_id', $result->toArray());
        self::assertArrayHasKey('shops', $result->toArray());
    }
}
