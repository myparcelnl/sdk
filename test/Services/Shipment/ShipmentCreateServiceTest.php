<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Shipment;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Api\ShipmentApi;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefShipmentPackageTypeV2;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefTypesCarrierV2;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentDefsShipment;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentPostShipmentsRequestV11;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentPostShipmentsRequestV11Data;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentResponsesPostShipmentsV12;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentResponsesPostShipmentsV12Data;
use MyParcelNL\Sdk\Collection\ShipmentCollection;
use MyParcelNL\Sdk\Model\Shipment\Shipment;
use MyParcelNL\Sdk\Services\Shipment\ShipmentCreateService;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

final class ShipmentCreateServiceTest extends TestCase
{
    public function testCreateThrowsWhenNoShipmentsWereAdded(): void
    {
        $service = new ShipmentCreateService($this->getApiKey(), $this->createMock(ShipmentApi::class));
        $collection = new ShipmentCollection();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('At least one shipment must be added before calling create().');

        $service->create($collection);
    }

    public function testCreateThrowsWhenMoreThanHundredShipmentsWereAdded(): void
    {
        $service = new ShipmentCreateService($this->getApiKey(), $this->createMock(ShipmentApi::class));
        $collection = new ShipmentCollection();

        for ($i = 0; $i < 101; $i++) {
            $collection->push(new Shipment());
        }

        $this->expectException(InvalidArgumentException::class);

        $service->create($collection);
    }

    public function testCreateSendsCorrectRequestBody(): void
    {
        $shipmentOne = (new Shipment())->setReferenceIdentifier('order-1');
        $shipmentTwo = (new Shipment())->setReferenceIdentifier('order-2');

        $api = $this->createMock(ShipmentApi::class);
        $service = new ShipmentCreateService($this->getApiKey(), $api);
        $service->setUserAgentForProposition('Magento', '2.4.7');

        $collection = new ShipmentCollection([$shipmentOne, $shipmentTwo]);
        $expectedUserAgent = $service->getUserAgentHeader();

        $api->expects(self::once())
            ->method('postShipments')
            ->with(
                self::identicalTo($expectedUserAgent),
                self::callback(static function ($request) use ($expectedUserAgent): bool {
                    self::assertInstanceOf(ShipmentPostShipmentsRequestV11::class, $request);
                    self::assertInstanceOf(ShipmentPostShipmentsRequestV11Data::class, $request->getData());

                    $data = $request->getData();
                    self::assertSame($expectedUserAgent, $data->getUserAgent());

                    $shipments = $data->getShipments();
                    self::assertCount(2, $shipments);
                    self::assertSame('order-1', $shipments[0]->getReferenceIdentifier());
                    self::assertSame('order-2', $shipments[1]->getReferenceIdentifier());

                    return true;
                }),
                self::identicalTo('A4'),
                self::identicalTo('1;2'),
                self::isNull(),
                self::isNull(),
                self::callback(static function (string $contentType): bool {
                    return 0 === strpos($contentType, 'application/vnd.shipment+json');
                })
            )
            ->willReturn($this->mockCreateResponse([
                ['id' => 1001, 'reference_identifier' => 'order-1'],
                ['id' => 1002, 'reference_identifier' => 'order-2'],
            ]));

        $result = $service->create($collection, 'A4', '1;2');

        self::assertSame([1001 => 'order-1', 1002 => 'order-2'], $result);
    }

