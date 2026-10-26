<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class ExternalReferencesSalesChannel implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var ExternalReferencesSalesChannelSource|string
     */
    public ExternalReferencesSalesChannelSource|string $source;
    /**
     * The external reference of the sales channel.
     *
     * @var ExternalReferenceSalesChannel
     */
    public ExternalReferenceSalesChannel $salesChannel;
    /**
     * The external reference of the ecommerce platform.
     *
     * @var ExternalReferenceEcommercePlatform
     */
    public ExternalReferenceEcommercePlatform $ecommercePlatform;
    public function definedProperties(): array
    {
        return ['source' => 'source', 'salesChannel' => 'salesChannel', 'ecommercePlatform' => 'ecommercePlatform'];
    }
}