<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Services\Web;

use MyParcelNL\Sdk\src\Model\Account\CarrierConfiguration;

class CarrierConfigurationWebService extends AbstractWebService
{
    public const ENDPOINT = "shops/:shopId/carriers/:carrierId/carrier_configuration";

    /**
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @throws \Exception
     */
    public function getCarrierConfigurations(int $shopId, int $carrierId): ?CarrierConfiguration
    {
        $uri     = strtr(self::ENDPOINT, [':shopId' => $shopId, ':carrierId' => $carrierId]);
        $request = $this->createRequest()
            ->sendRequest(
                'GET',
                $uri
            );

        $result = $request->getResult('data.carrier_configurations');

        if (! $result) {
            return null;
        }

        $dropOffPointWebService = (new RedJePakketjeDropOffPointWebService())->setApiKey($this->getApiKey());

        return (new CarrierConfiguration($result[0], $dropOffPointWebService));
    }
}
