<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Capabilities;

use MyParcel\CoreApi\Generated\Capabilities\Model\CapabilitiesPostCapabilitiesRequestV2;
use MyParcel\CoreApi\Generated\Capabilities\Model\RefShipmentPackageTypeV2;
use MyParcel\CoreApi\Generated\Capabilities\Model\RefTypesCarrierV2;
use MyParcel\CoreApi\Generated\Capabilities\Model\RefTypesDeliveryTypeV2;
use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesMapper;
use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesRequest;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

final class CapabilitiesMapperRequestTest extends TestCase
{
    public function testMapToCoreApiSetsAllProvidedFields(): void
    {
        $req = CapabilitiesRequest::forCountry('NL')
            ->withShopId(42)
            ->withDeliveryType(RefTypesDeliveryTypeV2::STANDARD_DELIVERY)
            ->withCarrier(RefTypesCarrierV2::POSTNL)
            ->withPackageType(RefShipmentPackageTypeV2::PACKAGE)
            ->withDirection(CapabilitiesPostCapabilitiesRequestV2::DIRECTION_OUTBOUND);

        $mapper  = new CapabilitiesMapper();
        $coreReq = $mapper->mapToCoreApi($req);

        $this->assertSame('NL', $coreReq->getRecipient()->getCountryCode());
        $this->assertSame(42, $coreReq->getShopId());
        $this->assertSame(RefTypesDeliveryTypeV2::STANDARD_DELIVERY, $coreReq->getDeliveryType());
        $this->assertSame(RefTypesCarrierV2::POSTNL, $coreReq->getCarrier());
        $this->assertSame(RefShipmentPackageTypeV2::PACKAGE, $coreReq->getPackageType());
        $this->assertSame(CapabilitiesPostCapabilitiesRequestV2::DIRECTION_OUTBOUND, $coreReq->getDirection());
    }

    public function testMapToCoreApiAllowsMinimalPayload(): void
    {
        $req = CapabilitiesRequest::forCountry('BE');

        $mapper  = new CapabilitiesMapper();
        $coreReq = $mapper->mapToCoreApi($req);

        $this->assertSame('BE', $coreReq->getRecipient()->getCountryCode());
        $this->assertNull($coreReq->getShopId());
        $this->assertNull($coreReq->getDeliveryType());
        $this->assertNull($coreReq->getCarrier());
        $this->assertNull($coreReq->getPackageType());
        $this->assertNull($coreReq->getDirection());
    }

    public function testInvalidInputsAreIgnored(): void
    {
        $req = CapabilitiesRequest::forCountry('NL')
            ->withDeliveryType('invalid-delivery-type')
            ->withPackageType('invalid-package-type')
            ->withCarrier('invalid-carrier')
            ->withDirection('invalid-direction');

        $mapper  = new CapabilitiesMapper();
        $coreReq = $mapper->mapToCoreApi($req);

        $this->assertSame('NL', $coreReq->getRecipient()->getCountryCode());
        // Invalid values should be ignored (not set)
        $this->assertNull($coreReq->getDeliveryType());
        $this->assertNull($coreReq->getPackageType());
        $this->assertNull($coreReq->getCarrier());
        $this->assertNull($coreReq->getDirection());
    }

    public function testMapToCoreApiWithSenderOptionsAndPhysicalProperties(): void
    {
        $req = CapabilitiesRequest::forCountry('DE')
            ->withSender(['country_code' => 'DE', 'is_business' => true])
            ->withOptions([
                'requires_signature' => new \stdClass(),
                'insurance' => ['amount' => 100],
                'saturday_delivery' => null // This should still work (gets converted to empty object)
            ])
            ->withPhysicalProperties([
                'height' => ['value' => 10.5, 'unit' => 'cm'],
                'weight' => ['value' => 250.0, 'unit' => 'g'],
                'width' => ['value' => 15.2, 'unit' => 'cm']
            ]);

        $mapper = new CapabilitiesMapper();
        $coreReq = $mapper->mapToCoreApi($req);

        $this->assertSame('DE', $coreReq->getRecipient()->getCountryCode());
        
        // Test sender mapping
        $sender = $coreReq->getSender();
        $this->assertNotNull($sender);
        $this->assertSame('DE', $sender->getCountryCode());
        $this->assertTrue($sender->getIsBusiness());
        
        // Test options mapping
        $options = $coreReq->getOptions();
        $this->assertNotNull($options);
        $this->assertNotNull($options->getRequiresSignature());
        $this->assertNotNull($options->getInsurance());
        $this->assertNotNull($options->getSaturdayDelivery());
        
        // Test physical properties mapping
        $physical = $coreReq->getPhysicalProperties();
        $this->assertNotNull($physical);
        
        $this->assertNotNull($physical->getHeight());
        $this->assertSame(10.5, $physical->getHeight()->getValue());
        $this->assertSame('cm', $physical->getHeight()->getUnit());
        
        $this->assertNotNull($physical->getWeight());
        $this->assertSame(250.0, $physical->getWeight()->getValue());
        $this->assertSame('g', $physical->getWeight()->getUnit());
        
        $this->assertNotNull($physical->getWidth());
        $this->assertSame(15.2, $physical->getWidth()->getValue());
        $this->assertSame('cm', $physical->getWidth()->getUnit());
        
        // Length was not provided, should be null
        $this->assertNull($physical->getLength());
    }

    public function testMapToCoreApiIgnoresInvalidSenderData(): void
    {
        $req = CapabilitiesRequest::forCountry('FR')
            ->withSender(['invalid_key' => 'value']); // No valid sender fields

        $mapper = new CapabilitiesMapper();
        $coreReq = $mapper->mapToCoreApi($req);

        // Invalid sender data should be ignored
        $this->assertNull($coreReq->getSender());
    }
}
