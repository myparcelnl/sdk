<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalPropertiesInterface;
class Product implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var string
     */
    public string $name;
    /**
     * The product ID as defined in the source system.
     *
     * @var string
     */
    public string $sourceId;
    /**
     * Whether the shop tracks the stock of this product.
     *
     * @var bool
     */
    public bool $trackInventory;
    /**
     * @var string
     */
    public string $barcode;
    /**
     * The parts of a bundle product, identified by their own source ID.
     *
     * @var list<ProductChild>
     */
    public array $children;
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
     * @var list<ProductDescriptionsItem>
     */
    public array $descriptions;
    /**
     * @var string
     */
    public string $ean;
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
     * The physical dimensions.
     *
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
     * @var SalesPrice
     */
    public SalesPrice $salesPrice;
    /**
     * @var string
     */
    public string $sku;
    public function definedProperties(): array
    {
        return ['name' => 'name', 'sourceId' => 'sourceId', 'trackInventory' => 'trackInventory', 'barcode' => 'barcode', 'children' => 'children', 'containsDangerousSubstances' => 'containsDangerousSubstances', 'countryCodeOfOrigin' => 'countryCodeOfOrigin', 'descriptions' => 'descriptions', 'ean' => 'ean', 'hasUnlimitedShelfLife' => 'hasUnlimitedShelfLife', 'hsCode' => 'hsCode', 'physicalProperties' => 'physicalProperties', 'purchasePrice' => 'purchasePrice', 'salesPrice' => 'salesPrice', 'sku' => 'sku'];
    }
}