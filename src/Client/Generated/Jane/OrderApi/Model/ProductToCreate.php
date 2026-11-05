<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class ProductToCreate implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var string
     */
    public string $barcode;
    /**
     * @var bool
     */
    public bool $containsDangerousSubstances;
    /**
     * ISO 3166-1 alpha-2 country code.
     *
     * @var string
     */
    public string $countryCodeOfOrigin;
    /**
     * The descriptions of the product.
     *
     * @var list<ProductToCreateDescriptionsItem>
     */
    public array $descriptions;
    /**
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
     * @var ProductToCreateFulfilment
     */
    public ProductToCreateFulfilment $fulfilment;
    /**
     * @var bool
     */
    public bool $hasUnlimitedShelfLife;
    /**
     * The HS code of the product.
     *
     * @var string
     */
    public string $hsCode;
    /**
     * @var string
     */
    public string $name;
    /**
     * @var PhysicalProperties
     */
    public PhysicalProperties $physicalProperties;
    /**
     * A monetary value represented by split integral and fractional parts to avoid floating point errors.
     *
     * @var Money
     */
    public Money $purchasePrice;
    /**
     * A monetary value represented by split integral and fractional parts to avoid floating point errors.
     *
     * @var ProductToCreateSalesPrice
     */
    public ProductToCreateSalesPrice $salesPrice;
    /**
     * @var string
     */
    public string $sku;
    public function definedProperties(): array
    {
        return ['barcode' => 'barcode', 'containsDangerousSubstances' => 'containsDangerousSubstances', 'countryCodeOfOrigin' => 'countryCodeOfOrigin', 'descriptions' => 'descriptions', 'ean' => 'ean', 'externalReference' => 'externalReference', 'fulfilment' => 'fulfilment', 'hasUnlimitedShelfLife' => 'hasUnlimitedShelfLife', 'hsCode' => 'hsCode', 'name' => 'name', 'physicalProperties' => 'physicalProperties', 'purchasePrice' => 'purchasePrice', 'salesPrice' => 'salesPrice', 'sku' => 'sku'];
    }
}