<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Services\Web;

use MyParcelNL\Sdk\src\Model\Account\CarrierOptions;
use MyParcelNL\Sdk\src\Model\Carrier\CarrierFactory;
use MyParcelNL\Sdk\src\Support\Collection;

class CarrierOptionsWebService extends AbstractWebService
{
    use HasCarrier;

    public const ENDPOINT = 'carrier_management/shops/:shopId/carrier_options';

    /**
     * @param  int $shopId
     *
     * @return \MyParcelNL\Sdk\src\Support\Collection|\MyParcelNL\Sdk\src\Model\Account\CarrierOptions[]
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    public function getCarrierOptions(int $shopId): Collection
    {
        $uri     = strtr(self::ENDPOINT, [':shopId' => $shopId]);
        $request = $this->createRequest()
            ->sendRequest(
                'GET',
                $uri
            );

        $result = $request->getResult('data.carrier_options');

        foreach ($result as $index => $carrierOptions) {
            if (! CarrierFactory::canCreateFromId($carrierOptions['carrier_id'])) {
                unset($result[$index]);
            }
        }

        return (new Collection($result))
            ->filter(function (array $array) {
                return $this->carrierIdExists($array['carrier_id']);
            })
            ->mapInto(CarrierOptions::class);
    }
}
