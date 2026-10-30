<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class Shipment implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * The unique identifier of the shipment.
     *
     * @var string
     */
    public string $id;
    /**
     * The carrier of the package.
     *
     * @var Carrier|string
     */
    public Carrier|string $carrier;
    /**
     * The date when the shipment was created.
     *
     * @var mixed
     */
    public $createdAt;
    /**
     * The direction of the shipment. OUTBOUND is from sender to recipient, INBOUND is from recipient to sender.
     *
     * @var ShipmentDirection|string
     */
    public ShipmentDirection|string $direction;
    /**
     * Whether the shipment is hidden.
     *
     * @var bool
     */
    public bool $hidden;
    /**
     * The type of the package.
     *
     * @var PackageType|string
     */
    public PackageType|string $packageType;
    /**
     * The recipient of the shipment.
     *
     * @var ShipmentRecipient
     */
    public ShipmentRecipient $recipient;
    /**
     * The unique identifier of the contract.
     *
     * @var string
     */
    public string $customContractId;
    /**
     * The official declaration of goods for customs clearance purposes during international shipment.
     *
     * @var CustomsDeclarationResponse
     */
    public CustomsDeclarationResponse $customsDeclaration;
    /**
     * The delivery details of the shipment.
     *
     * @var Delivery
     */
    public Delivery $delivery;
    /**
     * The drop-off details.
     *
     * @var DropOff
     */
    public DropOff $dropOff;
    /**
     * The label of the package.
     *
     * @var LabelWithBarcode|LabelWithoutBarcode
     */
    public $label;
    /**
     * Details if this package is part of a multi-package (multicollo) shipment. A multicollo shipment is a group of packages belonging to the same shipment that are consolidated for combined labeling and potential cost reduction. This object will only be present if the package is part of such a group.
     *
     * @var ShipmentMulticollo
     */
    public ShipmentMulticollo $multicollo;
    /**
     * Options applicable to shipments.
     *
     * @var ShipmentOptions
     */
    public ShipmentOptions $options;
    /**
     * @var mixed
     */
    public $pickup;
    /**
     * A monetary value represented by split integral and fractional parts to avoid floating point errors.
     *
     * @var Money
     */
    public Money $price;
    /**
     * The sender of the shipment.
     *
     * @var ShipmentSender
     */
    public ShipmentSender $sender;
    public function definedProperties(): array
    {
        return ['id' => 'id', 'carrier' => 'carrier', 'createdAt' => 'createdAt', 'direction' => 'direction', 'hidden' => 'hidden', 'packageType' => 'packageType', 'recipient' => 'recipient', 'customContractId' => 'customContractId', 'customsDeclaration' => 'customsDeclaration', 'delivery' => 'delivery', 'dropOff' => 'dropOff', 'label' => 'label', 'multicollo' => 'multicollo', 'options' => 'options', 'pickup' => 'pickup', 'price' => 'price', 'sender' => 'sender'];
    }
}