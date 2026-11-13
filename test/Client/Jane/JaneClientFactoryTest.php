<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Client\Jane;

use Jane\Component\OpenApiRuntime\Client\Plugin\ServerUrlHttpClient;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Client as CoreApiClient;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2Recipient;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesCarrier;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipment;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11Data;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItem;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemOptions;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemRecipient;
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

        $client->getShipments(queryParameters: ['reference_identifier' => 'order-1']);

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

    /**
     * The generated validator has a Choice constraint with the known values of `payment_status`. The
     * client reads without validation, so a value that the SDK does not know comes back as it is.
     */
    public function testReadsAnEnumValueThatTheSpecDoesNotHave(): void
    {
        self::assertSame('refunded', $this->readShipment(['payment_status' => 'refunded'])->paymentStatus);
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
            $client->postCapabilities(requestBody: $request);
            self::fail('A lower-case country code must fail validation.');
        } catch (ValidationException $exception) {
            self::assertStringContainsString('countryCode', (string) $exception->getViolationList());
        }

        self::assertSame([], $this->requests);
    }

    public function testAddsNoTimeoutToAnHttpClientThatTheCallerPasses(): void
    {
        $httpClient = new class implements HttpClientInterface {
            /** @var array<string, mixed>|null The options of the last `withOptions()` call. */
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
     * When a regeneration adds or removes a class that Client::create() instantiates, this test fails,
     * so the factory can follow. The test also checks the server URL and the JSON decode options,
     * which the factory copies, and the JSON encode options, which the factory replaces on purpose.
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

        self::assertStringContainsString(
            'JsonEncode()',
            $source,
            'The generated Client::create() encodes JSON with options. Merge them into the JsonEncode options of JaneClientFactory::createSerializer().'
        );
    }

    /**
     * The spec types a shipment option flag as a number, so the model holds a float. The API expects
     * the integers 0 and 1.
     *
     * @todo CLOUD-2372: remove this test when the spec types the flags as integer, and the client
     *       is generated again. The flag is then an int, and the encoder options can go.
     */
    public function testSendsAFloatWithAZeroFractionAsAnInteger(): void
    {
        $client = JaneClientFactory::coreApi('my-api-key', null, null, [], $this->mockHttpClient('{"data":{"shipments":[]}}'));

        $recipient             = new ShipmentPostShipmentsRequestV11DataShipmentsItemRecipient();
        $recipient->cc         = 'NL';
        $recipient->postalCode = '2132JE';
        $recipient->city       = 'Hoofddorp';
        $recipient->street     = 'Antareslaan';
        $recipient->number     = '31';
        $recipient->person     = 'Test Person';
        // Jane sends an unset field without a type as null (janephp/janephp#1141), and the validation rejects it.
        unset($recipient->email);

        $options              = new ShipmentPostShipmentsRequestV11DataShipmentsItemOptions();
        $options->packageType = 1;
        $options->signature   = 1.0;

        $shipment                      = new ShipmentPostShipmentsRequestV11DataShipmentsItem();
        $shipment->referenceIdentifier = 'order-1';
        $shipment->carrier             = 1;
        $shipment->recipient           = $recipient;
        $shipment->options             = $options;

        $data            = new ShipmentPostShipmentsRequestV11Data();
        $data->shipments = [$shipment];
        $request         = new ShipmentPostShipmentsRequestV11();
        $request->data   = $data;

        $client->postShipments(requestBody: $request);

        self::assertMatchesRegularExpression('/"signature":1[,}]/', (string) $this->requests[0]['options']['body']);
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
