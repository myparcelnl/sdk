<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\CoreApi;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\PostCapabilitiesClientErrorException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Normalizer\ValidationException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedCarrierV2;
use MyParcelNL\Sdk\Client\Jane\CoreApiModels;
use MyParcelNL\Sdk\Services\CoreApi\HttpCapabilitiesClient;
use MyParcelNL\Sdk\Services\CoreApi\ShipmentApiFactory;
use MyParcelNL\Sdk\Test\Bootstrap\MocksHttpClient;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

final class HttpCapabilitiesClientTest extends TestCase
{
    use MocksHttpClient;

    /**
     * The request goes out as the body, and the client that the caller passes keeps its own User-Agent.
     */
    public function testGetCapabilitiesSendsTheRequestAndReadsTheResponse(): void
    {
        $client = $this->createClient(
            '{"results":[{"carrier":"POSTNL","packageTypes":["PACKAGE"],"deliveryTypes":["STANDARD_DELIVERY"],'
            . '"transactionTypes":["B2C"],"collo":{"max":5}}]}'
        );

        $response = $client->getCapabilities(self::request(['recipient' => ['countryCode' => 'NL'], 'carrier' => 'POSTNL']));

        self::assertSame('POST', $this->requests[0]['method']);
        self::assertSame('https://api.myparcel.nl/shipments/capabilities', $this->requests[0]['url']);
        self::assertSame('application/json;version=2.0', $this->header('content-type'));
        self::assertSame('MyPlugin/1.0 Magento/2.4', $this->header('user-agent'));
        self::assertJsonStringEqualsJsonString('{"recipient":{"countryCode":"NL"},"carrier":"POSTNL"}', $this->requests[0]['options']['body']);

        $result = $response->results[0];
        self::assertSame(RefCapabilitiesSharedCarrierV2::Postnl, $result->carrier);
        self::assertSame(['PACKAGE'], array_map(static fn ($type) => $type->value, $result->packageTypes));
        self::assertSame(['STANDARD_DELIVERY'], array_map(static fn ($type) => $type->value, $result->deliveryTypes));
        self::assertSame(['B2C'], array_map(static fn ($type) => $type->value, $result->transactionTypes));
        self::assertSame(5, $result->collo->max);
    }

    /**
     * INT-2033 asks that an unknown enum value in a response gives no exception. The client reads a
     * response without validation, so the raw strings come back.
     */
    public function testGetCapabilitiesKeepsValuesThatTheSdkDoesNotKnow(): void
    {
        $client = $this->createClient(
            '{"results":[{"carrier":"FUTURE_CARRIER","packageTypes":["FUTURE_PACKAGE"],"options":{"requiresSignature":{},"futureOption":{}}}]}'
        );

        $response = $client->getCapabilities(self::request(['recipient' => ['countryCode' => 'NL']]));

        self::assertSame('FUTURE_CARRIER', $response->results[0]->carrier);
        self::assertSame(['FUTURE_PACKAGE'], $response->results[0]->packageTypes);
        self::assertSame(['requiresSignature', 'futureOption'], array_keys(CoreApiModels::toArray($response->results[0]->options)));
    }

    public function testGetCapabilitiesRejectsACarrierThatTheSpecDoesNotKnowBeforeSending(): void
    {
        $client = $this->createClient('{"results":[]}');

        try {
            $client->getCapabilities(self::request(['recipient' => ['countryCode' => 'NL'], 'carrier' => 'FUTURE_CARRIER']));
            self::fail('An unknown carrier must throw.');
        } catch (ValidationException) {
            self::assertSame([], $this->requests);
        }
    }

    public function testAnErrorResponseThrowsTheGeneratedException(): void
    {
        $client = $this->createClient('{"message":"Invalid country code"}', 422);

        try {
            $client->getCapabilities(self::request(['recipient' => ['countryCode' => 'XX']]));
            self::fail('A 422 response must throw.');
        } catch (PostCapabilitiesClientErrorException $exception) {
            self::assertSame(422, $exception->getResponse()->getStatusCode());
        }
    }

    /**
     * @param array<string, mixed> $data The request with the API field names.
     */
    private static function request(array $data): CapabilitiesPostCapabilitiesRequestV2
    {
        return CoreApiModels::toModel($data, CapabilitiesPostCapabilitiesRequestV2::class);
    }

    private function createClient(string $body, int $status = 200): HttpCapabilitiesClient
    {
        return new HttpCapabilitiesClient(ShipmentApiFactory::client('my-api-key', null, 'MyPlugin/1.0 Magento/2.4', [], $this->mockHttpClient($body, $status)));
    }
}
