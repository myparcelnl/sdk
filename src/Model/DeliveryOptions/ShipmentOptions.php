<?php

namespace MyParcelNL\Sdk\src\Model\DeliveryOptions;

class ShipmentOptions
{
    /**
     * @var bool
     */
    private $signature;

    /**
     * @var bool
     */
    private $only_recipient;

    public function __construct(array $shipmentOptions)
    {
        $this->input = $shipmentOptions;

        $this->signature      = $this->getOption("signature");
        $this->only_recipient = $this->getOption("only_recipient");
    }

    /**
     * @return bool
     */
    public function hasSignature(): bool
    {
        return $this->signature;
    }

    /**
     * @return bool
     */
    public function hasOnlyRecipient(): bool
    {
        return $this->only_recipient;
    }

    /**
     * @param string $string
     *
     * @return bool
     */
    private function getOption(string $string): bool
    {
        if (array_key_exists($string, $this->input)) {
            return (bool) $this->input[$string];
        }

        return false;
    }
}
