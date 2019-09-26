<?php

namespace MyParcelNL\Sdk\src\Model\DeliveryOptions;

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
    private $insurance;

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
}
