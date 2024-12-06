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
