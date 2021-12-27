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

    public function setSignature(?bool $signature): void
    {
        $this->signature = $signature;
    }

    public function setInsurance(?int $insurance): void
    {
        $this->insurance = $insurance;
    }

    public function setAgeCheck(?bool $ageCheck): void
    {
        $this->age_check = $ageCheck;
    }

    public function setOnlyRecipient(?bool $onlyRecipient): void
    {
        $this->only_recipient = $onlyRecipient;
    }

    public function setReturn(?bool $return): void
    {
        $this->return = $return;
    }

    public function setLargeFormat(?bool $largeFormat): void
    {
        $this->large_format = $largeFormat;
    }

    public function setLabelDescription(?string $labelDescription): void
    {
        $this->label_description = $labelDescription;
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
