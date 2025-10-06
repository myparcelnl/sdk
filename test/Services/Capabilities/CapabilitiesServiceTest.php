<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Capabilities;

use Mockery;
use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesRequest;
use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesResponse;
use MyParcelNL\Sdk\Services\Capabilities\CapabilitiesService;
use MyParcelNL\Sdk\Services\CoreApi\CapabilitiesClientInterface;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

final class CapabilitiesServiceTest extends TestCase
{
    /**
     * Test that the service passes through requests to the client.
     */
    public function testGetPassesThroughToClient(): void
    {
        // Arrange
        $request = CapabilitiesRequest::forCountry('NL');
        $expectedResponse = new CapabilitiesResponse(
            ['PACKAGE'], 
            ['STANDARD_DELIVERY'], 
            ['signature'], 
            'POSTNL', 
            ['B2C'], 
            1
        );

        $clientMock = Mockery::mock(CapabilitiesClientInterface::class);
        $clientMock
            ->shouldReceive('getCapabilities')
            ->once()
            ->with($request)
            ->andReturn($expectedResponse);

        $service = new CapabilitiesService($clientMock);

        // Act
        $result = $service->get($request);

        // Assert
        $this->assertSame($expectedResponse, $result);
    }

    /**
     * Test that the service creates a default client when none is provided.
     */
    public function testCreatesDefaultClient(): void
    {
        // This test verifies the service can be instantiated without dependencies
        $service = new CapabilitiesService();

        $this->assertInstanceOf(CapabilitiesService::class, $service);
    }
}
