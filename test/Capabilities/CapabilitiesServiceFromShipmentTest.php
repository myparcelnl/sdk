<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Capabilities;

use Mockery;
use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesRequest;
use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesResponse;
use MyParcelNL\Sdk\Model\Shipment\Shipment;
use MyParcelNL\Sdk\Services\Capabilities\CapabilitiesService;
use MyParcelNL\Sdk\Services\CoreApi\CapabilitiesClientInterface;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

final class CapabilitiesServiceFromShipmentTest extends TestCase
{
    public function testServiceCallsClientWithProjectedRequest(): void
    {
        $shipment = (new Shipment())
            ->setRecipient(['cc' => 'NL'])
            ->setPhysicalProperties(['weight' => 500]);

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
            ->withArgs(function (CapabilitiesRequest $req) {
                $this->assertSame('NL', $req->getCountryCode());
                $this->assertSame([
                    'weight' => ['value' => 500.0, 'unit' => 'g'],
                ], $req->getPhysicalProperties());
                return true;
            })
            ->andReturn($expected);

        $service = new CapabilitiesService($client);
        $response = $service->fromShipment($shipment);

        $this->assertSame($expected, $response);
    }
}

