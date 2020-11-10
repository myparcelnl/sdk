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
     * @var bool|null
     */
    protected $large_format;

    /**
     * @var bool|null
     */
    protected $return;

    /**
     * @var int|null
     */
    protected $insurance;

    /**
     * @var string|null
     */
    protected $label_description;

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
     * @return bool|null
     */
    public function hasLargeFormat(): ?bool
    {
        return $this->large_format;
    }

    /**
     * Return the package if the recipient is not home
     *
     * @return bool|null
     */
    public function isReturn(): ?bool
    {
        return $this->return;
    }

    /**
     * @return int|null
     */
    public function getInsurance(): ?int
    {
        return $this->insurance;
    }

    /**
     * @return string|null
     */
    public function getLabelDescription(): ?string
    {
        return $this->label_description;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'signature'         => $this->hasSignature(),
            'insurance'         => $this->getInsurance(),
            'age_check'         => $this->hasAgeCheck(),
            'only_recipient'    => $this->hasOnlyRecipient(),
            'return'            => $this->isReturn(),
            'large_format'      => $this->hasLargeFormat(),
            'label_description' => $this->getLabelDescription(),
        ];
    }
}
