<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Shipment;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Api\ShipmentApi;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentDefsShipment;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentDefsShipmentRecipient;
use MyParcelNL\Sdk\Services\Shipment\ShipmentQueryService;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use Psr\Http\Client\ClientInterface;

final class ShipmentQueryServiceTest extends TestCase
{
    public function testFindManyReturnsEmptyArrayForEmptyInput(): void
    {
        $api = $this->createMock(ShipmentApi::class);
        $api->expects(self::never())->method('getShipmentsByIdRequest');

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects(self::never())->method('sendRequest');

        $service = new ShipmentQueryService($this->getApiKey(), $api, $httpClient);

        self::assertSame([], $service->findMany([]));
    }

    public function testFindManyBuildsRequestAndParsesResponse(): void
    {
        $api = $this->createMock(ShipmentApi::class);
        $api->expects(self::once())
            ->method('getShipmentsByIdRequest')
            ->with(self::identicalTo('10;20'), self::isType('string'))
            ->willReturn(new Request('GET', 'https://api.myparcel.nl/shipments/10;20'));

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects(self::once())
            ->method('sendRequest')
            ->willReturn($this->createJsonResponse([
                'data' => ['shipments' => [
                    ['id' => 10, 'recipient' => ['cc' => 'NL', 'street' => 'Antareslaan']],
                    ['id' => 20, 'recipient' => ['cc' => 'BE', 'street' => 'Kerkstraat']],
                ]],
            ]));

        $service = new ShipmentQueryService($this->getApiKey(), $api, $httpClient);
        $result = $service->findMany([10, 20]);

        self::assertCount(2, $result);
        self::assertInstanceOf(ShipmentDefsShipment::class, $result[0]);
        self::assertSame(10, $result[0]->getId());
        self::assertSame(20, $result[1]->getId());
    }

    public function testFindReturnsFirstShipmentOrNull(): void
    {
        $api = $this->createMock(ShipmentApi::class);
        $api->method('getShipmentsByIdRequest')
            ->willReturn(new Request('GET', 'https://api.myparcel.nl/shipments/42'));

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects(self::once())
            ->method('sendRequest')
            ->willReturn($this->createJsonResponse([
                'data' => ['shipments' => [
                    ['id' => 42, 'recipient' => ['cc' => 'NL']],
                ]],
            ]));

        $service = new ShipmentQueryService($this->getApiKey(), $api, $httpClient);

        $result = $service->find(42);
        self::assertInstanceOf(ShipmentDefsShipment::class, $result);
        self::assertSame(42, $result->getId());
    }

    public function testFindReturnsNullWhenNoShipmentExists(): void
    {
        $api = $this->createMock(ShipmentApi::class);
        $api->method('getShipmentsByIdRequest')
            ->willReturn(new Request('GET', 'https://api.myparcel.nl/shipments/999'));

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects(self::once())
            ->method('sendRequest')
            ->willReturn($this->createJsonResponse(['data' => ['shipments' => []]]));

        $service = new ShipmentQueryService($this->getApiKey(), $api, $httpClient);

        self::assertNull($service->find(999));
    }

    public function testQueryPassesFiltersToRequestBuilder(): void
    {
        $capturedArgs = [];

        $api = $this->createMock(ShipmentApi::class);
        $api->expects(self::once())
            ->method('getShipmentsRequest')
            ->willReturnCallback(static function (...$args) use (&$capturedArgs) {
                $capturedArgs = $args;

                return new Request('GET', 'https://api.myparcel.nl/shipments');
            });

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->method('sendRequest')
            ->willReturn($this->createJsonResponse([
                'data' => ['shipments' => [
                    ['id' => 123],
                ]],
            ]));

        $service = new ShipmentQueryService($this->getApiKey(), $api, $httpClient);
        $result = $service->query([
            'barcode' => '3SMYPA1234567',
            'reference_identifier' => 'order-100',
            'page' => 2,
            'sort' => 'desc',
            'status' => 1,
        ]);

        self::assertCount(1, $result);
        self::assertSame('3SMYPA1234567', $capturedArgs[1]);
        self::assertSame(2, $capturedArgs[12]);
        self::assertSame('order-100', $capturedArgs[14]);
        self::assertSame(300, $capturedArgs[18]);
        self::assertSame('desc', $capturedArgs[19]);
        self::assertSame(1, $capturedArgs[20]);
    }

    public function testFindManyByReferenceIdAggregatesAndDeduplicatesByShipmentId(): void
    {
        $callCount = 0;

        $api = $this->createMock(ShipmentApi::class);
        $api->expects(self::exactly(2))
            ->method('getShipmentsRequest')
            ->willReturn(new Request('GET', 'https://api.myparcel.nl/shipments'));

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects(self::exactly(2))
            ->method('sendRequest')
            ->willReturnCallback(function () use (&$callCount) {
                $callCount++;

                if (1 === $callCount) {
                    return $this->createJsonResponse([
                        'data' => ['shipments' => [
                            ['id' => 1001],
                            ['id' => 1002],
                        ]],
                    ]);
                }

                return $this->createJsonResponse([
                    'data' => ['shipments' => [
                        ['id' => 1002],
                        ['id' => 1003],
                    ]],
                ]);
            });

        $service = new ShipmentQueryService($this->getApiKey(), $api, $httpClient);
        $result = $service->findManyByReferenceId(['ref-a', 'ref-b']);

        self::assertCount(3, $result);
        $ids = array_map(static fn (ShipmentDefsShipment $s) => $s->getId(), $result);
        self::assertSame([1001, 1002, 1003], $ids);
    }

    public function testRecipientStreetIsPreservedViaConstructorDeserialization(): void
    {
        $api = $this->createMock(ShipmentApi::class);
        $api->method('getShipmentsByIdRequest')
            ->willReturn(new Request('GET', 'https://api.myparcel.nl/shipments/1'));

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->method('sendRequest')
            ->willReturn($this->createJsonResponse([
                'data' => ['shipments' => [
                    [
                        'id' => 1,
                        'recipient' => [
                            'cc' => 'NL',
                            'street' => 'Antareslaan',
                            'number' => '31',
                            'postal_code' => '2132JE',
                            'city' => 'Hoofddorp',
                            'person' => 'Test Person',
                        ],
                    ],
                ]],
            ]));

        $service = new ShipmentQueryService($this->getApiKey(), $api, $httpClient);
        $shipment = $service->find(1);

        self::assertNotNull($shipment);
        $recipient = $shipment->getRecipient();
        self::assertInstanceOf(ShipmentDefsShipmentRecipient::class, $recipient);
        self::assertSame('Antareslaan', $recipient->getStreet());
        self::assertSame('NL', $recipient->getCc());
        self::assertSame('31', $recipient->getNumber());
        self::assertSame('2132JE', $recipient->getPostalCode());
        self::assertSame('Hoofddorp', $recipient->getCity());
        self::assertSame('Test Person', $recipient->getPerson());
    }

    private function createJsonResponse(array $data): Response
    {
        return new Response(200, ['Content-Type' => 'application/json'], json_encode($data));
    }
}
