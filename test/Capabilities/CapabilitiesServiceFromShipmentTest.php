<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Capabilities;

use Mockery;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2;
use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesResponse;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItem as ShipmentItem;
use MyParcelNL\Sdk\Client\Jane\CoreApiModels;
use MyParcelNL\Sdk\Services\Capabilities\CapabilitiesService;
use MyParcelNL\Sdk\Services\CoreApi\CapabilitiesClientInterface;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

final class CapabilitiesServiceFromShipmentTest extends TestCase
{
    public function testServiceCallsClientWithProjectedRequest(): void
    {
        $shipment = CoreApiModels::toModel([
            'recipient'           => ['cc' => 'NL'],
            'physical_properties' => ['weight' => 500],
        ], ShipmentItem::class);

        $expected = new CapabilitiesResponse(
            ['PACKAGE', 'MAILBOX'],
            ['STANDARD_DELIVERY'],
            ['signature'],
            null,
            ['B2C'],
            1
        );

        $client = Mockery::mock(CapabilitiesClientInterface::class);
        $client->shouldReceive('getCapabilities')
            ->once()
            ->withArgs(function (CapabilitiesPostCapabilitiesRequestV2 $req) {
                $this->assertSame('NL', $req->recipient->countryCode);
                $this->assertSame(500, $req->physicalProperties->weight->value);
                $this->assertSame('g', $req->physicalProperties->weight->unit->value);
                $this->assertFalse(isset($req->carrier));

                return true;
            })
            ->andReturn($expected);

        $service = new CapabilitiesService($client);
        $response = $service->fromShipment($shipment);

        $this->assertSame($expected, $response);
    }

    public function testFromShipmentThrowsWithoutACountryCode(): void
    {
        $shipment = CoreApiModels::toModel(['physical_properties' => ['weight' => 500]], ShipmentItem::class);

        $this->expectException(\InvalidArgumentException::class);

        (new CapabilitiesService(Mockery::mock(CapabilitiesClientInterface::class)))->fromShipment($shipment);
    }
}
