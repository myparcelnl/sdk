<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalPropertiesInterface;
class OrderLineProduct implements AdditionalPropertiesInterface
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
     * @var string
     */
    public string $barcode;
    /**
     * ISO 3166-1 alpha-2 country code.
     *
     * @var string
     */
    public string $countryCodeOfOrigin;
    /**
     * The descriptions of the product.
     *
     * @var list<OrderLineProductDescriptionsItem>
     */
    public array $descriptions;
    /**
     * @var string
     */
    public string $ean;
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
        return ['name' => 'name', 'sourceId' => 'sourceId', 'barcode' => 'barcode', 'countryCodeOfOrigin' => 'countryCodeOfOrigin', 'descriptions' => 'descriptions', 'ean' => 'ean', 'hsCode' => 'hsCode', 'physicalProperties' => 'physicalProperties', 'purchasePrice' => 'purchasePrice', 'salesPrice' => 'salesPrice', 'sku' => 'sku'];
    }
}