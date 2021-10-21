<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Account;

use MyParcelNL\Sdk\src\Model\BaseModel;
use MyParcelNL\Sdk\src\Model\Carrier\AbstractCarrier;
use MyParcelNL\Sdk\src\Model\Carrier\CarrierFactory;

class CarrierOptions extends BaseModel
{
    /**
     * @var \MyParcelNL\Sdk\src\Model\Carrier\AbstractCarrier
     */
    private $carrier;

    /**
     * @var bool
     */
    private $enabled;

    /**
     * @var string
     */
    private $label;

    /**
     * @var bool
     */
    private $optional;

    /**
     * @param  array $options
     *
     * @throws \Exception
     */
    public function __construct(array $options)
    {
        $this->enabled  = (bool) $options['enabled'];
        $this->optional = (bool) $options['optional'];
        $this->carrier  = CarrierFactory::create($options['carrier']['id']);
        $this->label    = $options['label'] ?? $this->carrier->getHuman();
    }

    /**
     * @return \MyParcelNL\Sdk\src\Model\Carrier\AbstractCarrier
     */
    public function getCarrier(): AbstractCarrier
    {
        return $this->carrier;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @return bool
     */
    public function isOptional(): bool
    {
        return $this->optional;
    }
}
