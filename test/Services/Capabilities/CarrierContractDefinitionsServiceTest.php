<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Capabilities;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Api\ShipmentApi;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CapabilitiesPostContractDefinitionsRequestV2;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CapabilitiesResponsesContractDefinitionsV2;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefCapabilitiesContractDefinitionsResponseContractDefinitionsV2;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefCapabilitiesSharedCarrierV2;
use MyParcelNL\Sdk\Services\Capabilities\CarrierContractDefinitionsService;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

final class CarrierContractDefinitionsServiceTest extends TestCase
{
    public function testGetContractDefinitionsFetchesAllThroughGeneratedClient(): void
    {
        $items = [
            $this->createDefinition(RefCapabilitiesSharedCarrierV2::POSTNL),
            $this->createDefinition(RefCapabilitiesSharedCarrierV2::INPOST),
        ];

        $api = $this->createMock(ShipmentApi::class);
        $service = new CarrierContractDefinitionsService($this->getApiKey(), null, $api);
        $service->setUserAgentForProposition('Shopify', '1.0.0');
        $expectedUserAgent = $service->getUserAgentHeader();

        $api->expects(self::once())
            ->method('postCapabilitiesContractDefinitions')
            ->with(
                self::identicalTo($expectedUserAgent),
                self::callback(static function ($request): bool {
                    self::assertInstanceOf(CapabilitiesPostContractDefinitionsRequestV2::class, $request);
                    self::assertNull($request->getCarrier());

                    return true;
                })
            )
            ->willReturn(new CapabilitiesResponsesContractDefinitionsV2(['items' => $items]));

        self::assertSame($items, $service->getContractDefinitions());
    }

    public function testGetContractDefinitionsPassesCarrierFilterToGeneratedRequest(): void
    {
        $inPost = $this->createDefinition(RefCapabilitiesSharedCarrierV2::INPOST);

        $api = $this->createMock(ShipmentApi::class);
        $api->expects(self::once())
            ->method('postCapabilitiesContractDefinitions')
            ->with(
                self::anything(),
                self::callback(static function ($request): bool {
                    self::assertInstanceOf(CapabilitiesPostContractDefinitionsRequestV2::class, $request);
                    self::assertSame(
                        CapabilitiesPostContractDefinitionsRequestV2::CARRIER_INPOST,
                        $request->getCarrier()
                    );

                    return true;
                })
            )
            ->willReturn(new CapabilitiesResponsesContractDefinitionsV2(['items' => [$inPost]]));

        $service = new CarrierContractDefinitionsService($this->getApiKey(), null, $api);

        self::assertSame(
            [$inPost],
            $service->getContractDefinitions(CapabilitiesPostContractDefinitionsRequestV2::CARRIER_INPOST)
        );
    }

    public function testGetContractDefinitionsLetsGeneratedRequestValidateCarrier(): void
    {
        $api = $this->createMock(ShipmentApi::class);
        $api->expects(self::never())
            ->method('postCapabilitiesContractDefinitions');

        $service = new CarrierContractDefinitionsService($this->getApiKey(), null, $api);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid value 'inpost' for 'carrier'");

        $service->getContractDefinitions('inpost');
    }

    public function testGetContractDefinitionsThrowsWhenGeneratedClientReturnsUnexpectedResponse(): void
    {
        $api = $this->createMock(ShipmentApi::class);
        $api->expects(self::once())
            ->method('postCapabilitiesContractDefinitions')
            ->willReturn(new \stdClass());

        $service = new CarrierContractDefinitionsService($this->getApiKey(), null, $api);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unexpected response type returned by ShipmentApi::postCapabilitiesContractDefinitions().');

        $service->getContractDefinitions();
    }

    private function createDefinition(string $carrier): RefCapabilitiesContractDefinitionsResponseContractDefinitionsV2
    {
        return new RefCapabilitiesContractDefinitionsResponseContractDefinitionsV2([
            'carrier' => $carrier,
        ]);
    }
}
