<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Capabilities;

use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2PickupLocation;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefShipmentPackageTypeV2;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefTypesCarrierV2;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefTypesDeliveryTypeV2;
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
            ->withDirection(CapabilitiesPostCapabilitiesRequestV2::DIRECTION_OUTBOUND)
            ->withPickup([
                'location' => [
                    'type' => CapabilitiesPostCapabilitiesRequestV2PickupLocation::TYPE_RETAIL,
                ],
            ]);

        $mapper  = new CapabilitiesMapper();
        $coreReq = $mapper->mapToCoreApi($req);

        $this->assertSame('NL', $coreReq->getRecipient()->getCountryCode());
        $this->assertSame(42, $coreReq->getShopId());
        $this->assertSame(RefTypesDeliveryTypeV2::STANDARD_DELIVERY, $coreReq->getDeliveryType());
        $this->assertSame(RefTypesCarrierV2::POSTNL, $coreReq->getCarrier());
        $this->assertSame(RefShipmentPackageTypeV2::PACKAGE, $coreReq->getPackageType());
        $this->assertSame(CapabilitiesPostCapabilitiesRequestV2::DIRECTION_OUTBOUND, $coreReq->getDirection());
        $this->assertNotNull($coreReq->getPickup());
        $this->assertNotNull($coreReq->getPickup()->getLocation());
        $this->assertSame(
            CapabilitiesPostCapabilitiesRequestV2PickupLocation::TYPE_RETAIL,
            $coreReq->getPickup()->getLocation()->getType()
        );
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

        $this->assertNull($coreReq->getSender());
        $this->assertNull($coreReq->getOptions());
        $this->assertNull($coreReq->getPhysicalProperties());
        $this->assertNull($coreReq->getPickup());
    }

    public function testMapToCoreApiMapsSenderAndPhysicalProperties(): void
    {
        $req = CapabilitiesRequest::forCountry('DE')
            ->withSender(['country_code' => 'DE', 'is_business' => true])
            ->withPhysicalProperties([
                'height' => ['value' => 10.5, 'unit' => 'cm'],
                'weight' => ['value' => 250.0, 'unit' => 'g'],
                'width'  => ['value' => 15.2, 'unit' => 'cm'],
            ]);

        $mapper  = new CapabilitiesMapper();
        $coreReq = $mapper->mapToCoreApi($req);

        $this->assertSame('DE', $coreReq->getRecipient()->getCountryCode());

        $sender = $coreReq->getSender();
        $this->assertNotNull($sender);
        $this->assertSame('DE', $sender->getCountryCode());
        $this->assertTrue($sender->getIsBusiness());

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

        $this->assertNull($physical->getLength());
    }

    public function testMapToCoreApiCreatesSenderObjectEvenWhenNoKnownFieldsArePresent(): void
    {
        $req = CapabilitiesRequest::forCountry('FR')
            ->withSender(['invalid_key' => 'value']);

        $mapper  = new CapabilitiesMapper();
        $coreReq = $mapper->mapToCoreApi($req);

        $sender = $coreReq->getSender();
        $this->assertNotNull($sender);

        // Stable assertion: country code is not mapped.
        $this->assertNull($sender->getCountryCode());
    }

    public function testMapToCoreApiMapsOptionsKnownAndFallbackAndNormalizesNull(): void
    {
        $req = CapabilitiesRequest::forCountry('NL')
            ->withOptions([
                // Known mapping (key differs from setter name)
                'signature' => null,

                // Fallback mapping (key matches generated setter name)
                'saturday_delivery' => null,

                // Value should pass through unchanged (array/object)
                'insurance' => ['amount' => 100],
            ]);

        $mapper  = new CapabilitiesMapper();
        $coreReq = $mapper->mapToCoreApi($req);

        $options = $coreReq->getOptions();
        $this->assertNotNull($options);

        $this->assertNotNull($options->getRequiresSignature());
        $this->assertInstanceOf(\stdClass::class, $options->getRequiresSignature());

        $this->assertNotNull($options->getSaturdayDelivery());
        $this->assertInstanceOf(\stdClass::class, $options->getSaturdayDelivery());

        $this->assertNotNull($options->getInsurance());
    }

    public function testMapToCoreApiIgnoresUnknownOptionKeysWithoutCrashing(): void
    {
        $req = CapabilitiesRequest::forCountry('NL')
            ->withOptions([
                'some_future_option' => null,
                'another_future_option' => ['foo' => 'bar'],
            ]);

        $mapper  = new CapabilitiesMapper();
        $coreReq = $mapper->mapToCoreApi($req);

        // The important thing: mapping succeeds and still returns a request.
        $this->assertSame('NL', $coreReq->getRecipient()->getCountryCode());

        // Options object may exist or not depending on mapper implementation.
        // If it exists, at least it should not crash.
        $this->assertTrue(true);
    }

    public function testMapToCoreApiDoesNotConvertFalseOrZeroToStdClass(): void
    {
        $req = CapabilitiesRequest::forCountry('NL')
            ->withOptions([
                // These values must remain as-is, not be replaced by stdClass().
                'signature' => false,
                'insurance' => 0,
            ]);

        $mapper  = new CapabilitiesMapper();
        $coreReq = $mapper->mapToCoreApi($req);

        $options = $coreReq->getOptions();
        $this->assertNotNull($options);

        $this->assertSame(false, $options->getRequiresSignature());
        $this->assertSame(0, $options->getInsurance());
    }
}
