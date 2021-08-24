<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Services\Web;

use BadMethodCallException;
use MyParcelNL\Sdk\src\Helper\ValidatePostalCode;
use MyParcelNL\Sdk\src\Model\Carrier\CarrierRedJePakketje;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\src\Model\Consignment\DropOffPoint;

class RedJePakketjeDropOffPointWebService extends AbstractWebService
{
    /**
     * @param  string $postalCode
     *
     * @return array
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    public function getDropOffPoints(string $postalCode): array
    {
        if (! ValidatePostalCode::validate($postalCode, AbstractConsignment::CC_NL)) {
            throw new BadMethodCallException('Invalid postal code');
        }

        $request = $this->createRequest()
            ->setQuery([
                'postal_code' => $postalCode,
                'carrier_id'  => CarrierRedJePakketje::getId(),
            ])
            ->sendRequest('GET', 'drop_off_points');

        return $request->getResult('data.drop_off_points');
    }

    /**
     * @param string $external_identifier which is the location_code belonging tot he drop off point
     *
     * @return array (indexed) with zero or one entry
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    public function getDropOffPoint(string $external_identifier): array
    {
        $request = $this->createRequest()
            ->setQuery([
                'external_identifier' => $external_identifier,
                'carrier_id'  => CarrierRedJePakketje::getId(),
            ])
            ->sendRequest('GET', 'drop_off_points');

        return $request->getResult('data.drop_off_points');
    }
}
