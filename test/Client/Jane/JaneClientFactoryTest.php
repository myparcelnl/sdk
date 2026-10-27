<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Client\Jane;

use Jane\Component\OpenApiRuntime\Client\Plugin\ServerUrlHttpClient;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Client as CoreApiClient;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\DeleteShipmentsClientErrorException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2Recipient;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesCarrier;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipment;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\JaneObjectNormalizer as CoreApiNormalizer;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Client\FormEncoder as CoreApiFormEncoder;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Normalizer\ValidationException;
use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Client as IamApiClient;
use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Normalizer\JaneObjectNormalizer as IamApiNormalizer;
use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Runtime\Client\FormEncoder as IamApiFormEncoder;
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
use Throwable;
use TypeError;

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

    public function testAProblemJsonErrorThrowsTheClientErrorException(): void
    {
        $client = JaneClientFactory::coreApi('my-api-key', null, null, [], $this->mockHttpClient(
            '{"type":"urn:problem:permission-denied","title":"Permission denied","status":401,"request_id":"1"}',
            401,
            'application/problem+json'
        ));

        try {
            $client->deleteShipments('1');
            self::fail('A 401 response must throw.');
        } catch (DeleteShipmentsClientErrorException $exception) {
            self::assertSame(401, $exception->getResponse()->getStatusCode());
            self::assertSame('Permission denied', $exception->getCommonResponsesProblem()->title);
        }

        self::assertSame('Accept: application/json, application/problem+json', $this->requests[0]['options']['normalized_headers']['accept'][0]);
    }

    /**
     * Jane throws a TypeError for an `application/json` error of an operation that also lists
     * `application/problem+json`. ProblemJsonAcceptHttpClient prevents that error format.
     */
    public function testJaneStillThrowsATypeErrorForAJsonErrorOfAProblemJsonOperation(): void
    {
        $client = JaneClientFactory::coreApi('my-api-key', null, null, [], $this->mockHttpClient(
            '{"message":"Permission denied","request_id":"1","status_code":401,"errors":[]}',
            401
        ));

        $thrown = null;

        try {
            $client->deleteShipments('1');
        } catch (Throwable $thrown) {
        }

        self::assertInstanceOf(
            TypeError::class,
            $thrown,
            'Jane now reads an application/json error. Remove ProblemJsonAcceptHttpClient and this test.'
        );
    }

    /**
     * JaneClientFactory builds the same parts as the generated Client::create(). When a regeneration
     * adds or removes a part there, this test fails, so the factory can follow.
     *
     * @dataProvider provideGeneratedClients
     *
     * @param class-string $clientClass
     * @param class-string $normalizerClass
     * @param class-string $formEncoderClass
     */
    public function testBuildsTheSamePartsAsTheGeneratedCreateMethod(
        string $clientClass,
        string $normalizerClass,
        string $formEncoderClass
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
    }

    /**
     * @return array<string, array{class-string, class-string, class-string}>
     */
    public static function provideGeneratedClients(): array
    {
        return [
            'Core API' => [CoreApiClient::class, CoreApiNormalizer::class, CoreApiFormEncoder::class],
            'IAM API'  => [IamApiClient::class, IamApiNormalizer::class, IamApiFormEncoder::class],
        ];
    }

    /**
     * @param array<string, mixed> $shipment
     */
    private function readShipment(array $shipment): ShipmentDefsShipment
    {
        $client = JaneClientFactory::coreApi('my-api-key', null, null, [], $this->mockHttpClient(self::shipmentsResponse([$shipment])));

        return $client->getShipments()->data->shipments[0];
    }

    private function mockHttpClient(string $body, int $status = 200, string $contentType = 'application/json'): MockHttpClient
    {
        return new MockHttpClient(function (string $method, string $url, array $options) use ($body, $status, $contentType): MockResponse {
            $this->requests[] = ['method' => $method, 'url' => $url, 'options' => $options];

            return new MockResponse($body, ['http_code' => $status, 'response_headers' => ['content-type' => $contentType]]);
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
