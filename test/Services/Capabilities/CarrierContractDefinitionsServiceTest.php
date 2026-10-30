<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Capabilities;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\BadResponseException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\PostCapabilitiesContractDefinitionsClientErrorException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostContractDefinitionsRequestV2Carrier;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedCarrierV2;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Normalizer\ValidationException;
use MyParcelNL\Sdk\Services\Capabilities\CarrierContractDefinitionsService;
use MyParcelNL\Sdk\Services\CoreApi\ShipmentApiFactory;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

final class CarrierContractDefinitionsServiceTest extends TestCase
{
    /**
     * @var array<int, array{method: string, url: string, content_type: string[], user_agent: string[], body: mixed}>
     */
    private array $requests = [];

    public function testGetContractDefinitionsFetchesAllDefinitions(): void
    {
        $service = $this->createService('{"items":[{"carrier":"POSTNL"},{"carrier":"INPOST"}]}');
        $service->setUserAgentForProposition('Shopify', '1.0.0');

        $definitions = $service->getContractDefinitions();

        self::assertSame('POST', $this->requests[0]['method']);
        self::assertSame('https://api.myparcel.nl/shipments/capabilities/contract-definitions', $this->requests[0]['url']);
        self::assertSame(['application/json;version=2.0'], $this->requests[0]['content_type']);
        self::assertSame(['User-Agent: ' . $service->getUserAgentHeader()], $this->requests[0]['user_agent']);
        self::assertSame([], $this->requests[0]['body']);
        self::assertSame(
            [RefCapabilitiesSharedCarrierV2::Postnl, RefCapabilitiesSharedCarrierV2::Inpost],
            array_map(static fn(object $definition) => $definition->carrier, $definitions)
        );
    }

    public function testGetContractDefinitionsSendsTheCarrierFilter(): void
    {
        $service = $this->createService('{"items":[{"carrier":"INPOST"}]}');

        $service->getContractDefinitions('INPOST');
        $service->getContractDefinitions(CapabilitiesPostContractDefinitionsRequestV2Carrier::Inpost);

        self::assertSame(['carrier' => 'INPOST'], $this->requests[0]['body']);
        self::assertSame(['carrier' => 'INPOST'], $this->requests[1]['body']);
    }

    public function testGetContractDefinitionsRejectsUnknownCarrierBeforeSending(): void
    {
        $service = $this->createService('{"items":[]}');

        try {
            $service->getContractDefinitions('inpost');
            self::fail('An unknown carrier must throw.');
        } catch (ValidationException) {
            self::assertSame([], $this->requests);
        }
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

    private function createService(string $body, int $status = 200, string $contentType = 'application/json'): CarrierContractDefinitionsService
    {
        $httpClient = new MockHttpClient(function (string $method, string $url, array $options) use ($body, $status, $contentType): MockResponse {
            $this->requests[] = [
                'method'       => $method,
                'url'          => $url,
                'content_type' => array_map(static fn(string $line): string => substr($line, 14), $options['normalized_headers']['content-type'] ?? []),
                'user_agent'   => $options['normalized_headers']['user-agent'] ?? [],
                'body'         => json_decode((string) ($options['body'] ?? ''), true),
            ];

            return new MockResponse($body, ['http_code' => $status, 'response_headers' => ['content-type' => $contentType]]);
        });

        return new CarrierContractDefinitionsService(
            'my-api-key',
            null,
            ShipmentApiFactory::make('my-api-key', null, null, [], $httpClient)
        );
    }
}
