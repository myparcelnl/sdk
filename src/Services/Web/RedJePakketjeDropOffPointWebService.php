<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Services\Web;

use BadMethodCallException;
use MyParcelNL\Sdk\src\Helper\ValidatePostalCode;
use MyParcelNL\Sdk\src\Model\Carrier\CarrierRedJePakketje;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\src\Model\Consignment\DropOffPoint;

class RedJePakketjeDropOffPointWebService extends AbstractWebService implements CanGetDropOffPoint
{
    /**
     * @param  string $externalIdentifier which is the location_code belonging tot the drop-off point
     *
     * @return \MyParcelNL\Sdk\src\Model\Consignment\DropOffPoint|null
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    public function getDropOffPoint(string $externalIdentifier): ?DropOffPoint
    {
        $request = $this->createRequest()
            ->setQuery([
                'external_identifier' => $externalIdentifier,
                'carrier_id'          => (new CarrierRedJePakketje())->getId(),
            ])
            ->sendRequest('GET', 'drop_off_points');

        $result = $request->getResult('data.drop_off_points');

        if ($result && is_array($result)) {
            return (new DropOffPoint($result[0]));
        }

        return null;
    }

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
                'carrier_id'  => (new CarrierRedJePakketje())->getId(),
            ])
            ->sendRequest('GET', 'drop_off_points');

        return $request->getResult('data.drop_off_points');
    }
}
