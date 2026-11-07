<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Capabilities;

use Mockery;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2;
use MyParcelNL\Sdk\Client\Jane\CoreApiModels;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesResponsesCapabilitiesV2;
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
        $request = CoreApiModels::toModel(['recipient' => ['countryCode' => 'NL']], CapabilitiesPostCapabilitiesRequestV2::class);
        $expectedResponse = new CapabilitiesResponsesCapabilitiesV2();

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
