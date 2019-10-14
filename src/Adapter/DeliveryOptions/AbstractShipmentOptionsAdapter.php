<?php declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Adapter\DeliveryOptions;

abstract class AbstractShipmentOptionsAdapter
{
    /**
     * @var bool
     */
    protected $signature;

    /**
     * @var bool
     */
    protected $only_recipient;

    /**
     * @var int|null
     */
    protected $insurance;

    /**
     * @return bool|null
     */
    public function hasSignature()
    {
        return $this->signature;
    }

    /**
     * @return bool|null
     */
    public function hasOnlyRecipient()
    {
        return $this->only_recipient;
    }

    /**
     * @return int|null
     */
    public function getInsurance()
    {
        return $this->insurance;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'signature'      => $this->hasSignature(),
            'insurance'      => $this->getInsurance(),
            'only_recipient' => $this->hasOnlyRecipient(),
        ];
    }
}
