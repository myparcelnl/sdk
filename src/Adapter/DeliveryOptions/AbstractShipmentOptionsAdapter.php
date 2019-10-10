<?php declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Adapter\DeliveryOptions;

abstract class AbstractShipmentOptionsAdapter
{
    /**
     * @var array
     */
    protected $input;

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
     * Sets a value to a boolean if set, otherwise it's null.
     *
     * @param string $string
     *
     * @return bool|null
     */
    protected function getOption(string $string): ?bool
    {
        if (array_key_exists($string, $this->input)) {
            return (bool) $this->input[$string];
        }

        return null;
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
