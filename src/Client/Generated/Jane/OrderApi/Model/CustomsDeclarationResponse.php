<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class CustomsDeclarationResponse implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * The list of item groups.
     *
     * @var list<CustomsDeclarationResponseGroupsItem>
     */
    public array $groups;
    /**
     * The primary reason or purpose for the international shipment.
     *
     * @var CustomsDeclarationResponsePurpose|string
     */
    public CustomsDeclarationResponsePurpose|string $purpose;
    /**
     * The total weight of all items declared within this customs declaration.
     *
     * @var CustomsDeclarationResponseTotalWeight
     */
    public CustomsDeclarationResponseTotalWeight $totalWeight;
    /**
     * The identifying number of the commercial invoice associated with this shipment.
     *
     * @var string
     */
    public string $invoiceNumber;
    public function definedProperties(): array
    {
        return ['groups' => 'groups', 'purpose' => 'purpose', 'totalWeight' => 'totalWeight', 'invoiceNumber' => 'invoiceNumber'];
    }
}