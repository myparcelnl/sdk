<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services;

use MyParcelNL\Sdk\Model\Account\Shop;
use MyParcelNL\Sdk\Services\Web\AccountWebService;
use MyParcelNL\Sdk\Services\Web\CarrierConfigurationWebService;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

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
     * @throws \MyParcelNL\Sdk\Exception\ApiException
     * @throws \MyParcelNL\Sdk\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\Exception\MissingFieldException
     * @throws \Exception
     */
    public function testGetConfiguration(): void
    {
        $this->markTestSkipped('This does not work since Instabox was removed from the API.');

        $shop                        = $this->getShop();
        $carrierConfigurationService = (new CarrierConfigurationWebService())->setApiKey($this->getApiKey());
        $result                      = $carrierConfigurationService->getCarrierConfiguration(
            $shop->getId(),
            CarrierInstabox::ID
        );

        self::assertNotEmpty($result->getDefaultDropOffPointIdentifier());
    }

    /**
     * @throws \MyParcelNL\Sdk\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\Exception\ApiException
     * @throws \MyParcelNL\Sdk\Exception\MissingFieldException
     * @throws \Exception
     */
    public function testGetConfigurations(): void
    {
        $this->markTestSkipped('This does not work since Instabox was removed from the API.');

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
     * @return \MyParcelNL\Sdk\Model\Account\Shop
     * @throws \MyParcelNL\Sdk\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\Exception\ApiException
     * @throws \MyParcelNL\Sdk\Exception\MissingFieldException
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
