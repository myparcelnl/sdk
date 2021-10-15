<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Services;

use MyParcelNL\Sdk\src\Model\Account\Shop;
use MyParcelNL\Sdk\src\Services\Web\AccountWebService;
use MyParcelNL\Sdk\src\Services\Web\CarrierConfigurationWebService;
use MyParcelNL\Sdk\src\Services\Web\CarrierOptionsWebService;
use PHPUnit\Framework\TestCase;

class CarrierConfigurationServiceTest extends TestCase
{
    /**
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    public function testGetConfiguration(): void
    {
        $accountService        = (new AccountWebService())->setApiKey(getenv('API_KEY'));
        $carrierOptionsService = (new CarrierOptionsWebService())->setApiKey(getenv('API_KEY'));

        $accountService->getAccount()
            ->getShops()
            ->first(static function (Shop $shop) use ($carrierOptionsService) {
                $carriers                    = $carrierOptionsService->getCarrierOptions($shop->getId());
                $carrierConfigurationService = (new CarrierConfigurationWebService())->setApiKey(getenv('API_KEY'));

                foreach ($carriers as $carrierOptions) {
                    $carrierId = $carrierOptions->getCarrierId();
                    if (5 !== $carrierId) {
                        continue;
                    }
                    $result = $carrierConfigurationService->getCarrierConfigurations($shop->getId(), $carrierId);
                    break;
                }

                if (! isset($result)) {
                    throw new \Exception('carrier with carrier id 5 must be present to assert');
                }

                self::assertNotEmpty($result->getDefaultDropOffPointExternalIdentifier());
            });
    }
}
