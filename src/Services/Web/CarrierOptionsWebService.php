<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Web;

use MyParcelNL\Sdk\Model\Account\CarrierOptions;
use MyParcelNL\Sdk\Model\Carrier\CarrierFactory;
use MyParcelNL\Sdk\Support\Collection;

class CarrierOptionsWebService extends AbstractWebService
{
    use HasCarrier;

    public const ENDPOINT = 'carrier_management/shops/:shopId/carrier_options';

    /**
     * @param  int $shopId
     *
     * @return \MyParcelNL\Sdk\Support\Collection|\MyParcelNL\Sdk\Model\Account\CarrierOptions[]
     * @throws \MyParcelNL\Sdk\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\Exception\ApiException
     * @throws \MyParcelNL\Sdk\Exception\MissingFieldException
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
