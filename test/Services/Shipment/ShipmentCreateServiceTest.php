<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Shipment;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\PostShipmentsClientErrorException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedCarrierV2;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentPackageTypeV2;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Normalizer\ValidationException;
use MyParcelNL\Sdk\Client\Jane\JaneClientFactory;
use MyParcelNL\Sdk\Collection\ShipmentCollection;
use MyParcelNL\Sdk\Model\Shipment\Shipment;
use MyParcelNL\Sdk\Services\Shipment\ShipmentCreateService;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

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

        $service->create(new ShipmentCollection());
    }

    public function testCreateThrowsBeforeSendingMoreThanHundredShipments(): void
    {
        $service    = $this->createService(self::createdResponse([]));
        $collection = new ShipmentCollection();

        for ($i = 0; $i < 101; $i++) {
            $collection->push(self::shipment("order-{$i}"));
        }

        try {
            $service->create($collection);
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

        $result = $service->create(
            new ShipmentCollection([
                self::shipment('order-1'),
                self::shipment('order-2'),
            ]),
            'A4',
            '1;2'
        );

        self::assertSame([1001 => 'order-1', 1002 => 'order-2'], $result);
        self::assertSame('POST', $this->requests[0]['method']);
        self::assertSame('https://api.myparcel.nl/shipments?format=A4&positions=1%3B2', $this->requests[0]['url']);
        self::assertSame(['application/vnd.shipment+json;version=1.1'], $this->requests[0]['headers']['content-type']);
        self::assertSame([$service->getUserAgentHeader()], $this->requests[0]['headers']['user-agent']);
        self::assertSame($service->getUserAgentHeader(), $this->requests[0]['body']['data']['user_agent']);
        self::assertSame(['order-1', 'order-2'], array_column($this->requests[0]['body']['data']['shipments'], 'reference_identifier'));
    }

    public function testCreateSendsTheCarrierAndThePackageTypeAsIds(): void
    {
        $shipment = self::shipment('order-enum')
            ->setCarrier(RefCapabilitiesSharedCarrierV2::Postnl)
            ->withPackageType(RefShipmentPackageTypeV2::Package);

        $result = $this->createService(self::createdResponse([['id' => 3001, 'reference_identifier' => 'order-enum']]))
            ->create(new ShipmentCollection([$shipment]));

        self::assertSame([3001 => 'order-enum'], $result);
        self::assertSame(1, $this->requests[0]['body']['data']['shipments'][0]['carrier']);
        self::assertSame(['package_type' => 1], $this->requests[0]['body']['data']['shipments'][0]['options']);
    }

    public function testCreateSendsARecipientWithoutEmail(): void
    {
        $this->createService(self::createdResponse([['id' => 1, 'reference_identifier' => 'order-1']]))
            ->create(new ShipmentCollection([self::shipment('order-1')]));

        self::assertArrayNotHasKey('email', $this->requests[0]['body']['data']['shipments'][0]['recipient']);
        self::assertSame('Hoofddorp', $this->requests[0]['body']['data']['shipments'][0]['recipient']['city']);
    }

    public function testCreateAssignsMissingReferenceIdentifiers(): void
    {
        $shipment = self::shipment();

        $result = $this->createService(self::createdResponse([['id' => 2001]]))
            ->create(new ShipmentCollection([$shipment]));

        self::assertStringStartsWith('sdk_', (string) $shipment->getReferenceIdentifier());
        self::assertSame($shipment->getReferenceIdentifier(), $this->requests[0]['body']['data']['shipments'][0]['reference_identifier']);
        self::assertSame([2001 => $shipment->getReferenceIdentifier()], $result);
    }

    public function testCreateFallsBackToRequestReferenceWhenResponseReferenceIdentifierIsMissing(): void
    {
        $result = $this->createService(self::createdResponse([['id' => 7001]]))
            ->create(new ShipmentCollection([self::shipment('order-wrapper-ref')]));

        self::assertSame([7001 => 'order-wrapper-ref'], $result);
    }

    public function testCreateThrowsWhenResponseDataIsMissing(): void
    {
        $service = $this->createService('{}');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('postShipments() returned a response without data.');

        $service->create(new ShipmentCollection([self::shipment('order-1')]));
    }

    public function testCreateReturnsEmptyMappingWhenResponseHasNoShipments(): void
    {
        $result = $this->createService(self::createdResponse([]))
            ->create(new ShipmentCollection([self::shipment('order-1')]));

        self::assertSame([], $result);
    }

    public function testCreateSkipsShipmentsWithoutIdInResponse(): void
    {
        $result = $this->createService(self::createdResponse([
            ['reference_identifier' => 'order-1'],
            ['id' => 8002, 'reference_identifier' => 'order-2'],
        ]))->create(new ShipmentCollection([
            self::shipment('order-1'),
            self::shipment('order-2'),
        ]));

        self::assertSame([8002 => 'order-2'], $result);
    }

    public function testAnErrorResponseThrowsTheGeneratedException(): void
    {
        $service = $this->createService(
            '{"type":"urn:problem:validation","title":"Invalid shipment","status":422}',
            422,
            'application/problem+json'
        );

        try {
            $service->create(new ShipmentCollection([self::shipment('order-1')]));
            self::fail('A 422 response must throw.');
        } catch (PostShipmentsClientErrorException $exception) {
            self::assertSame(422, $exception->getResponse()->getStatusCode());
        }

        self::assertStringEndsWith(', application/problem+json', $this->requests[0]['headers']['accept'][0]);
    }

    public function testCreateThrowsBeforeSendingAShipmentWithoutRecipient(): void
    {
        $service = $this->createService(self::createdResponse([]));

        try {
            $service->create(new ShipmentCollection([self::shipment('order-1')->setRecipient(null)]));
            self::fail('A shipment without recipient must throw.');
        } catch (ValidationException) {
            self::assertSame([], $this->requests);
        }
    }

    /**
     * A shipment with the fields that the spec requires.
     */
    private static function shipment(?string $referenceIdentifier = null): Shipment
    {
        return (new Shipment())
            ->setReferenceIdentifier($referenceIdentifier)
            ->setCarrier('POSTNL')
            ->withPackageType('PACKAGE')
            ->setRecipient([
                'cc'          => 'NL',
                'postal_code' => '2132JE',
                'city'        => 'Hoofddorp',
                'street'      => 'Antareslaan',
                'number'      => '31',
                'person'      => 'Test Person',
            ]);
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

        return new ShipmentCreateService($this->getApiKey(), JaneClientFactory::coreApi('my-api-key', null, null, [], $httpClient));
    }

    /**
     * @param array<int, array<string, mixed>> $shipments
     */
    private static function createdResponse(array $shipments): string
    {
        return json_encode(['data' => ['shipments' => $shipments]], JSON_THROW_ON_ERROR);
    }
}
