<?php declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Adapter\DeliveryOptions;

abstract class AbstractShipmentOptionsAdapter
{
    /**
     * @var bool|null
     */
    protected $signature;

    /**
     * @var bool|null
     */
    protected $only_recipient;

    /**
     * @var bool|null
     */
    protected $age_check;

    /**
     * @var int|null
     */
    protected $insurance;

    /**
     * @return bool|null
     */
    public function hasSignature(): ?bool
    {
        return $this->signature;
    }

    /**
     * @return bool|null
     */
    public function hasOnlyRecipient(): ?bool
    {
        return $this->only_recipient;
    }

    /**
     * @return bool|null
     */
    public function hasAgeCheck(): ?bool
    {
        return $this->age_check;
    }

    /**
     * @return int|null
     */
    public function getInsurance(): ?int
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
            'only_recipient' => $this->hasOnlyRecipient(),
            'age_check'      => $this->hasAgeCheck(),
            'insurance'      => $this->getInsurance(),
        ];
    }
}
