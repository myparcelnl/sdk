<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Client\Generated\Jane\CoreApi;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\DeleteShipmentsClientErrorException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2Recipient;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesUserError;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesCarrier;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesIntBoolean;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipment;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11Data;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItem;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemOptions;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemRecipient;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\JaneObjectNormalizer as CoreApiNormalizer;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Normalizer\ValidationException;
use MyParcelNL\Sdk\Client\Jane\JaneClientFactory;
use MyParcelNL\Sdk\Test\Bootstrap\BuildsShipmentsResponse;
use MyParcelNL\Sdk\Test\Bootstrap\MocksHttpClient;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use ReflectionProperty;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Guards the generated Core API client against a regression of the Jane generator or of the spec.
 *
 * These tests run generated code, not code of the SDK. Each one covers a behaviour that the SDK
 * relies on. Most name the Jane issue that broke it before.
 */
final class GeneratedCoreApiClientTest extends TestCase
{
    use BuildsShipmentsResponse;
    use MocksHttpClient;

    /**
     * The client reads a response without validation, so a field or an enum value that the spec does
     * not have does not break a read.
     */
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

    /**
     * A flag such as `signature` goes out as the integer 0 or 1. This guards against a spec that
     * types the flags as `number` again.
     */
    public function testSendsAFlagAsAnInteger(): void
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
        $options->signature   = RefTypesIntBoolean::True;

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
     * A request body with a `+json` content type with parameters is JSON encoded
     * (janephp/janephp#1132).
     */
    public function testPostShipmentsSendsAJsonBodyWithTheVersionedContentType(): void
    {
        $client = JaneClientFactory::coreApi('my-api-key', null, null, [], $this->mockHttpClient('{"data":{"shipments":[]}}'));

        $client->postShipments(requestBody: self::shipmentRequest('order-1'));

        self::assertSame('application/vnd.shipment+json;version=1.1', $this->header('content-type'));
        self::assertSame('order-1', json_decode((string) $this->requests[0]['options']['body'], true, 512, JSON_THROW_ON_ERROR)['data']['shipments'][0]['reference_identifier']);
    }

    /**
     * The generated validator of a shipment request finds its constraint classes, so a request
     * that does not match the spec throws before it goes out (janephp/janephp#1134).
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
     * The spec describes both error content types for operations such as `deleteShipments`.
     * The generated exception accepted only the `application/problem+json` model before
     * janephp/janephp#1139, so an `application/json` error threw a `TypeError`.
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

    /**
     * An `application/problem+json` error of the same operation throws the same exception.
     */
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
     * A property without a type that nobody set, such as the email of a recipient, stays out of
     * the request (janephp/janephp#1141).
     */
    public function testLeavesOutAPropertyWithoutATypeThatNobodySet(): void
    {
        $recipient     = new ShipmentPostShipmentsRequestV11DataShipmentsItemRecipient();
        $recipient->cc = 'NL';

        self::assertSame(['cc' => 'NL'], self::coreApiSerializer()->normalize($recipient, 'json', ['skip_validation' => true]));
    }

    /**
     * A nullable list that the API sent as null is null in the model and in the encoded array
     * (janephp/janephp#1150).
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
     * @param array<string, mixed> $shipment
     */
    private function readShipment(array $shipment): ShipmentDefsShipment
    {
        $client = JaneClientFactory::coreApi('my-api-key', null, null, [], $this->mockHttpClient(self::shipmentsResponse([$shipment])));

        return $client->getShipments()->data->shipments[0];
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
}
