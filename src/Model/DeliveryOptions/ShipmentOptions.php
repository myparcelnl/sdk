<?php

namespace MyParcelNL\Sdk\src\Model\DeliveryOptions;

/**
 * Class ShipmentOptions
 *
 * @package MyParcelNL\Sdk\src\Model\DeliveryOptions
 */
class ShipmentOptions
{
    /**
     * @var array
     */
    private $input;

    /**
     * @var bool
     */
    private $signature;

    /**
     * @var bool
     */
    private $only_recipient;

    /**
     * @var bool|null
     */
    private $insurance;

    /**
     * ShipmentOptions constructor.
     *
     * @param array $shipmentOptions
     */
    public function __construct(array $shipmentOptions)
    {
        $this->input          = $shipmentOptions;
        $this->signature      = $this->getOption("signature");
        $this->only_recipient = $this->getOption("only_recipient");
        $this->insurance      = $this->getOption("insurance");
    }

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
     * @return bool|null
     */
    public function hasInsurance()
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
    private function getOption(string $string)
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
            'insurance'      => $this->hasInsurance(),
            'only_recipient' => $this->hasOnlyRecipient(),
        ];
    }
}
