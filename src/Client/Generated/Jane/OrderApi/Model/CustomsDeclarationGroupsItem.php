<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class CustomsDeclarationGroupsItem implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * A clear and detailed description of the goods within this group.
     *
     * @var string
     */
    public string $description;
    /**
     * The Harmonized System (HS) code for the goods in this group, a globally recognized classification for traded products.
     *
     * @var string
     */
    public string $hsCode;
    /**
     * The total quantity of individual items within this specific group.
     *
     * @var int
     */
    public int $numberOfItems;
    /**
     * The total declared value of all items within this group.
     *
     * @var CustomsDeclarationGroupsItemTotalValue
     */
    public CustomsDeclarationGroupsItemTotalValue $totalValue;
    /**
     * The total weight of all items within this specific group.
     *
     * @var CustomsDeclarationGroupsItemTotalWeight
     */
    public CustomsDeclarationGroupsItemTotalWeight $totalWeight;
    /**
     * @var string
     */
    public string $countryCodeOfOrigin;
    /**
     * Reference to the product in an external product catalog.
     *
     * @var string
     */
    public string $externalProductReference;
    public function definedProperties(): array
    {
        return ['description' => 'description', 'hsCode' => 'hsCode', 'numberOfItems' => 'numberOfItems', 'totalValue' => 'totalValue', 'totalWeight' => 'totalWeight', 'countryCodeOfOrigin' => 'countryCodeOfOrigin', 'externalProductReference' => 'externalProductReference'];
    }
}