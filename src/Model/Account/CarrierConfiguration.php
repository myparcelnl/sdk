<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Account;

use MyParcelNL\Sdk\src\Model\BaseModel;
use MyParcelNL\Sdk\src\Model\Carrier\AbstractCarrier;
use MyParcelNL\Sdk\src\Model\Carrier\CarrierFactory;
use MyParcelNL\Sdk\src\Model\Consignment\DropOffPoint;
use MyParcelNL\Sdk\src\Services\Web\CanGetDropOffPoint;

class CarrierConfiguration extends BaseModel
{
    /**
     * @var \MyParcelNL\Sdk\src\Model\Carrier\AbstractCarrier
     */
    private $carrier;

    /**
     * @var null|string
     */
    private $defaultDropOffPointIdentifier;

    /**
     * @var \MyParcelNL\Sdk\src\Model\Consignment\DropOffPoint
     */
    private $defaultDropOffPoint;

    /**
     * CarrierConfiguration constructor.
     *
     * @param array                                               $configurations
     * @param \MyParcelNL\Sdk\src\Services\Web\CanGetDropOffPoint $dropOffPointWebService
     *
     * @throws \Exception
     */
    public function __construct(array $configurations, CanGetDropOffPoint $dropOffPointWebService)
    {
        $this->carrier                       = CarrierFactory::createFromId($configurations['carrier_id']);
        $this->defaultDropOffPointIdentifier = $configurations['configuration']['default_drop_off_point'] ?? null;

        $this->fetchDefaultDropOffPoint($dropOffPointWebService);
    }

    /**
     * @return \MyParcelNL\Sdk\src\Model\Carrier\AbstractCarrier
     */
    public function getCarrier(): AbstractCarrier
    {
        return $this->carrier;
    }

    /**
     * @return string|null
     */
    public function getDefaultDropOffPointIdentifier(): ?string
    {
        return $this->defaultDropOffPointIdentifier;
    }

    /**
     * @param \MyParcelNL\Sdk\src\Services\Web\CanGetDropOffPoint $canGetDropOffPoint
     */
    private function fetchDefaultDropOffPoint(CanGetDropOffPoint $canGetDropOffPoint): void
    {
        if (isset($this->defaultDropOffPoint)) {
            return;
        }

        if (null !== $this->getDefaultDropOffPointIdentifier()) {
            $dropOffPoint = $canGetDropOffPoint
                ->getDropOffPoint($this->getDefaultDropOffPointIdentifier());

            if ($dropOffPoint) {
                $this->defaultDropOffPoint = $dropOffPoint;
            }
        }
    }

    /**
     * @return \MyParcelNL\Sdk\src\Model\Consignment\DropOffPoint
     */
    public function getDefaultDropOffPoint(): ?DropOffPoint
    {
        return $this->defaultDropOffPoint ?? null;
    }
}
