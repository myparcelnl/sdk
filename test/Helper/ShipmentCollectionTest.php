<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Helper;

use BadMethodCallException;
use InvalidArgumentException;
use Mockery;
use MyParcelNL\Sdk\CoreApi\Generated\Shipments\Api\ShipmentApi;
use MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\InlineObject;
use MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\RefShipmentReferenceIdentifier;
use MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\ShipmentPostShipmentsRequestV11;
use MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\ShipmentPostShipmentsRequestV11Data;
use MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\ShipmentResponsesShipmentIdsDataIdsInner;
use MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\ShipmentResponsesShipmentLabelsData;
use MyParcelNL\Sdk\Helper\ShipmentCollection;
use MyParcelNL\Sdk\Model\MyParcelRequest;
use MyParcelNL\Sdk\Model\Shipment\Carrier;
use MyParcelNL\Sdk\Model\Shipment\PackageType;
use MyParcelNL\Sdk\Model\Shipment\Shipment;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class ShipmentCollectionTest extends TestCase
{
    public function testCreateConceptsThrowsWhenNoShipmentsWereAdded(): void
    {
        $collection = new ShipmentCollection($this->getApiKey());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('At least one shipment must be added before calling createConcepts()');

        $collection->createConcepts();
    }

    public function testCreateConceptsThrowsWhenMoreThanHundredShipmentsWereAdded(): void
    {
        $collection = new ShipmentCollection($this->getApiKey());

        for ($i = 0; $i < 101; $i++) {
            $collection->addShipment(new Shipment());
        }

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Maximum 100 shipments per call');

        $collection->createConcepts();
    }

    public function testCreateConceptsSendsCorrectRequestBody(): void
    {
        $shipmentOne = (new Shipment())->setReferenceIdentifier('order-1');
        $shipmentTwo = (new Shipment())->setReferenceIdentifier('order-2');

        $api = $this->createMock(ShipmentApi::class);
        $collection = new ShipmentCollection($this->getApiKey(), $api);
        $collection->setUserAgentForProposition('Magento', '2.4.7');
        $collection->addShipments([$shipmentOne, $shipmentTwo]);

        $expectedUserAgent = $collection->getUserAgentHeader();

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
                self::identicalTo(ShipmentApi::contentTypes['postShipments'][0])
            )
            ->willReturn($this->mockCreateConceptsResponse([
                ['id' => 1001, 'reference_identifier' => 'order-1'],
                ['id' => 1002, 'reference_identifier' => 'order-2'],
            ]));

        $result = $collection->createConcepts('A4', '1;2');

        self::assertSame([1001 => 'order-1', 1002 => 'order-2'], $result);
    }

    public function testCreateConceptsCastsEnumValuesToIntegers(): void
    {
        $shipment = (new Shipment())
            ->setReferenceIdentifier('order-enum')
            ->withCarrier(Carrier::POSTNL)
            ->withPackageType(PackageType::PACKAGE);

        $api = $this->createMock(ShipmentApi::class);
        $collection = new ShipmentCollection($this->getApiKey(), $api);
        $collection->addShipment($shipment);

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
                self::identicalTo(ShipmentApi::contentTypes['postShipments'][0])
            )
            ->willReturn($this->mockCreateConceptsResponse([
                ['id' => 3001, 'reference_identifier' => 'order-enum'],
            ]));

        $result = $collection->createConcepts();
        self::assertSame([3001 => 'order-enum'], $result);
    }

    public function testCreateConceptsAssignsMissingReferenceIdentifiers(): void
    {
        $shipment = new Shipment();

        $api = $this->createMock(ShipmentApi::class);
        $collection = new ShipmentCollection($this->getApiKey(), $api);
        $collection->addShipment($shipment);

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
                self::identicalTo(ShipmentApi::contentTypes['postShipments'][0])
            )
            ->willReturnCallback(
                fn () => $this->mockCreateConceptsResponse([
                    ['id' => 2001, 'reference_identifier' => $shipment->getReferenceIdentifier()],
                ])
            );

        $result = $collection->createConcepts();

        self::assertNotNull($shipment->getReferenceIdentifier());
        self::assertStringStartsWith('sdk_', $shipment->getReferenceIdentifier());
        self::assertSame($capturedReference, $shipment->getReferenceIdentifier());
        self::assertSame([2001 => $shipment->getReferenceIdentifier()], $result);
    }

    public function testCreateConceptsFallsBackToRequestReferenceWhenGeneratedReferenceIsWrapperObject(): void
    {
        $shipment = (new Shipment())->setReferenceIdentifier('order-wrapper-ref');

        $api = $this->createMock(ShipmentApi::class);
        $collection = new ShipmentCollection($this->getApiKey(), $api);
        $collection->addShipment($shipment);

        $api->expects(self::once())
            ->method('postShipments')
            ->willReturn($this->mockCreateConceptsResponse([
                ['id' => 7001, 'reference_identifier' => new RefShipmentReferenceIdentifier()],
            ]));

        $result = $collection->createConcepts();

        self::assertSame([7001 => 'order-wrapper-ref'], $result);
    }

    public function testPrintDirectUsesMyParcelRequestWithDirectPrintHeader(): void
    {
        $api = $this->createMock(ShipmentApi::class);
        $api->expects(self::never())->method('postShipments');

        $collection = new ShipmentCollection($this->getApiKey(), $api);
        $collection->setUserAgentForProposition('Magento', '2.4.7');
        $collection->addShipment((new Shipment())->setReferenceIdentifier('order-1001'));

        $expectedUserAgent = $collection->getUserAgentHeader();
        $printerGroupId = 'printer-group-1';
        $expectedAccept = MyParcelRequest::getDirectPrintAcceptHeader($printerGroupId)['Accept'];

        $curlMock = $this->mockCurl();
        $curlMock->shouldReceive('write')
            ->once()
            ->with(
                Mockery::on(static function ($method): bool {
                    return 'POST' === strtoupper((string) $method);
                }),
                Mockery::on(static function ($url): bool {
                    return is_string($url)
                        && false !== strpos($url, MyParcelRequest::REQUEST_URL . '/shipments')
                        && false === strpos($url, MyParcelRequest::PRINTING_API_URL);
                }),
                Mockery::on(static function ($headers) use ($expectedAccept): bool {
                    self::assertIsArray($headers);
                    self::assertArrayHasKey('Accept', $headers);
                    self::assertArrayHasKey('Content-Type', $headers);
                    self::assertArrayHasKey('Authorization', $headers);
                    self::assertSame($expectedAccept, $headers['Accept']);
                    self::assertSame(
                        MyParcelRequest::HEADER_CONTENT_TYPE_SHIPMENT['Content-Type'],
                        $headers['Content-Type']
                    );

                    return true;
                }),
                Mockery::on(static function ($body) use ($expectedUserAgent): bool {
                    self::assertIsString($body);
                    $decoded = json_decode($body, true);

                    self::assertIsArray($decoded);
                    self::assertSame($expectedUserAgent, $decoded['data']['user_agent'] ?? null);
                    self::assertSame('order-1001', $decoded['data']['shipments'][0]['reference_identifier'] ?? null);

                    return true;
                })
            );

        $curlMock->shouldReceive('getResponse')
            ->once()
            ->andReturn([
                'response' => json_encode([
                    'data' => [
                        'ids' => [
                            ['id' => 6001, 'reference_identifier' => 'order-1001'],
                        ],
                    ],
                ]),
                'code' => 200,
            ]);

        $curlMock->shouldReceive('close')->once();

        $result = $collection->printDirect($printerGroupId);

        self::assertSame([6001 => 'order-1001'], $result);
    }

    public function testAddShipmentsThrowsWhenArrayContainsNonShipment(): void
    {
        $collection = new ShipmentCollection($this->getApiKey(), $this->createMock(ShipmentApi::class));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('All items must be instances of ' . Shipment::class);

        $collection->addShipments([new Shipment(), new \stdClass()]);
    }

    public function testCollectionStateHelpers(): void
    {
        $collection = new ShipmentCollection($this->getApiKey(), $this->createMock(ShipmentApi::class));

        self::assertSame(0, $collection->count());
        self::assertSame([], $collection->getShipments());

        $shipmentOne = new Shipment();
        $shipmentTwo = new Shipment();

        $collection->addShipment($shipmentOne);
        $collection->addShipments([$shipmentTwo]);

        self::assertSame(2, $collection->count());
        self::assertSame([$shipmentOne, $shipmentTwo], $collection->getShipments());

        $collection->clearShipmentsCollection();

        self::assertSame(0, $collection->count());
        self::assertSame([], $collection->getShipments());
    }

    public function testGetOneShipmentReturnsSingleItem(): void
    {
        $collection = new ShipmentCollection($this->getApiKey(), $this->createMock(ShipmentApi::class));
        $shipment = new Shipment();
        $collection->addShipment($shipment);

        self::assertSame($shipment, $collection->getOneShipment());
    }

    public function testGetOneShipmentThrowsWhenMultipleItems(): void
    {
        $collection = new ShipmentCollection($this->getApiKey(), $this->createMock(ShipmentApi::class));
        $collection->addShipment(new Shipment());
        $collection->addShipment(new Shipment());

        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage('Multiple items found');

        $collection->getOneShipment();
    }

    public function testGetOneShipmentThrowsWhenEmpty(): void
    {
        $collection = new ShipmentCollection($this->getApiKey(), $this->createMock(ShipmentApi::class));

        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage('No items found');

        $collection->getOneShipment();
    }

    public function testGetShipmentsByReferenceId(): void
    {
        $collection = new ShipmentCollection($this->getApiKey(), $this->createMock(ShipmentApi::class));

        $s1 = (new Shipment())->setReferenceIdentifier('order-100');
        $s2 = (new Shipment())->setReferenceIdentifier('order-200');
        $s3 = (new Shipment())->setReferenceIdentifier('order-100');

        $collection->addShipments([$s1, $s2, $s3]);

        $matches = $collection->getShipmentsByReferenceId('order-100');
        self::assertCount(2, $matches);
        self::assertSame($s1, $matches[0]);
        self::assertSame($s3, $matches[1]);

        self::assertCount(1, $collection->getShipmentsByReferenceId('order-200'));
        self::assertCount(0, $collection->getShipmentsByReferenceId('order-999'));
    }

    public function testGetShipmentsByReferenceIdGroup(): void
    {
        $collection = new ShipmentCollection($this->getApiKey(), $this->createMock(ShipmentApi::class));

        $s1 = (new Shipment())->setReferenceIdentifier('batch-A-001');
        $s2 = (new Shipment())->setReferenceIdentifier('batch-A-002');
        $s3 = (new Shipment())->setReferenceIdentifier('batch-B-001');
        $s4 = new Shipment(); // no reference

        $collection->addShipments([$s1, $s2, $s3, $s4]);

        $groupA = $collection->getShipmentsByReferenceIdGroup('batch-A');
        self::assertCount(2, $groupA);
        self::assertSame($s1, $groupA[0]);
        self::assertSame($s2, $groupA[1]);

        self::assertCount(1, $collection->getShipmentsByReferenceIdGroup('batch-B'));
        self::assertCount(0, $collection->getShipmentsByReferenceIdGroup('batch-C'));
    }

    /**
     * @param array<int, array{id:int, reference_identifier:mixed}> $ids
     */
    private function mockCreateConceptsResponse(array $ids): InlineObject
    {
        $labelsData = new ShipmentResponsesShipmentLabelsData();
        $idObjects = [];

        foreach ($ids as $id) {
            $idObject = new ShipmentResponsesShipmentIdsDataIdsInner();
            $idObject->setId($id['id']);
            $idObject->setReferenceIdentifier($id['reference_identifier']);
            $idObjects[] = $idObject;
        }

        $labelsData->setIds($idObjects);

        $response = new InlineObject();
        $response->setData($labelsData);

        return $response;
    }

}
