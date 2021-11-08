<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Services\Web;

use BadMethodCallException;
use MyParcelNL\Sdk\src\Helper\ValidatePostalCode;
use MyParcelNL\Sdk\src\Model\Carrier\CarrierFactory;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\src\Model\Consignment\DropOffPoint;
use MyParcelNL\Sdk\src\Support\Collection;

class DropOffPointWebService extends AbstractWebService implements CanGetDropOffPoint
{
    /**
     * @var \MyParcelNL\Sdk\src\Model\Carrier\AbstractCarrier
     */
    private $carrier;

    /**
     * @param  string|int|\MyParcelNL\Sdk\src\Model\Carrier\AbstractCarrier $carrier
     *
     * @throws \Exception
     */
    public function __construct($carrier)
    {
        $this->carrier = CarrierFactory::create($carrier);
    }

    /**
     * @param  string $externalIdentifier
     *
     * @return null|\MyParcelNL\Sdk\src\Model\Consignment\DropOffPoint
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @throws \Exception
     */
    public function getDropOffPoint(string $externalIdentifier): ?DropOffPoint
    {
        $request = $this->createRequest()
            ->setQuery([
                'external_identifier' => $externalIdentifier,
                'carrier_id'          => $this->carrier->getId(),
            ])
            ->sendRequest('GET', 'drop_off_points');

        $result = $request->getResult('data.drop_off_points');

        if ($result && is_array($result)) {
            return new DropOffPoint($result[0]);
        }

        return null;
    }

    /**
     * @param  string $postalCode
     *
     * @return \MyParcelNL\Sdk\src\Support\Collection|\MyParcelNL\Sdk\src\Model\Consignment\DropOffPoint[]
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    public function getDropOffPoints(string $postalCode): Collection
    {
        if (! ValidatePostalCode::validate($postalCode, AbstractConsignment::CC_NL)) {
            throw new BadMethodCallException('Invalid postal code');
        }

        $request = $this->createRequest()
            ->setQuery([
                'postal_code' => $postalCode,
                'carrier_id'  => $this->carrier->getId(),
            ])
            ->sendRequest('GET', 'drop_off_points');

        $result = $request->getResult('data.drop_off_points');

        return (new Collection($result ?? []))->mapInto(DropOffPoint::class);
    }
}
