<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class LineProduct implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * The name of the product.
     *
     * @var string
     */
    public string $name;
    /**
     * The EAN of the product.
     *
     * @var string
     */
    public string $ean;
    /**
     * The SKU of the product.
     *
     * @var string
     */
    public string $sku;
    /**
     * Identifier of the product. This is always undefined when the `status` is `DISCOVERED` or `IMPORT_REQUESTED`, otherwise it is always defined.
     *
     * @var string
     */
    public string $id;
    public function definedProperties(): array
    {
        return ['name' => 'name', 'ean' => 'ean', 'sku' => 'sku', 'id' => 'id'];
    }
}