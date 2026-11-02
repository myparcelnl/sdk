<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Client\Jane;

use Jane\Component\OpenApiRuntime\Client\Plugin\ServerUrlHttpClient;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Client as CoreApiClient;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2Recipient;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesCarrier;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipment;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\JaneObjectNormalizer as CoreApiNormalizer;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Client\FormEncoder as CoreApiFormEncoder;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Normalizer\ValidationException;
use MyParcelNL\Sdk\Client\Jane\JaneClientFactory;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use ReflectionMethod;
use ReflectionProperty;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;

final class JaneClientFactoryTest extends TestCase
{
    /**
     * @var array<int, array{method: string, url: string, options: array<string, mixed>}>
     */
    private array $requests = [];

    public function testSendsTheApiKeyTheUserAgentAndTheHeaders(): void
    {
        $client = JaneClientFactory::coreApi(
            'my-api-key',
            null,
            'MyParcelNL-SDK/12.0.0',
            ['x-dmp-test-feature' => 'enabled'],
            $this->mockHttpClient(self::shipmentsResponse([]))
        );

        $client->getShipments(['reference_identifier' => 'order-1']);

        self::assertCount(1, $this->requests);
        self::assertSame('GET', $this->requests[0]['method']);
        self::assertSame('https://api.myparcel.nl/shipments?reference_identifier=order-1', $this->requests[0]['url']);
        self::assertSame('Bearer ' . base64_encode('my-api-key'), $this->header('authorization'));
        self::assertSame('MyParcelNL-SDK/12.0.0', $this->header('user-agent'));
        self::assertSame('enabled', $this->header('x-dmp-test-feature'));
    }

    public function testSendsTheRequestsToTheHostOverride(): void
    {
        $client = JaneClientFactory::coreApi(
            'my-api-key',
            'https://api.acceptance.myparcel.nl',
            null,
            [],
            $this->mockHttpClient(self::shipmentsResponse([]))
        );

        $client->getShipments();

        self::assertSame('https://api.acceptance.myparcel.nl/shipments', $this->requests[0]['url']);
    }

    public function testReadsAResponseWithAFieldThatTheSpecDoesNotHave(): void
    {
        $shipment = $this->readShipment(['id' => 1, 'field_the_api_adds_later' => 'value']);

        self::assertSame(1, $shipment->id);
    }

    public function testKeepsACarrierIdThatTheSpecDoesNotHave(): void
    {
        self::assertSame(RefTypesCarrier::Postnl, $this->readShipment(['carrier_id' => 1])->carrierId);
        self::assertSame(999, $this->readShipment(['carrier_id' => 999])->carrierId);
    }

    public function testReadsTheRegionWereld(): void
    {
        self::assertSame('Wereld', $this->readShipment(['region' => 'Wereld'])->region);
        self::assertSame('EU', $this->readShipment(['region' => 'EU'])->region);
    }

    public function testTellsNullFromAFieldThatTheResponseDoesNotHave(): void
    {
        $shipment = $this->readShipment(['id' => 1, 'parent_id' => null]);

        self::assertTrue((new ReflectionProperty($shipment, 'parentId'))->isInitialized($shipment));
        self::assertNull($shipment->parentId);
        self::assertFalse((new ReflectionProperty($shipment, 'pickup'))->isInitialized($shipment));
    }

    public function testValidatesARequestBeforeItGoesOut(): void
    {
        $client = JaneClientFactory::coreApi('my-api-key', null, null, [], $this->mockHttpClient('{}'));

        $recipient              = new CapabilitiesPostCapabilitiesRequestV2Recipient();
        $recipient->countryCode = 'nl';
        $request                = new CapabilitiesPostCapabilitiesRequestV2();
        $request->recipient     = $recipient;

        try {
            $client->postCapabilities($request);
            self::fail('A lower-case country code must fail validation.');
        } catch (ValidationException $exception) {
            self::assertStringContainsString('countryCode', (string) $exception->getViolationList());
        }

        self::assertSame([], $this->requests);
    }

