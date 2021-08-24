<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Account;

use MyParcelNL\Sdk\src\Model\BaseModel;
use MyParcelNL\Sdk\src\Model\Carrier\AbstractCarrier;
use MyParcelNL\Sdk\src\Model\Carrier\CarrierFactory;

class CarrierConfiguration extends BaseModel
{
    /**
     * @var \MyParcelNL\Sdk\src\Model\Carrier\AbstractCarrier
     */
    private $carrier;

    /**
     * @var null|string
     */
    private $defaultDropOffPoint;

    /**
     * @param  array $configurations
     *
     * @throws \Exception
     */
    public function __construct(array $configurations)
    {
        $this->carrier             = CarrierFactory::createFromId($configurations['carrier_id']);
        $this->defaultDropOffPoint = $configurations['configuration']['default_drop_off_point'] ?? null;
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
    public function getDefaultDropOffPoint(): ?string
    {
        return $this->defaultDropOffPoint;
    }
}
