<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Web;

use BadMethodCallException;
use MyParcelNL\Sdk\Helper\ValidatePostalCode;
use MyParcelNL\Sdk\Model\Carrier\CarrierFactory;
use MyParcelNL\Sdk\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\Model\Consignment\DropOffPoint;
use MyParcelNL\Sdk\Support\Collection;

class DropOffPointWebService extends AbstractWebService implements CanGetDropOffPoint
{
    /**
     * @var \MyParcelNL\Sdk\Model\Carrier\AbstractCarrier
     */
    private $carrier;

    /**
     * @param  string|int|\MyParcelNL\Sdk\Model\Carrier\AbstractCarrier $carrier
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
     * @return null|\MyParcelNL\Sdk\Model\Consignment\DropOffPoint
     * @throws \MyParcelNL\Sdk\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\Exception\ApiException
     * @throws \MyParcelNL\Sdk\Exception\MissingFieldException
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
     * @return \MyParcelNL\Sdk\Support\Collection|\MyParcelNL\Sdk\Model\Consignment\DropOffPoint[]
     * @throws \MyParcelNL\Sdk\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\Exception\ApiException
     * @throws \MyParcelNL\Sdk\Exception\MissingFieldException
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