    public function testKeepsTheOptionsOfAnHttpClientThatTheCallerPasses(): void
    {
        $httpClient = new class implements HttpClientInterface {
            /** @var array<string, mixed>|null The options of the last withOptions() call. */
            public ?array $options = null;

            public function request(string $method, string $url, array $options = []): ResponseInterface
            {
                throw new \LogicException('The test sends no request.');
            }

            public function stream(ResponseInterface|iterable $responses, ?float $timeout = null): ResponseStreamInterface
            {
                throw new \LogicException('The test sends no request.');
            }

            public function withOptions(array $options): static
            {
                $this->options = $options;

                return $this;
            }
        };

        JaneClientFactory::coreApi('my-api-key', null, null, ['x-dmp-example' => 'true'], $httpClient);

        self::assertNotNull($httpClient->options);
        self::assertArrayNotHasKey('timeout', $httpClient->options, 'The factory must not replace the timeout of a client that the caller passes.');
        self::assertSame(['x-dmp-example' => 'true'], $httpClient->options['headers']);
    }

    /**
     * JaneClientFactory builds the same parts as the generated Client::create(). When a regeneration
     * adds or removes a part there, this test fails, so the factory can follow. The test also checks
     * the server URL and the JSON decode options, which the factory copies.
     */
    public function testBuildsTheSamePartsAsTheGeneratedCreateMethod(): void
    {
        $create = new ReflectionMethod(CoreApiClient::class, 'create');
        $lines  = file((string) $create->getFileName());
        $source = implode('', array_slice($lines, $create->getStartLine() - 1, $create->getEndLine() - $create->getStartLine() + 1));

        preg_match_all('/new \\\\([\w\\\\]+)\(/', $source, $matches);

        self::assertSame(
            [
                ServerUrlHttpClient::class,
                ArrayDenormalizer::class,
                CoreApiNormalizer::class,
                Serializer::class,
                JsonEncoder::class,
                JsonEncode::class,
                JsonDecode::class,
                CoreApiFormEncoder::class,
            ],
            $matches[1],
            'The generated Client::create() changed. Update JaneClientFactory to build the same parts.'
        );

        preg_match("/ServerUrlHttpClient\\('([^']+)'\\)/", $source, $serverUrl);

        self::assertSame(
            JaneClientFactory::CORE_API_HOST,
            rtrim($serverUrl[1] ?? '', '/'),
            'The generated Client::create() uses another server URL. Update JaneClientFactory::CORE_API_HOST.'
        );
        self::assertStringContainsString(
            "JsonDecode(['json_decode_associative' => true])",
            $source,
            'The generated Client::create() decodes JSON with other options. Update JaneClientFactory::createSerializer().'
        );
    }

    /**
     * @param array<string, mixed> $shipment
     */
    private function readShipment(array $shipment): ShipmentDefsShipment
    {
        $client = JaneClientFactory::coreApi('my-api-key', null, null, [], $this->mockHttpClient(self::shipmentsResponse([$shipment])));

        return $client->getShipments()->data->shipments[0];
    }

    private function mockHttpClient(string $body): MockHttpClient
    {
        return new MockHttpClient(function (string $method, string $url, array $options) use ($body): MockResponse {
            $this->requests[] = ['method' => $method, 'url' => $url, 'options' => $options];

            return new MockResponse($body, ['http_code' => 200, 'response_headers' => ['content-type' => 'application/json']]);
        });
    }

    private function header(string $name): ?string
    {
        $line = $this->requests[0]['options']['normalized_headers'][$name][0] ?? null;

        return null === $line ? null : substr($line, strlen($name) + 2);
    }

    /**
     * @param array<int, array<string, mixed>> $shipments
     */
    private static function shipmentsResponse(array $shipments): string
    {
        return json_encode(['data' => ['shipments' => $shipments, 'results' => count($shipments)]], JSON_THROW_ON_ERROR);
    }
}
