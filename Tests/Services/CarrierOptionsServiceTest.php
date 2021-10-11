<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Services;

use MyParcelNL\Sdk\src\Model\Account\Shop;
use MyParcelNL\Sdk\src\Services\Web\AccountWebService;
use MyParcelNL\Sdk\src\Services\Web\CarrierOptionsWebService;
use PHPUnit\Framework\TestCase;

class CarrierOptionsServiceTest extends TestCase
{
    /**
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    public function testGetAccounts(): void
    {
        $accountService        = (new AccountWebService())->setApiKey(getenv('API_KEY'));
        $carrierOptionsService = (new CarrierOptionsWebService())->setApiKey(getenv('API_KEY'));

        $accountService->getAccount()
            ->getShops()
            ->first(static function (Shop $shop) use ($carrierOptionsService) {
                $result = $carrierOptionsService->getCarrierOptions($shop->getId());

                self::assertEquals('MyParcelNL\Sdk\src\Model\Account\CarrierOptions', get_class($result->first()));
            });
    }
}
