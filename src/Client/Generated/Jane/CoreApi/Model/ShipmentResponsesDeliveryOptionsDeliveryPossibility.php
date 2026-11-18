<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

class ShipmentResponsesDeliveryOptionsDeliveryPossibility
{
    /**
     * @var ShipmentDefsDeliveryOptionsDeliveryNameV2|string|null
     */
    public ShipmentDefsDeliveryOptionsDeliveryNameV2|string|null $type;
    /**
     * Package Type.
     *
     * @var ShipmentResponsesDeliveryOptionsPackageTypeV2|string
     */
    public ShipmentResponsesDeliveryOptionsPackageTypeV2|string $packageType;
    /**
     * @var list<ShipmentDefsDeliveryOptionsDeliveryTimeFrame>
     */
    public array $deliveryTimeFrames;
    /**
     * @var list<ShipmentResponsesDeliveryOptionsDeliveryPossibilityShipmentOptionsItem>
     */
    public array $shipmentOptions;
    /**
     * Price amount in a specific currency
     *
     * @var CommonDefsPrice
     */
    public CommonDefsPrice $price;
    /**
     * @var string
     */
    public string $identifier;
    /**
     * @var string
     */
    public string $transporter;
    /**
     * @var string
     */
    public string $labelType;
}