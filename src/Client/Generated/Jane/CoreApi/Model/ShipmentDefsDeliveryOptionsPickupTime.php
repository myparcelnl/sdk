<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class ShipmentDefsDeliveryOptionsPickupTime implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var string|null
     */
    public ?string $start;
    /**
     * @var RefTypesDeliveryType|int
     */
    public RefTypesDeliveryType|int $type;
    /**
     * Price amount in a specific currency
     *
     * @var CommonDefsPrice
     */
    public CommonDefsPrice $price;
    public function definedProperties(): array
    {
        return ['start' => 'start', 'type' => 'type', 'price' => 'price'];
    }
}