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
    public function testGetAllFetchesContractDefinitionsThroughGeneratedClient(): void
    {
        $items = [
            $this->createDefinition(RefCapabilitiesSharedCarrierV2::POSTNL),
            $this->createDefinition(RefCapabilitiesSharedCarrierV2::INPOST),
        ];

        $api = $this->createMock(ShipmentApi::class);
        $service = new CarrierContractDefinitionsService($this->getApiKey(), $api);
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

        self::assertSame($items, $service->getAll());
    }

    public function testGetByCarrierFiltersReturnedDefinitions(): void
    {
        $postNl = $this->createDefinition(RefCapabilitiesSharedCarrierV2::POSTNL);
        $posteItaliane = $this->createDefinition(RefCapabilitiesSharedCarrierV2::POSTE_ITALIANE);
        $inPost = $this->createDefinition(RefCapabilitiesSharedCarrierV2::INPOST);

        $api = $this->createMock(ShipmentApi::class);
        $api->expects(self::once())
            ->method('postCapabilitiesContractDefinitions')
            ->willReturn(new CapabilitiesResponsesContractDefinitionsV2([
                'items' => [$postNl, $posteItaliane, $inPost],
            ]));

        $service = new CarrierContractDefinitionsService($this->getApiKey(), $api);

        self::assertSame([$posteItaliane], $service->getByCarrier('posteitaliane'));
    }

    public function testGetByCarrierAcceptsGeneratedEnumValues(): void
    {
        $inPost = $this->createDefinition(RefCapabilitiesSharedCarrierV2::INPOST);

        $api = $this->createMock(ShipmentApi::class);
        $api->expects(self::once())
            ->method('postCapabilitiesContractDefinitions')
            ->willReturn(new CapabilitiesResponsesContractDefinitionsV2([
                'items' => [$inPost],
            ]));

        $service = new CarrierContractDefinitionsService($this->getApiKey(), $api);

        self::assertSame([$inPost], $service->getByCarrier(RefCapabilitiesSharedCarrierV2::INPOST));
    }

    public function testGetAllThrowsWhenGeneratedClientReturnsUnexpectedResponse(): void
    {
        $api = $this->createMock(ShipmentApi::class);
        $api->expects(self::once())
            ->method('postCapabilitiesContractDefinitions')
            ->willReturn(new \stdClass());

        $service = new CarrierContractDefinitionsService($this->getApiKey(), $api);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unexpected response type returned by ShipmentApi::postCapabilitiesContractDefinitions().');

        $service->getAll();
    }

    private function createDefinition(string $carrier): RefCapabilitiesContractDefinitionsResponseContractDefinitionsV2
    {
        return new RefCapabilitiesContractDefinitionsResponseContractDefinitionsV2([
            'carrier' => $carrier,
        ]);
    }
}
