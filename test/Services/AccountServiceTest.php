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
        $result = (new AccountWebService())
            ->setApiKey($this->getApiKey())
            ->getAccount();

        self::assertArrayHasKey('id', $result->toArray());
        self::assertArrayHasKey('platform_id', $result->toArray());
        self::assertArrayHasKey('shops', $result->toArray());
    }
}
