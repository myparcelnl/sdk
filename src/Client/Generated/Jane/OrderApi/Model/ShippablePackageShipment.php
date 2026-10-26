<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class ShippablePackageShipment implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * The carrier to select to ship a package with.
     *
     * @var CarrierToCreate|string
     */
    public CarrierToCreate|string $carrier;
    /**
     * The direction of the shipment. OUTBOUND is from sender to recipient, INBOUND is from recipient to sender.
     *
     * @var ShipmentDirection|string
     */
    public ShipmentDirection|string $direction;
    /**
     * When set to true, this field disables the automatic detection and change of a recipient's address to a pickup location, particularly when the recipient's address is very close to or within the same building as a pickup point.
     *
     * @var bool
     */
    public bool $disableAutoDetectPickup;
    /**
     * The type of the package.
     *
     * @var PackageType|string
     */
    public PackageType|string $packageType;
    /**
     * The recipient of the shipment.
     *
     * @var ShippablePackageShipmentRecipient
     */
    public ShippablePackageShipmentRecipient $recipient;
    /**
     * The unique identifier of the contract.
     *
     * @var string
     */
    public string $customContractId;
    /**
     * The delivery details of the shipment.
     *
     * @var Delivery
     */
    public Delivery $delivery;
    /**
     * Options applicable to shipments.
     *
     * @var ShipmentOptionsCommon
     */
    public ShipmentOptionsCommon $options;
    /**
     * @var mixed
     */
    public $pickup;
    /**
     * The sender of the shipment.
     *
     * @var ShippablePackageShipmentSender
     */
    public ShippablePackageShipmentSender $sender;
    /**
     * When set to true, the recipient address is saved to the user's address book.
     *
     * @var bool
     */
    public bool $saveRecipientAddress;
    public function definedProperties(): array
    {
        return ['carrier' => 'carrier', 'direction' => 'direction', 'disableAutoDetectPickup' => 'disableAutoDetectPickup', 'packageType' => 'packageType', 'recipient' => 'recipient', 'customContractId' => 'customContractId', 'delivery' => 'delivery', 'options' => 'options', 'pickup' => 'pickup', 'sender' => 'sender', 'saveRecipientAddress' => 'saveRecipientAddress'];
    }
}