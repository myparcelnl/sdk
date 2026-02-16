<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Capabilities;

use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesRequest;
use MyParcelNL\Sdk\Model\Shipment\Shipment;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

final class CapabilitiesRequestFromShipmentTest extends TestCase
{
    public function testFromShipmentThrowsWhenRecipientMissing(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Recipient with country code');

        $shipment = new Shipment();
        CapabilitiesRequest::fromShipment($shipment);
    }

    public function testFromShipmentProjectionMinimal(): void
    {
        $shipment = (new Shipment())
            ->setRecipient(['cc' => 'NL'])
            ->setShopId(18)
            ->setPhysicalProperties(['weight' => 500]);

        $req = CapabilitiesRequest::fromShipment($shipment)->withShopId(18);

        $this->assertSame('NL', $req->getCountryCode());
        $this->assertSame(18, $req->getShopId());
        $this->assertNull($req->getCarrier());
        $this->assertSame(['weight' => ['value' => 500.0, 'unit' => 'g']], $req->getPhysicalProperties());
    }

    public function testFromShipmentWithDimensions(): void
    {
        $shipment = (new Shipment())
            ->setRecipient(['cc' => 'NL'])
            ->setShopId(42)
            ->setPhysicalProperties([
                'weight' => 750,
                'height' => 10,
                'length' => 20,
                'width'  => 30,
            ]);

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

    public function testFromShipmentWithExplicitCapabilitiesCarrier(): void
    {
        $shipment = (new Shipment())
            ->setRecipient(['cc' => 'NL'])
            ->setPhysicalProperties(['weight' => 500]);

        $req = CapabilitiesRequest::fromShipment($shipment)
            ->withShopId(18)
            ->withCarrier(\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\RefCapabilitiesSharedCarrierV2::POSTNL);

        $this->assertSame('NL', $req->getCountryCode());
        $this->assertSame(18, $req->getShopId());
        $this->assertSame('POSTNL', $req->getCarrier());
    }
}
