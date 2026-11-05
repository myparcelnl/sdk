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
     * @var string
     */
    public string $countryCodeOfOrigin;
    /**
     * The EAN of the product.
     *
     * @var string
     */
    public string $ean;
    /**
     * Reference to the product in an external product catalog, like the zonos catalog id used for constructing a customs declaration.
     *
     * @var string
     */
    public string $externalReference;
    /**
     * @var PhysicalProperties
     */
    public PhysicalProperties $physicalProperties;
    /**
     * The HS code of the product.
     *
     * @var string
     */
    public string $hsCode;
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
        return ['name' => 'name', 'countryCodeOfOrigin' => 'countryCodeOfOrigin', 'ean' => 'ean', 'externalReference' => 'externalReference', 'physicalProperties' => 'physicalProperties', 'hsCode' => 'hsCode', 'sku' => 'sku', 'id' => 'id'];
    }
}