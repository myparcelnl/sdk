<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Services\Web;

use MyParcelNL\Sdk\src\Factory\Account\CarrierConfigurationFactory;
use MyParcelNL\Sdk\src\Model\Account\CarrierConfiguration;
use MyParcelNL\Sdk\src\Support\Collection;

class CarrierConfigurationWebService extends AbstractWebService
{
    use HasCarrier;

    private const ENDPOINT_MULTIPLE = 'shops/:shopId/carrier_configurations';
    private const ENDPOINT_SINGLE   = 'shops/:shopId/carriers/:carrierId/carrier_configuration';

    /**
     * @param  int  $shopId
     * @param  int  $carrierId
     * @param  bool $fetchDropOffPoint
     *
     * @return null|\MyParcelNL\Sdk\src\Model\Account\CarrierConfiguration
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @throws \Exception
     */
    public function getCarrierConfiguration(
        int  $shopId,
        int  $carrierId,
        bool $fetchDropOffPoint = false
    ): ?CarrierConfiguration {
        $uri    = strtr(self::ENDPOINT_SINGLE, [':shopId' => $shopId, ':carrierId' => $carrierId]);
        $result = $this->doRequest($uri);

        if (! $result) {
            return null;
        }

        return CarrierConfigurationFactory::create($result[0], $fetchDropOffPoint, $this->getApiKey());
    }

    /**
     * @param  int  $shopId
     * @param  bool $fetchDropOffPoint
     *
     * @return \MyParcelNL\Sdk\src\Support\Collection|\MyParcelNL\Sdk\src\Model\Account\CarrierConfiguration[]
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    public function getCarrierConfigurations(
        int  $shopId,
        bool $fetchDropOffPoint = false
    ): Collection {
        $uri    = strtr(self::ENDPOINT_MULTIPLE, [':shopId' => $shopId]);
        $result = $this->doRequest($uri);

        if (! $result) {
            return new Collection();
        }

        return (new Collection($result))
            ->filter(function (array $array) {
                return $this->carrierIdExists($array['carrier_id']);
            })
            ->map(function (array $data) use ($fetchDropOffPoint) {
                return CarrierConfigurationFactory::create($data, $fetchDropOffPoint, $this->getApiKey());
            });
    }

    /**
     * @param  string $uri
     *
     * @return null|array
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    private function doRequest(string $uri): ?array
    {
        $request = $this->createRequest()
            ->sendRequest(
                'GET',
                $uri
            );

        return $request->getResult('data.carrier_configurations');
    }
}
