<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\CoreApi;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\PostCapabilitiesClientErrorException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Normalizer\ValidationException;
use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesRequest;
use MyParcelNL\Sdk\Services\CoreApi\HttpCapabilitiesClient;
use MyParcelNL\Sdk\Services\CoreApi\ShipmentApiFactory;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

final class HttpCapabilitiesClientTest extends TestCase
{
    /**
     * @var array<int, array{method: string, url: string, content_type: string[], user_agent: string[], body: string}>
     */
    private array $requests = [];

    /**
     * The request goes out as the body, and the client that the caller passes keeps its own User-Agent.
     */
    public function testGetCapabilitiesSendsTheRequestAndReadsTheResponse(): void
    {
        $client = $this->createClient(
            '{"results":[{"carrier":"POSTNL","packageTypes":["PACKAGE"],"deliveryTypes":["STANDARD_DELIVERY"],'
            . '"transactionTypes":["B2C"],"collo":{"max":5}}]}'
        );

        $response = $client->getCapabilities(CapabilitiesRequest::forCountry('NL')->withCarrier('POSTNL'));

        self::assertSame('POST', $this->requests[0]['method']);
        self::assertSame('https://api.myparcel.nl/shipments/capabilities', $this->requests[0]['url']);
        self::assertSame(['application/json;version=2.0'], $this->requests[0]['content_type']);
        self::assertSame(['User-Agent: MyPlugin/1.0 Magento/2.4'], $this->requests[0]['user_agent']);
        self::assertJsonStringEqualsJsonString('{"recipient":{"countryCode":"NL"},"carrier":"POSTNL"}', $this->requests[0]['body']);

        self::assertSame('POSTNL', $response->getCarrier());
        self::assertSame(['PACKAGE'], $response->getPackageTypes());
        self::assertSame(['STANDARD_DELIVERY'], $response->getDeliveryTypes());
        self::assertSame(['B2C'], $response->getTransactionTypes());
        self::assertSame(5, $response->getColloMax());
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

        $response = $client->getCapabilities(CapabilitiesRequest::forCountry('NL'));

        self::assertSame('FUTURE_CARRIER', $response->getCarrier());
        self::assertSame(['FUTURE_PACKAGE'], $response->getPackageTypes());
        self::assertSame(['requiresSignature', 'futureOption'], $response->getShipmentOptions());
    }

    public function testGetCapabilitiesRejectsACarrierThatTheSpecDoesNotKnowBeforeSending(): void
    {
        $client = $this->createClient('{"results":[]}');

        try {
            $client->getCapabilities(CapabilitiesRequest::forCountry('NL')->withCarrier('FUTURE_CARRIER'));
            self::fail('An unknown carrier must throw.');
        } catch (ValidationException) {
            self::assertSame([], $this->requests);
        }
    }

    public function testAnErrorResponseThrowsTheGeneratedException(): void
    {
        $client = $this->createClient('{"message":"Invalid country code"}', 422);

        try {
            $client->getCapabilities(CapabilitiesRequest::forCountry('XX'));
            self::fail('A 422 response must throw.');
        } catch (PostCapabilitiesClientErrorException $exception) {
            self::assertSame(422, $exception->getResponse()->getStatusCode());
        }
    }

    private function createClient(string $body, int $status = 200): HttpCapabilitiesClient
    {
        $httpClient = new MockHttpClient(function (string $method, string $url, array $options) use ($body, $status): MockResponse {
            $this->requests[] = [
                'method'       => $method,
                'url'          => $url,
                'content_type' => array_map(static fn(string $line): string => substr($line, 14), $options['normalized_headers']['content-type'] ?? []),
                'user_agent'   => $options['normalized_headers']['user-agent'] ?? [],
                'body'         => (string) ($options['body'] ?? ''),
            ];

            return new MockResponse($body, ['http_code' => $status, 'response_headers' => ['content-type' => 'application/json']]);
        });

        return new HttpCapabilitiesClient(null, ShipmentApiFactory::client('my-api-key', null, 'MyPlugin/1.0 Magento/2.4', [], $httpClient));
    }
}