    public function testCreateCastsEnumValuesToIntegers(): void
    {
        $shipment = (new Shipment())
            ->setReferenceIdentifier('order-enum')
            ->setCarrier(RefTypesCarrierV2::POSTNL)
            ->withPackageType(RefShipmentPackageTypeV2::PACKAGE);

        $api = $this->createMock(ShipmentApi::class);
        $service = new ShipmentCreateService($this->getApiKey(), $api);
        $collection = new ShipmentCollection([$shipment]);

        $api->expects(self::once())
            ->method('postShipments')
            ->with(
                self::anything(),
                self::callback(static function ($request): bool {
                    self::assertInstanceOf(ShipmentPostShipmentsRequestV11::class, $request);
                    $shipmentData = $request->getData()->getShipments()[0];

                    $payload = json_decode(json_encode($shipmentData), true);
                    self::assertIsArray($payload);
                    self::assertIsInt($payload['carrier']);
                    self::assertSame(1, $payload['carrier']);
                    self::assertIsInt($payload['options']['package_type']);
                    self::assertSame(1, $payload['options']['package_type']);

                    return true;
                }),
                self::isNull(),
                self::isNull(),
                self::isNull(),
                self::isNull(),
                self::callback(static function (string $contentType): bool {
                    return 0 === strpos($contentType, 'application/vnd.shipment+json');
                })
            )
            ->willReturn($this->mockCreateResponse([
                ['id' => 3001, 'reference_identifier' => 'order-enum'],
            ]));

        $result = $service->create($collection);
        self::assertSame([3001 => 'order-enum'], $result);
    }

    public function testCreateAssignsMissingReferenceIdentifiers(): void
    {
        $shipment = new Shipment();
        $collection = new ShipmentCollection([$shipment]);

        $api = $this->createMock(ShipmentApi::class);
        $service = new ShipmentCreateService($this->getApiKey(), $api);

        $capturedReference = null;

        $api->expects(self::once())
            ->method('postShipments')
            ->with(
                self::anything(),
                self::callback(static function ($request) use (&$capturedReference): bool {
                    self::assertInstanceOf(ShipmentPostShipmentsRequestV11::class, $request);
                    $capturedReference = $request->getData()->getShipments()[0]->getReferenceIdentifier();

                    self::assertNotNull($capturedReference);
                    self::assertStringStartsWith('sdk_', $capturedReference);

                    return true;
                }),
                self::isNull(),
                self::isNull(),
                self::isNull(),
                self::isNull(),
                self::callback(static function (string $contentType): bool {
                    return 0 === strpos($contentType, 'application/vnd.shipment+json');
                })
            )
            ->willReturnCallback(
                fn () => $this->mockCreateResponse([
                    ['id' => 2001, 'reference_identifier' => $shipment->getReferenceIdentifier()],
                ])
            );

        $result = $service->create($collection);

        self::assertNotNull($shipment->getReferenceIdentifier());
        self::assertStringStartsWith('sdk_', $shipment->getReferenceIdentifier());
        self::assertSame($capturedReference, $shipment->getReferenceIdentifier());
        self::assertSame([2001 => $shipment->getReferenceIdentifier()], $result);
    }

    public function testCreateFallsBackToRequestReferenceWhenResponseReferenceIdentifierIsNull(): void
    {
        $shipment = (new Shipment())->setReferenceIdentifier('order-wrapper-ref');
        $collection = new ShipmentCollection([$shipment]);

        $api = $this->createMock(ShipmentApi::class);
        $service = new ShipmentCreateService($this->getApiKey(), $api);

        $api->expects(self::once())
            ->method('postShipments')
            ->willReturn($this->mockCreateResponse([
                ['id' => 7001, 'reference_identifier' => null],
            ]));

        $result = $service->create($collection);

        self::assertSame([7001 => 'order-wrapper-ref'], $result);
    }

    /**
     * @param array<int, array{id:int, reference_identifier:mixed}> $ids
     */
    private function mockCreateResponse(array $ids): ShipmentResponsesPostShipmentsV12
    {
        $shipmentModels = array_map(static function (array $row): ShipmentDefsShipment {
            $model = new ShipmentDefsShipment();
            $model->setId($row['id']);
            $model->setReferenceIdentifier($row['reference_identifier']);

            return $model;
        }, $ids);

        $data = new ShipmentResponsesPostShipmentsV12Data();
        $data->setShipments($shipmentModels);

        $response = new ShipmentResponsesPostShipmentsV12();
        $response->setData($data);

        return $response;
    }
}
