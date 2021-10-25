<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Services;

use MyParcelNL\Sdk\src\Model\Carrier\CarrierRedJePakketje;
use MyParcelNL\Sdk\src\Services\Web\AccountWebService;
use MyParcelNL\Sdk\src\Services\Web\CarrierConfigurationWebService;
use PHPUnit\Framework\TestCase;

class CarrierConfigurationServiceTest extends TestCase
{
    /**
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @throws \Exception
     */
    public function testGetConfiguration(): void
    {
        $shop = $this->getShop();

        $carrierConfigurationService = (new CarrierConfigurationWebService())->setApiKey(getenv('API_KEY'));

        $result = $carrierConfigurationService->getCarrierConfiguration($shop->getId(), CarrierRedJePakketje::ID);

        self::assertNotEmpty($result->getDefaultDropOffPointIdentifier());
    }

    /**
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    public function testGetConfigurations(): void
    {
        $shop = $this->getShop();

        $carrierConfigurationService = (new CarrierConfigurationWebService())->setApiKey(getenv('API_KEY'));

        $result = $carrierConfigurationService->getCarrierConfigurations($shop->getId());

        self::assertNotEmpty(
            $result
                ->first()
                ->getDefaultDropOffPointIdentifier()
        );
    }

    /**
     * @return mixed
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    private function getShop()
    {
        $accountService = (new AccountWebService())->setApiKey(getenv('API_KEY'));
        $shop           = $accountService->getAccount()
            ->getShops()
            ->first();
        return $shop;
    }
}
