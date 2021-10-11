<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Services;

use MyParcelNL\Sdk\src\Services\Web\AccountWebService;
use PHPUnit\Framework\TestCase;

class AccountServiceTest extends TestCase
{
    /**
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    public function testGetAccount(): void
    {
        $result = (new AccountWebService())
            ->setApiKey(getenv('API_KEY'))
            ->getAccount();

        self::assertArrayHasKey('id', $result->toArray());
        self::assertArrayHasKey('platform_id', $result->toArray());
        self::assertArrayHasKey('shops', $result->toArray());
    }
}
