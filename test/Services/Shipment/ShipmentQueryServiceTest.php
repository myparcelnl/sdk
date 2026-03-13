<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Shipment;

use MyParcelNL\Sdk\Client\Generated\CoreApi\Api\ShipmentApi;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\FixedShipmentRecipient;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentDefsShipment;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentResponsesShipments;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentResponsesShipmentsData;
use MyParcelNL\Sdk\Services\Shipment\ShipmentQueryService;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

final class ShipmentQueryServiceTest extends TestCase
{
    public function testFindManyReturnsEmptyArrayForEmptyInput(): void
    {
        $api = $this->createMock(ShipmentApi::class);
        $api->expects(self::never())->method('getShipmentsById');

        $service = new ShipmentQueryService($this->getApiKey(), $api);

        self::assertSame([], $service->findMany([]));
    }

    public function testFindManyBuildsRequestAndParsesResponse(): void
    {
        $shipment1 = new ShipmentDefsShipment(['id' => 10]);
        $shipment2 = new ShipmentDefsShipment(['id' => 20]);

        $api = $this->createMock(ShipmentApi::class);
        $api->expects(self::once())
            ->method('getShipmentsById')
            ->with(self::identicalTo('10;20'), self::isType('string'))
            ->willReturn($this->buildShipmentsResponse([$shipment1, $shipment2]));

        $service = new ShipmentQueryService($this->getApiKey(), $api);
        $result = $service->findMany([10, 20]);

        self::assertCount(2, $result);
        self::assertSame(10, $result[0]->getId());
        self::assertSame(20, $result[1]->getId());
    }

    public function testFindReturnsFirstShipmentOrNull(): void
    {
        $shipment = new ShipmentDefsShipment(['id' => 42]);

        $api = $this->createMock(ShipmentApi::class);
        $api->method('getShipmentsById')
            ->willReturn($this->buildShipmentsResponse([$shipment]));

        $service = new ShipmentQueryService($this->getApiKey(), $api);

        $result = $service->find(42);
        self::assertInstanceOf(ShipmentDefsShipment::class, $result);
        self::assertSame(42, $result->getId());
    }

    public function testFindReturnsNullWhenNoShipmentExists(): void
    {
        $api = $this->createMock(ShipmentApi::class);
        $api->method('getShipmentsById')
            ->willReturn($this->buildShipmentsResponse([]));

        $service = new ShipmentQueryService($this->getApiKey(), $api);

        self::assertNull($service->find(999));
    }

    public function testQueryPassesFiltersToGeneratedMethod(): void
    {
        $api = $this->createMock(ShipmentApi::class);
        $api->expects(self::once())
            ->method('getShipments')
            ->willReturnCallback(function (...$args) {
                // Verify key parameters are passed correctly
                self::assertSame('3SMYPA1234567', $args[1]); // barcode
                self::assertSame(2, $args[12]); // page
                self::assertSame('order-100', $args[14]); // reference_identifier
                self::assertSame(300, $args[18]); // size (default)
                self::assertSame('desc', $args[19]); // sort
                self::assertSame(1, $args[20]); // status

                return $this->buildShipmentsResponse([new ShipmentDefsShipment(['id' => 123])]);
            });

        $service = new ShipmentQueryService($this->getApiKey(), $api);
        $result = $service->query([
            'barcode' => '3SMYPA1234567',
            'reference_identifier' => 'order-100',
            'page' => 2,
            'sort' => 'desc',
            'status' => 1,
        ]);

        self::assertCount(1, $result);
    }

    public function testFindManyByReferenceIdAggregatesAndDeduplicatesByShipmentId(): void
    {
        $callCount = 0;

        $api = $this->createMock(ShipmentApi::class);
        $api->expects(self::exactly(2))
            ->method('getShipments')
            ->willReturnCallback(function () use (&$callCount) {
                $callCount++;

                if (1 === $callCount) {
                    return $this->buildShipmentsResponse([
                        new ShipmentDefsShipment(['id' => 1001]),
                        new ShipmentDefsShipment(['id' => 1002]),
                    ]);
                }

                return $this->buildShipmentsResponse([
                    new ShipmentDefsShipment(['id' => 1002]),
                    new ShipmentDefsShipment(['id' => 1003]),
                ]);
            });

        $service = new ShipmentQueryService($this->getApiKey(), $api);
        $result = $service->findManyByReferenceId(['ref-a', 'ref-b']);

        self::assertCount(3, $result);
        $ids = array_map(static fn (ShipmentDefsShipment $s) => $s->getId(), $result);
        self::assertSame([1001, 1002, 1003], $ids);
    }

    public function testRecipientIsDeserializedAsFixedShipmentRecipient(): void
    {
        $recipient = new FixedShipmentRecipient([
            'cc' => 'NL',
            'street' => 'Antareslaan',
            'number' => '31',
            'postal_code' => '2132JE',
            'city' => 'Hoofddorp',
            'person' => 'Test Person',
        ]);
        $shipment = new ShipmentDefsShipment(['id' => 1, 'recipient' => $recipient]);

        $api = $this->createMock(ShipmentApi::class);
        $api->method('getShipmentsById')
            ->willReturn($this->buildShipmentsResponse([$shipment]));

        $service = new ShipmentQueryService($this->getApiKey(), $api);
        $result = $service->find(1);

        self::assertNotNull($result);
        $resultRecipient = $result->getRecipient();
        self::assertInstanceOf(FixedShipmentRecipient::class, $resultRecipient);
        self::assertSame('Antareslaan', $resultRecipient->getStreet());
        self::assertSame('NL', $resultRecipient->getCc());
        self::assertSame('31', $resultRecipient->getNumber());
        self::assertSame('2132JE', $resultRecipient->getPostalCode());
        self::assertSame('Hoofddorp', $resultRecipient->getCity());
        self::assertSame('Test Person', $resultRecipient->getPerson());
    }

    private function buildShipmentsResponse(array $shipments): ShipmentResponsesShipments
    {
        $data = new ShipmentResponsesShipmentsData();
        $data->setShipments($shipments);

        $response = new ShipmentResponsesShipments();
        $response->setData($data);

        return $response;
    }
}
