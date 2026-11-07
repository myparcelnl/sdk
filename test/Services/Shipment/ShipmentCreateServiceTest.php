<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Shipment;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\PostShipmentsUnprocessableEntityException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentPackageType;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesCarrier;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItem as ShipmentItem;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Normalizer\ValidationException;
use MyParcelNL\Sdk\Client\Jane\CoreApiModels;
use MyParcelNL\Sdk\Client\Jane\JaneClientFactory;
use MyParcelNL\Sdk\Services\Shipment\ShipmentCreateService;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;

final class ShipmentCreateServiceTest extends TestCase
{
    /**
     * @var array<int, array{method: string, url: string, headers: array<string, string[]>, body: array<string, mixed>}>
     */
    private array $requests = [];

    public function testCreateThrowsWhenNoShipmentsWereAdded(): void
    {
        $service = $this->createService(self::createdResponse([]));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('At least one shipment must be added before calling create().');

        $service->create([]);
    }

    public function testCreateThrowsForAnItemThatIsNotTheGeneratedModel(): void
    {
        $service = $this->createService(self::createdResponse([]));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Each shipment must be a ' . ShipmentItem::class . ', got array.');

        $service->create([['carrier' => 1]]);
    }

    public function testCreateThrowsBeforeSendingMoreThanHundredShipments(): void
    {
        $service   = $this->createService(self::createdResponse([]));
        $shipments = [];

        for ($i = 0; $i < 101; $i++) {
            $shipments[] = self::shipment("order-{$i}");
        }

        try {
            $service->create($shipments);
            self::fail('More than 100 shipments must throw.');
        } catch (ValidationException) {
            self::assertSame([], $this->requests);
        }
    }

    public function testCreateSendsTheShipmentsTheLabelFormatAndTheUserAgent(): void
    {
        $service = $this->createService(self::createdResponse([
            ['id' => 1001, 'reference_identifier' => 'order-1'],
            ['id' => 1002, 'reference_identifier' => 'order-2'],
        ]));
        $service->setUserAgentForProposition('Magento', '2.4.7');

        $result = $service->create([self::shipment('order-1'), self::shipment('order-2')], 'A4', '1;2');

        self::assertSame([1001 => 'order-1', 1002 => 'order-2'], $result);
        self::assertSame('POST', $this->requests[0]['method']);
        self::assertSame('https://api.myparcel.nl/shipments?format=A4&positions=1%3B2', $this->requests[0]['url']);
        self::assertSame(['application/vnd.shipment+json;version=1.1'], $this->requests[0]['headers']['content-type']);
        self::assertSame(['MyPlugin/1.0'], $this->requests[0]['headers']['user-agent']);
        self::assertSame($service->getUserAgentHeader(), $this->requests[0]['body']['data']['user_agent']);
        self::assertSame(['order-1', 'order-2'], array_column($this->requests[0]['body']['data']['shipments'], 'reference_identifier'));
    }

    public function testCreateSendsTheOtherQueryParametersOfTheSpec(): void
    {
        $this->createService(self::createdResponse([['id' => 1, 'reference_identifier' => 'order-1']]))
            ->create([self::shipment('order-1')], 'A4', null, ['send_return_mail' => 0]);

        self::assertSame('https://api.myparcel.nl/shipments?format=A4&send_return_mail=0', $this->requests[0]['url']);
    }

    public function testCreateRejectsAQueryParameterThatTheSpecDoesNotHave(): void
    {
        $service = $this->createService(self::createdResponse([]));

        try {
            $service->create([self::shipment('order-1')], null, null, ['send_mail' => 0]);
            self::fail('An unknown query parameter must throw.');
        } catch (UndefinedOptionsException) {
            self::assertSame([], $this->requests);
        }
    }

    public function testCreateSendsAnEnumCaseAsItsValue(): void
    {
        $shipment                       = self::shipment('order-enum');
        $shipment->carrier              = RefTypesCarrier::Postnl;
        $shipment->options->packageType = RefShipmentPackageType::Package;

        $result = $this->createService(self::createdResponse([['id' => 3001, 'reference_identifier' => 'order-enum']]))
            ->create([$shipment]);

        self::assertSame([3001 => 'order-enum'], $result);
        self::assertSame(1, $this->requests[0]['body']['data']['shipments'][0]['carrier']);
        self::assertSame(['package_type' => 1], $this->requests[0]['body']['data']['shipments'][0]['options']);
    }

    /**
     * An unset field without a type, such as the email, stays out of the request.
     */
    public function testCreateSendsARecipientWithoutEmail(): void
    {
        $this->createService(self::createdResponse([['id' => 1, 'reference_identifier' => 'order-1']]))
            ->create([self::shipment('order-1')]);

        self::assertArrayNotHasKey('email', $this->requests[0]['body']['data']['shipments'][0]['recipient']);
        self::assertSame('Hoofddorp', $this->requests[0]['body']['data']['shipments'][0]['recipient']['city']);
    }

