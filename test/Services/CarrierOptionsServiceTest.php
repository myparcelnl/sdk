<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services;

use MyParcelNL\Sdk\src\Model\Account\CarrierOptions;
use MyParcelNL\Sdk\src\Model\Account\Shop;
use MyParcelNL\Sdk\src\Services\Web\AccountWebService;
use MyParcelNL\Sdk\src\Services\Web\CarrierOptionsWebService;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class CarrierOptionsServiceTest extends TestCase
{
    /**
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @throws \Exception
     * @throws \Exception
     */
    public function testGetAccounts(): void
    {
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
