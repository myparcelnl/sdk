<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Client\Jane;

use Jane\Component\OpenApiRuntime\Client\Plugin\ServerUrlHttpClient;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Client as CoreApiClient;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\DeleteShipmentsClientErrorException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2Recipient;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesUserError;
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
use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Client as IamApiClient;
use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Normalizer\JaneObjectNormalizer as IamApiNormalizer;
use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Runtime\Client\FormEncoder as IamApiFormEncoder;
use MyParcelNL\Sdk\Client\Jane\JaneClientFactory;
use MyParcelNL\Sdk\Test\Bootstrap\MocksHttpClient;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use ReflectionMethod;
use ReflectionProperty;
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
    use MocksHttpClient;

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

    public function testSendsTheIamRequestsWithTheApiKeyTheUserAgentAndTheHostOverride(): void
    {
        $client = JaneClientFactory::iamApi(
            'my-api-key',
            'https://iam.acceptance.myparcel.nl',
            'MyParcelNL-SDK/12.0.0',
            ['x-dmp-test-feature' => 'enabled'],
            $this->mockHttpClient('{"type":"SHOP"}')
        );

        $client->getWhoami();

        self::assertSame('https://iam.acceptance.myparcel.nl/whoami', $this->requests[0]['url']);
        self::assertSame('Bearer ' . base64_encode('my-api-key'), $this->header('authorization'));
        self::assertSame('MyParcelNL-SDK/12.0.0', $this->header('user-agent'));
        self::assertSame('enabled', $this->header('x-dmp-test-feature'));
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
     * When a regeneration adds or removes a class that Client::create() instantiates, this test fails,
     * so the factory can follow. The test also checks the server URL and the JSON decode options,
     * which the factory copies, and the JSON encode options, which the factory replaces on purpose.
     *
     * @dataProvider provideGeneratedClients
     *
     * @param class-string $clientClass
     * @param class-string $normalizerClass
     * @param class-string $formEncoderClass
     * @param string       $host             The host constant of the factory for this client
     */
    public function testBuildsTheSamePartsAsTheGeneratedCreateMethod(
        string $clientClass,
        string $normalizerClass,
        string $formEncoderClass,
        string $host
    ): void {
        $create = new ReflectionMethod($clientClass, 'create');
        $lines  = file((string) $create->getFileName());
        $source = implode('', array_slice($lines, $create->getStartLine() - 1, $create->getEndLine() - $create->getStartLine() + 1));

        preg_match_all('/new \\\\([\w\\\\]+)\(/', $source, $matches);

        self::assertSame(
            [
                ServerUrlHttpClient::class,
                ArrayDenormalizer::class,
                $normalizerClass,
                Serializer::class,
                JsonEncoder::class,
                JsonEncode::class,
                JsonDecode::class,
                $formEncoderClass,
            ],
            $matches[1],
            'The generated Client::create() changed. Update JaneClientFactory to build the same parts.'
        );

        preg_match("/ServerUrlHttpClient\\('([^']+)'\\)/", $source, $serverUrl);

        self::assertSame(
            $host,
            rtrim($serverUrl[1] ?? '', '/'),
            'The generated Client::create() uses another server URL. Update the host constant of JaneClientFactory.'
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
     * @return array<string, array{class-string, class-string, class-string, string}>
     */
    public static function provideGeneratedClients(): array
    {
        return [
            'Core API' => [CoreApiClient::class, CoreApiNormalizer::class, CoreApiFormEncoder::class, JaneClientFactory::CORE_API_HOST],
            'IAM API'  => [IamApiClient::class, IamApiNormalizer::class, IamApiFormEncoder::class, JaneClientFactory::IAM_API_HOST],
        ];
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
     * A request body with a `+json` content type with parameters went out without JSON encoding
     * before Jane 8.1.1 (janephp/janephp#1132).
     */
    public function testPostShipmentsSendsAJsonBodyWithTheVersionedContentType(): void
    {
        $client = JaneClientFactory::coreApi('my-api-key', null, null, [], $this->mockHttpClient('{"data":{"shipments":[]}}'));

        $client->postShipments(requestBody: self::shipmentRequest('order-1'));

        self::assertSame('application/vnd.shipment+json;version=1.1', $this->header('content-type'));
        self::assertSame('order-1', json_decode((string) $this->requests[0]['options']['body'], true, 512, JSON_THROW_ON_ERROR)['data']['shipments'][0]['reference_identifier']);
    }

    /**
     * The validators of the shipment requests referred to a constraint class without its namespace
     * before Jane 8.1.1 (janephp/janephp#1134): each such request failed with "Class not found".
     */
    public function testValidatesAShipmentRequestBeforeItGoesOut(): void
    {
        $client  = JaneClientFactory::coreApi('my-api-key', null, null, [], $this->mockHttpClient('{}'));
        $request = self::shipmentRequest('order-1');
        unset($request->data->shipments[0]->recipient);

        try {
            $client->postShipments(requestBody: $request);
            self::fail('A shipment without a recipient must fail validation.');
        } catch (ValidationException $exception) {
            self::assertStringContainsString('recipient', (string) $exception->getViolationList());
        }

        self::assertSame([], $this->requests);
    }

    /**
     * The spec describes both error content types for four operations, such as `deleteShipments`.
     * The generated exception of such an operation accepted only the `application/problem+json`
     * model before janephp/janephp#1139, so an `application/json` error threw a BadResponseException.
     */
    public function testAJsonErrorOfAProblemJsonOperationThrowsTheClientErrorException(): void
    {
        $client = JaneClientFactory::coreApi('my-api-key', null, null, [], $this->mockHttpClient(
            '{"message":"Permission denied","request_id":"1","status_code":401,"errors":[]}',
            401
        ));

        try {
            $client->deleteShipments(ids: '1');
            self::fail('A 401 response must throw.');
        } catch (DeleteShipmentsClientErrorException $exception) {
            $error = $exception->getCommonResponsesProblem();

            self::assertInstanceOf(CommonResponsesUserError::class, $error);
            self::assertSame('Permission denied', $error->message);
        }
    }

    public function testAProblemJsonErrorThrowsTheClientErrorException(): void
    {
        $client = JaneClientFactory::coreApi('my-api-key', null, null, [], $this->mockHttpClient(
            '{"type":"urn:problem:permission-denied","title":"Permission denied","status":401,"request_id":"1"}',
            401,
            'application/problem+json'
        ));

        try {
            $client->deleteShipments(ids: '1');
            self::fail('A 401 response must throw.');
        } catch (DeleteShipmentsClientErrorException $exception) {
            self::assertSame(401, $exception->getResponse()->getStatusCode());
            self::assertSame('Permission denied', $exception->getCommonResponsesProblem()->title);
        }
    }

    /**
     * A shipment request with the fields that the spec requires.
     */
    private static function shipmentRequest(string $referenceIdentifier): ShipmentPostShipmentsRequestV11
    {
        $recipient             = new ShipmentPostShipmentsRequestV11DataShipmentsItemRecipient();
        $recipient->cc         = 'NL';
        $recipient->postalCode = '2132JE';
        $recipient->city       = 'Hoofddorp';
        $recipient->street     = 'Antareslaan';
        $recipient->number     = '31';
        $recipient->person     = 'Test Person';

        $options              = new ShipmentPostShipmentsRequestV11DataShipmentsItemOptions();
        $options->packageType = 1;

        $shipment                      = new ShipmentPostShipmentsRequestV11DataShipmentsItem();
        $shipment->referenceIdentifier = $referenceIdentifier;
        $shipment->carrier             = 1;
        $shipment->recipient           = $recipient;
        $shipment->options             = $options;

        $data            = new ShipmentPostShipmentsRequestV11Data();
        $data->shipments = [$shipment];
        $request         = new ShipmentPostShipmentsRequestV11();
        $request->data   = $data;

        return $request;
    }

    /**
     * @param array<string, mixed> $shipment
     */
    private function readShipment(array $shipment): ShipmentDefsShipment
    {
        $client = JaneClientFactory::coreApi('my-api-key', null, null, [], $this->mockHttpClient(self::shipmentsResponse([$shipment])));

        return $client->getShipments()->data->shipments[0];
    }

    /**
     * Before janephp/janephp#1141, the normalizer sent a property without a type, such as the email of
     * a recipient, as null when nobody set it, and the validation rejected the request.
     */
    public function testLeavesOutAPropertyWithoutATypeThatNobodySet(): void
    {
        $recipient     = new ShipmentPostShipmentsRequestV11DataShipmentsItemRecipient();
        $recipient->cc = 'NL';

        self::assertSame(['cc' => 'NL'], self::coreApiSerializer()->normalize($recipient, 'json', ['skip_validation' => true]));
    }

    /**
     * Before janephp/janephp#1150, the normalizer iterated over a nullable list that the API sent as
     * null, and PHP warned.
     */
    public function testNormalizesANullableListThatTheApiSentAsNull(): void
    {
        $shipment = self::coreApiSerializer()->denormalize(['id' => 1, 'shipped_items' => null], ShipmentDefsShipment::class, 'json');
        $array    = json_decode(json_encode(self::coreApiSerializer()->normalize($shipment, 'json', ['skip_validation' => true])), true);

        self::assertNull($shipment->shippedItems);
        self::assertArrayHasKey('shipped_items', $array);
        self::assertNull($array['shipped_items']);
    }

    private static function coreApiSerializer(): Serializer
    {
        return new Serializer([new ArrayDenormalizer(), new CoreApiNormalizer()]);
    }

    /**
     * @param array<int, array<string, mixed>> $shipments
     */
    private static function shipmentsResponse(array $shipments): string
    {
        return json_encode(['data' => ['shipments' => $shipments, 'results' => count($shipments)]], JSON_THROW_ON_ERROR);
    }
}