    public function testCreateAssignsMissingReferenceIdentifiers(): void
    {
        $shipment = self::shipment();

        $result = $this->createService(self::createdResponse([['id' => 2001]]))->create([$shipment]);

        self::assertStringStartsWith('sdk_', (string) $shipment->referenceIdentifier);
        self::assertSame($shipment->referenceIdentifier, $this->requests[0]['body']['data']['shipments'][0]['reference_identifier']);
        self::assertSame([2001 => $shipment->referenceIdentifier], $result);
    }

    public function testCreateChangesNothingElseOnTheShipment(): void
    {
        $shipment = self::shipment('order-1');

        $this->createService(self::createdResponse([['id' => 1]]))->create([$shipment]);

        self::assertNull($shipment->recipient->email);
        self::assertSame('order-1', $shipment->referenceIdentifier);
    }

    public function testCreateFallsBackToRequestReferenceWhenResponseReferenceIdentifierIsMissing(): void
    {
        $result = $this->createService(self::createdResponse([['id' => 7001]]))->create([self::shipment('order-wrapper-ref')]);

        self::assertSame([7001 => 'order-wrapper-ref'], $result);
    }

    public function testCreateThrowsWhenResponseDataIsMissing(): void
    {
        $service = $this->createService('{}');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('postShipments() returned a response without data.');

        $service->create([self::shipment('order-1')]);
    }

    public function testCreateThrowsWhenResponseDataIsNull(): void
    {
        $service = $this->createService('{"data":null}');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('postShipments() returned a response without data.');

        $service->create([self::shipment('order-1')]);
    }

    public function testCreateReturnsEmptyMappingWhenResponseHasNoShipments(): void
    {
        self::assertSame([], $this->createService(self::createdResponse([]))->create([self::shipment('order-1')]));
    }

    public function testCreateSkipsShipmentsWithoutIdInResponse(): void
    {
        $result = $this->createService(self::createdResponse([
            ['reference_identifier' => 'order-1'],
            ['id' => 8002, 'reference_identifier' => 'order-2'],
        ]))->create([self::shipment('order-1'), self::shipment('order-2')]);

        self::assertSame([8002 => 'order-2'], $result);
    }

    /**
     * The spec documents both error formats for `POST /shipments`. The generated exception reads both.
     *
     * @return array<string, array{string, string}>
     */
    public static function provideErrorResponses(): array
    {
        return [
            'application/json'         => ['{"message":"Invalid shipment","request_id":"1","status_code":422,"errors":[]}', 'application/json'],
            'application/problem+json' => ['{"type":"urn:problem:validation","title":"Invalid shipment","status":422}', 'application/problem+json'],
        ];
    }

    /**
     * @dataProvider provideErrorResponses
     */
    public function testAnErrorResponseThrowsTheGeneratedException(string $body, string $contentType): void
    {
        $service = $this->createService($body, 422, $contentType);

        try {
            $service->create([self::shipment('order-1')]);
            self::fail('A 422 response must throw.');
        } catch (PostShipmentsUnprocessableEntityException $exception) {
            self::assertSame(422, $exception->getResponse()->getStatusCode());
        }
    }

    public function testCreateThrowsBeforeSendingAShipmentWithoutRecipient(): void
    {
        $service  = $this->createService(self::createdResponse([]));
        $shipment = self::shipment('order-1');
        unset($shipment->recipient);

        try {
            $service->create([$shipment]);
            self::fail('A shipment without recipient must throw.');
        } catch (ValidationException) {
            self::assertSame([], $this->requests);
        }
    }

    /**
     * A shipment with the fields that the spec requires.
     */
    private static function shipment(?string $referenceIdentifier = null): ShipmentItem
    {
        return CoreApiModels::toModel(array_filter([
            'reference_identifier' => $referenceIdentifier,
            'carrier'              => 1,
            'options'              => ['package_type' => 1],
            'recipient'            => [
                'cc'          => 'NL',
                'postal_code' => '2132JE',
                'city'        => 'Hoofddorp',
                'street'      => 'Antareslaan',
                'number'      => '31',
                'person'      => 'Test Person',
            ],
        ], static fn (mixed $value): bool => null !== $value), ShipmentItem::class);
    }

    private function createService(string $body, int $status = 200, string $contentType = 'application/json;charset=utf-8;version=1.2'): ShipmentCreateService
    {
        $httpClient = new MockHttpClient(function (string $method, string $url, array $options) use ($body, $status, $contentType): MockResponse {
            $headers = [];
            foreach ($options['normalized_headers'] as $name => $lines) {
                $headers[$name] = array_map(static fn (string $line): string => substr($line, strlen($name) + 2), $lines);
            }

            $this->requests[] = [
                'method'  => $method,
                'url'     => $url,
                'headers' => $headers,
                'body'    => json_decode((string) $options['body'], true, 512, JSON_THROW_ON_ERROR),
            ];

            return new MockResponse($body, ['http_code' => $status, 'response_headers' => ['content-type' => $contentType]]);
        });

        return new ShipmentCreateService($this->getApiKey(), JaneClientFactory::coreApi('my-api-key', null, 'MyPlugin/1.0', [], $httpClient));
    }

    /**
     * @param array<int, array<string, mixed>> $shipments
     */
    private static function createdResponse(array $shipments): string
    {
        return json_encode(['data' => ['shipments' => $shipments]], JSON_THROW_ON_ERROR);
    }
}
