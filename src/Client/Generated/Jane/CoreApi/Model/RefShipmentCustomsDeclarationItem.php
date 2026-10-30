<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

class RefShipmentCustomsDeclarationItem
{
    /**
     * @var string
     */
    public string $description;
    /**
     * @var int
     */
    public int $amount;
    /**
     * @var int
     */
    public int $weight;
    /**
     * Money amount with currency
     *
     * @var RefTypesMoney
     */
    public RefTypesMoney $itemValue;
    /**
     * @var string
     */
    public string $classification;
    /**
     * @var mixed
     */
    public $country;
    /**
     * Reference to the product in an external product catalog. Required for PostNL shipments to the United States and Puerto Rico, where it must contain the Zonos Catalog Item ID (e.g. `catalog_item_` followed by 13 characters).
     *
     * @var string|null
     */
    public ?string $externalProductReference;
}