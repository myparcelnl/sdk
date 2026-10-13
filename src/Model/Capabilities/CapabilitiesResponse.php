<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Capabilities;

/**
 * Immutable value object representing carrier capabilities.
 * Holds the combined information from the capabilities API response.
 *
 * NOTE: 'physical_properties' and rich option details are intentionally omitted for now.
 *       We only expose option keys (presence). Physical limits will be added in a follow-up.
 */
final class CapabilitiesResponse
{
    /** @var string[] */
    private $packageTypes;

    /** @var string[] */
    private $deliveryTypes;

    /** @var string[] */
    private $shipmentOptions;

    /** @var null|string */
    private $carrier;

    /** @var string[] */
    private $transactionTypes;

    /** @var null|int */
    private $colloMax;

    /**
     * @param string[]     $packageTypes
     * @param string[]     $deliveryTypes
     * @param string[]     $shipmentOptions
     * @param null|string  $carrier
     * @param string[]     $transactionTypes
     * @param null|int     $colloMax
     */
    public function __construct(
        array $packageTypes,
        array $deliveryTypes,
        array $shipmentOptions,
        ?string $carrier,
        array $transactionTypes,
        ?int $colloMax
    ) {
        $this->packageTypes     = array_values($packageTypes);
        $this->deliveryTypes    = array_values($deliveryTypes);
        $this->shipmentOptions  = array_values($shipmentOptions);
        $this->carrier          = $carrier;
        $this->transactionTypes = array_values($transactionTypes);
        $this->colloMax         = $colloMax;
    }

    /** @return string[] */
    public function getPackageTypes()
    {
        return $this->packageTypes;
    }

    /** @return string[] */
    public function getDeliveryTypes()
    {
        return $this->deliveryTypes;
    }

    /** @return string[] */
    public function getShipmentOptions()
    {
        return $this->shipmentOptions;
    }

    /** @return null|string */
    public function getCarrier()
    {
        return $this->carrier;
    }

    /** @return string[] */
    public function getTransactionTypes()
    {
        return $this->transactionTypes;
    }

    /** @return null|int */
    public function getColloMax()
    {
        return $this->colloMax;
    }
}
