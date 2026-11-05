<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Capabilities;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedCarrierV2;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItem as ShipmentItem;
use MyParcelNL\Sdk\Client\Jane\CoreApiModels;
use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesRequest;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

final class CapabilitiesRequestFromShipmentTest extends TestCase
{
    public function testFromShipmentThrowsWhenRecipientMissing(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Recipient with country code');

        CapabilitiesRequest::fromShipment(new ShipmentItem());
    }

    public function testFromShipmentProjectionMinimal(): void
    {
        $shipment = CoreApiModels::toModel([
            'recipient'           => ['cc' => 'NL'],
            'shop_id'             => 18,
            'physical_properties' => ['weight' => 500],
        ], ShipmentItem::class);

        $req = CapabilitiesRequest::fromShipment($shipment)->withShopId(18);

        $this->assertSame('NL', $req->getCountryCode());
        $this->assertSame(18, $req->getShopId());
        $this->assertNull($req->getCarrier());
        $this->assertSame(['weight' => ['value' => 500.0, 'unit' => 'g']], $req->getPhysicalProperties());
    }

    public function testFromShipmentWithDimensions(): void
    {
        $shipment = CoreApiModels::toModel([
            'recipient'           => ['cc' => 'NL'],
            'shop_id'             => 42,
            'physical_properties' => ['weight' => 750, 'height' => 10, 'length' => 20, 'width' => 30],
        ], ShipmentItem::class);

        $req = CapabilitiesRequest::fromShipment($shipment)->withShopId(42);

        $this->assertSame('NL', $req->getCountryCode());
        $this->assertSame(42, $req->getShopId());
        $this->assertNull($req->getCarrier());
        $this->assertSame([
            'weight' => ['value' => 750.0, 'unit' => 'g'],
            'height' => ['value' => 10.0,  'unit' => 'cm'],
            'length' => ['value' => 20.0,  'unit' => 'cm'],
            'width'  => ['value' => 30.0,  'unit' => 'cm'],
        ], $req->getPhysicalProperties());
    }

    public function testFromShipmentWithoutPhysicalPropertiesSetsNone(): void
    {
        $req = CapabilitiesRequest::fromShipment(CoreApiModels::toModel(['recipient' => ['cc' => 'BE']], ShipmentItem::class));

        $this->assertSame('BE', $req->getCountryCode());
        $this->assertNull($req->getPhysicalProperties());
    }

    public function testFromShipmentWithExplicitCapabilitiesCarrier(): void
    {
        $shipment = CoreApiModels::toModel([
            'recipient'           => ['cc' => 'NL'],
            'physical_properties' => ['weight' => 500],
        ], ShipmentItem::class);

        $req = CapabilitiesRequest::fromShipment($shipment)
            ->withShopId(18)
            ->withCarrier(RefCapabilitiesSharedCarrierV2::Postnl->value);

        $this->assertSame('NL', $req->getCountryCode());
        $this->assertSame(18, $req->getShopId());
        $this->assertSame('POSTNL', $req->getCarrier());
    }
}
