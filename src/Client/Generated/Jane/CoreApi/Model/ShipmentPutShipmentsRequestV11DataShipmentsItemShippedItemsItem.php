<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class ShipmentPutShipmentsRequestV11DataShipmentsItemShippedItemsItem implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var string
     */
    public string $orderLineIdentifier;
    /**
     * @var int
     */
    public int $quantity;
    public function definedProperties(): array
    {
        return ['orderLineIdentifier' => 'order_line_identifier', 'quantity' => 'quantity'];
    }
}