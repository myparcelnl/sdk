<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services;

use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use MyParcelNL\Sdk\src\Model\Account\Shop;
use MyParcelNL\Sdk\src\Model\Carrier\CarrierInstabox;
use MyParcelNL\Sdk\src\Services\Web\AccountWebService;
use MyParcelNL\Sdk\src\Services\Web\CarrierConfigurationWebService;

class CarrierConfigurationServiceTest extends TestCase
{
    /**
     * @before
     * @return void
     */
    public function before(): void
    {
        self::markTestBroken();
    }

    /**
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @throws \Exception
     */
    public function testGetConfiguration(): void
    {
        $shop                        = $this->getShop();
        $carrierConfigurationService = (new CarrierConfigurationWebService())->setApiKey($this->getApiKey());
        $result                      = $carrierConfigurationService->getCarrierConfiguration(
            $shop->getId(),
            CarrierInstabox::ID
        );

        self::assertNotEmpty($result->getDefaultDropOffPointIdentifier());
    }

    /**
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @throws \Exception
     */
    public function testGetConfigurations(): void
    {
        $shop                        = $this->getShop();
        $carrierConfigurationService = (new CarrierConfigurationWebService())->setApiKey($this->getApiKey());
        $result                      = $carrierConfigurationService->getCarrierConfigurations($shop->getId());

        self::assertNotEmpty(
            $result
                ->first()
                ->getDefaultDropOffPointIdentifier()
        );
    }

    /**
     * @return \MyParcelNL\Sdk\src\Model\Account\Shop
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @throws \Exception
     */
    private function getShop(): Shop
    {
        $accountService = (new AccountWebService())->setApiKey($this->getApiKey());
        return $accountService->getAccount()
            ->getShops()
            ->first();
    }
}
