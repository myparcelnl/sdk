<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class OrderToCreateShipping implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * Additional shipping context of the order.
     *
     * @var string
     */
    public string $additionalContext;
    /**
     * The carrier to select to ship a package with.
     *
     * @var CarrierToCreate|string
     */
    public CarrierToCreate|string $carrier;
    /**
     * The unique identifier of the contract.
     *
     * @var string
     */
    public string $customContractId;
    /**
     * The official declaration of goods for customs clearance purposes during international shipment.
     *
     * @var CustomsDeclaration
     */
    public CustomsDeclaration $customsDeclaration;
    /**
     * The delivery details of the shipment.
     *
     * @var Delivery
     */
    public Delivery $delivery;
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
     * @var int
     */
    public int $numberOfLabels;
    /**
     * The complete set of shipment options.
     *
     * @var OrderToCreateShippingOptions
     */
    public OrderToCreateShippingOptions $options;
    /**
     * The type of the package.
     *
     * @var PackageType|string
     */
    public PackageType|string $packageType;
    /**
     * The pickup details.
     *
     * @var mixed
     */
    public $pickup;
    /**
     * The recipient of the shipment.
     *
     * @var OrderToCreateShippingRecipient
     */
    public OrderToCreateShippingRecipient $recipient;
    public function definedProperties(): array
    {
        return ['additionalContext' => 'additionalContext', 'carrier' => 'carrier', 'customContractId' => 'customContractId', 'customsDeclaration' => 'customsDeclaration', 'delivery' => 'delivery', 'direction' => 'direction', 'disableAutoDetectPickup' => 'disableAutoDetectPickup', 'numberOfLabels' => 'numberOfLabels', 'options' => 'options', 'packageType' => 'packageType', 'pickup' => 'pickup', 'recipient' => 'recipient'];
    }
}