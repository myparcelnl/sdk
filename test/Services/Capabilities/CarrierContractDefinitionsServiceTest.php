<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Capabilities;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\BadResponseException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\PostCapabilitiesContractDefinitionsClientErrorException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostContractDefinitionsRequestV2Carrier;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedCarrierV2;
use MyParcelNL\Sdk\Services\Capabilities\CarrierContractDefinitionsService;
use MyParcelNL\Sdk\Services\CoreApi\ShipmentApiFactory;
use MyParcelNL\Sdk\Test\Bootstrap\MocksHttpClient;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use TypeError;

final class CarrierContractDefinitionsServiceTest extends TestCase
{
    use MocksHttpClient;

    public function testGetContractDefinitionsFetchesAllDefinitions(): void
    {
        $service = $this->createService('{"items":[{"carrier":"POSTNL"},{"carrier":"INPOST"}]}');
        $service->setUserAgentForProposition('Shopify', '1.0.0');

        $definitions = $service->getContractDefinitions();

        self::assertSame('POST', $this->requests[0]['method']);
        self::assertSame('https://api.myparcel.nl/shipments/capabilities/contract-definitions', $this->requests[0]['url']);
        self::assertSame('application/json;version=2.0', $this->header('content-type'));
        self::assertSame('MyPlugin/1.0', $this->header('user-agent'));
        self::assertSame('{}', $this->requests[0]['options']['body']);
        self::assertSame(
            [RefCapabilitiesSharedCarrierV2::Postnl, RefCapabilitiesSharedCarrierV2::Inpost],
            array_map(static fn(object $definition) => $definition->carrier, $definitions)
        );
    }

    public function testGetContractDefinitionsSendsTheCarrierFilter(): void
    {
        $service = $this->createService('{"items":[{"carrier":"INPOST"}]}');

        $service->getContractDefinitions(CapabilitiesPostContractDefinitionsRequestV2Carrier::Inpost);

        self::assertSame('{"carrier":"INPOST"}', $this->requests[0]['options']['body']);
    }

    /**
     * INT-2033 asks that an unknown enum value in a response gives no exception. The client reads a
     * response without validation, so the raw string comes back.
     */
    public function testGetContractDefinitionsKeepsAValueThatTheSdkDoesNotKnow(): void
    {
        $definitions = $this->createService('{"items":[{"carrier":"FUTURE_CARRIER","packageTypes":["FUTURE_PACKAGE"]}]}')->getContractDefinitions();

        self::assertSame('FUTURE_CARRIER', $definitions[0]->carrier);
        self::assertSame(['FUTURE_PACKAGE'], $definitions[0]->packageTypes);
    }

    public function testGetContractDefinitionsReturnsAnEmptyListWithoutItems(): void
    {
        self::assertSame([], $this->createService('{}')->getContractDefinitions());
    }

    public function testAnErrorResponseThrowsTheGeneratedException(): void
    {
        $service = $this->createService('{"message":"Unauthorized"}', 401);

        try {
            $service->getContractDefinitions();
            self::fail('A 401 response must throw.');
        } catch (PostCapabilitiesContractDefinitionsClientErrorException $exception) {
            self::assertSame(401, $exception->getResponse()->getStatusCode());
        }
    }

    public function testAResponseWithoutJsonThrowsTheGeneratedException(): void
    {
        $service = $this->createService('{"items":[]}', 200, 'text/plain');

        $this->expectException(BadResponseException::class);

        $service->getContractDefinitions();
    }

    /**
     * The constructor of `CreatesCoreApiClient` takes the API key, the client and the host in this
     * order. In v11, this service took the host as the second argument.
     */
    public function testAHostAsTheSecondArgumentThrowsATypeError(): void
    {
        $this->expectException(TypeError::class);

        new CarrierContractDefinitionsService('my-api-key', 'https://api.acceptance.myparcel.nl');
    }

    private function createService(string $body, int $status = 200, string $contentType = 'application/json'): CarrierContractDefinitionsService
    {
        return new CarrierContractDefinitionsService(
            'my-api-key',
            ShipmentApiFactory::client('my-api-key', null, 'MyPlugin/1.0', [], $this->mockHttpClient($body, $status, $contentType))
        );
    }
}
