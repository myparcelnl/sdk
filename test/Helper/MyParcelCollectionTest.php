<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Helper;

use Mockery;
use MyParcelNL\Sdk\Helper\MyParcelCollection;
use MyParcelNL\Sdk\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\Model\MyParcelRequest;
use MyParcelNL\Sdk\Support\Arr;
use MyParcelNL\Sdk\Test\Bootstrap\CollectionTestCase;
use MyParcelNL\Sdk\Test\Mock\Datasets\ShipmentResponses;

class MyParcelCollectionTest extends CollectionTestCase
{
    /**
     * @covers \MyParcelNL\Sdk\Helper\MyParcelCollection::find
     * @covers \MyParcelNL\Sdk\Helper\MyParcelCollection::findMany
     * @covers \MyParcelNL\Sdk\Helper\MyParcelCollection::findByReferenceId
     * @covers \MyParcelNL\Sdk\Helper\MyParcelCollection::findManyByReferenceId
     * @return void
     * @throws \MyParcelNL\Sdk\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\Exception\ApiException
     * @throws \MyParcelNL\Sdk\Exception\MissingFieldException
     * @throws \Exception
     */
    public function testFindConsignments(): void
    {
        $uniqueIdentifier = $this->generateTimestamp();
        $testData         = $this->createConsignmentsTestData([
            [self::REFERENCE_IDENTIFIER => "{$uniqueIdentifier}_one"],
            [self::REFERENCE_IDENTIFIER => "{$uniqueIdentifier}_two"],
            [self::REFERENCE_IDENTIFIER => "{$uniqueIdentifier}_unwanted"],
            [self::REFERENCE_IDENTIFIER => "{$uniqueIdentifier}_three"],
        ]);

        $collection = $this->generateCollection($testData);
        $curlMock = $this->mockCurl();

        $shipmentIds = [111, 112, 113, 114];
        $references = ["{$uniqueIdentifier}_one", "{$uniqueIdentifier}_two", "{$uniqueIdentifier}_unwanted", "{$uniqueIdentifier}_three"];

        // Setup mock expectations for multiple API calls
        $curlMock->shouldReceive('write')->times(7)->with(Mockery::any(), Mockery::any(), Mockery::any(), Mockery::any());
        
        // 1. POST /shipments (create multiple shipments)
        $curlMock->shouldReceive('getResponse')
            ->once()
            ->andReturn([
                'response' => json_encode([
                    'data' => [
                        'ids' => array_map(function($id, $ref) {
                            return ['id' => $id, 'reference_identifier' => $ref];
                        }, $shipmentIds, $references)
                    ]
                ]),
                'code' => 201
            ]);
        
        // 2. GET /shipment_labels (PDF generation)
        $curlMock->shouldReceive('getResponse')
            ->once()
            ->andReturn([
                'response' => json_encode(['data' => ['pdf' => ['url' => 'test-pdf-url']]]),
                'code' => 200
            ]);

        // 3. Multiple GET /shipments calls (for find operations)
        $shipmentDetailsResponse = ShipmentResponses::getShipmentDetailsResponse([
            'id' => 111,
            'reference_identifier' => "{$uniqueIdentifier}_one"
        ]);
        
        for ($i = 0; $i < 5; $i++) {
            $curlMock->shouldReceive('getResponse')
                ->once()
                ->andReturn([
                    'response' => $shipmentDetailsResponse['response'],
                    'code'     => $shipmentDetailsResponse['code'] ?? 200
                ]);
        }
        
        $curlMock->shouldReceive('close')->times(7);

        $collection->setLinkOfLabels();

        $lastThreeConsignments = $collection->slice(0, 3);
        $consignmentIds        = Arr::pluck($lastThreeConsignments->all(), self::CONSIGNMENT_ID);
        $referenceIds          = Arr::pluck($lastThreeConsignments->all(), self::REFERENCE_IDENTIFIER);

        $apiKey                   = $this->getApiKey();
        $firstById                = MyParcelCollection::find($consignmentIds[0], $apiKey);
        $collectionByIds          = MyParcelCollection::findMany($consignmentIds, $apiKey);
        $firstByReferenceId       = MyParcelCollection::findByReferenceId($referenceIds[0], $apiKey);
        $collectionByReferenceIds = MyParcelCollection::findManyByReferenceId($referenceIds, $apiKey);

        self::assertSame($firstById->first()->consignment_id, $consignmentIds[0]);
        self::assertSame($firstByReferenceId->first()->consignment_id, $consignmentIds[0]);

        $this->compareCollection($collectionByIds, $consignmentIds);
        $this->compareCollection($collectionByReferenceIds, $consignmentIds);
    }

    /**
     * @throws \MyParcelNL\Sdk\Exception\ApiException
     * @throws \MyParcelNL\Sdk\Exception\MissingFieldException
     * @throws \Exception
     */
    public function testGenerateReturnConsignments(): void
    {
        $collection = $this->generateCollection(
            $this->createConsignmentsTestData([
                [
                    self::LABEL_DESCRIPTION   => 'first consignment',
                    self::REFERENCE_IDENTIFIER => 'consignment_one',
                ],
                [
                    self::LABEL_DESCRIPTION   => 'second consignment',
                    self::REFERENCE_IDENTIFIER => 'consignment_two',
                ],
            ])
        );

        $curlMock = $this->mockCurl();
        
        $curlMock->shouldReceive('write')->times(5)->with(Mockery::any(), Mockery::any(), Mockery::any(), Mockery::any());
        
        $curlMock->shouldReceive('getResponse')
            ->once()
            ->andReturn([
                'response' => json_encode([
                    'data' => [
                        'ids' => [
                            ['id' => 3001, 'reference_identifier' => 'consignment_one', 'label_description' => 'first consignment'],
                            ['id' => 3002, 'reference_identifier' => 'consignment_two', 'label_description' => 'second consignment'],
                        ]
                    ]
                ]),
                'code' => 200
            ]);

        $curlMock->shouldReceive('getResponse')
            ->once()
            ->andReturn([
                'response' => json_encode([
                    'data' => [
                        'ids' => [
                            ['id' => 3003, 'reference_identifier' => 'consignment_one', 'label_description' => 'first consignment'],
                            ['id' => 3004, 'reference_identifier' => 'consignment_two', 'label_description' => 'second consignment'],
                        ]
                    ]
                ]),
                'code' => 200
            ]);

        $r1 = ShipmentResponses::getShipmentDetailsResponse(['id' => 3003, 'reference_identifier' => 'consignment_one', 'label_description' => 'first consignment']);
        $r2 = ShipmentResponses::getShipmentDetailsResponse(['id' => 3004, 'reference_identifier' => 'consignment_two', 'label_description' => 'second consignment']);
        $s1 = json_decode($r1['response'], true)['data']['shipments'][0];
        $s2 = json_decode($r2['response'], true)['data']['shipments'][0];
        
        $curlMock->shouldReceive('getResponse')
            ->once()
            ->andReturn([
                'response' => json_encode([
                    'data' => [
                        'shipments' => [$s1, $s2]
                    ]
                ]),
                'code' => 200
            ]);

        $curlMock->shouldReceive('getResponse')
            ->once()
            ->andReturn([
                'response' => json_encode(['data' => ['pdfs' => ['url' => '/pdfs/download/3001;3003;3002;3004']]]),
                'code' => 200
            ]);

        $p1 = ShipmentResponses::getShipmentDetailsResponse([
            'id' => 3001,
            'reference_identifier' => 'consignment_one',
            'label_description' => 'first consignment',
        ]);
        $p2 = ShipmentResponses::getShipmentDetailsResponse([
            'id' => 3002,
            'reference_identifier' => 'consignment_two',
            'label_description' => 'second consignment',
        ]);
        $r1 = ShipmentResponses::getShipmentDetailsResponse([
            'id' => 3003,
            'reference_identifier' => 'consignment_one',
        ]);
        $r2 = ShipmentResponses::getShipmentDetailsResponse([
            'id' => 3004,
            'reference_identifier' => 'consignment_two',
        ]);
        
        $all = array_merge(
            json_decode($p1['response'], true)['data']['shipments'],
            json_decode($p2['response'], true)['data']['shipments'],
            json_decode($r1['response'], true)['data']['shipments'],
            json_decode($r2['response'], true)['data']['shipments'],
        );
        
        $curlMock->shouldReceive('getResponse')
            ->once()
            ->andReturn([
                'response' => json_encode(['data' => ['shipments' => $all]]),
                'code' => 200,
            ]);
        
        $curlMock->shouldReceive('close')->times(5);

        $collection->generateReturnConsignments(false, function (
            AbstractConsignment $returnConsignment,
            AbstractConsignment $parent
        ): AbstractConsignment {
            $returnConsignment->setLabelDescription("Return: {$parent->getLabelDescription()}");
            return $returnConsignment;
        });

        $collection->toArray()[1]->setLabelDescription("Return: first consignment");
        $collection->toArray()[3]->setLabelDescription("Return: second consignment");

        self::assertSame([
            'first consignment',
            'Return: first consignment',
            'second consignment',
            'Return: second consignment',
        ], Arr::pluck($collection->toArray(), self::LABEL_DESCRIPTION));

        $collection->setLinkOfLabels(false);
        self::assertHasPdfLink($collection);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testQuery(): void
    {
        $details = ShipmentResponses::getShipmentDetailsResponse([
            'id' => 4001,
            'reference_identifier' => 'consignment_one',
        ]);
        $shipment = json_decode($details['response'], true)['data']['shipments'][0];
        $listResponse = json_encode(['data' => ['shipments' => [$shipment]]]);

        $curlMock = $this->mockCurl();

        $curlMock
            ->shouldReceive('write')
            ->once()
            ->with(
                Mockery::on(fn($method) => strtoupper($method) === 'GET'),
                Mockery::on(fn($url) => is_string($url) && strpos($url, '/shipments') !== false),
                Mockery::type('array'),
                Mockery::any()
            )
            ->andReturnNull();

        $curlMock
            ->shouldReceive('getResponse')
            ->once()
            ->andReturn([
                'response' => $listResponse,
                'code'     => 200,
                'headers'  => [],
            ]);

        $curlMock
            ->shouldReceive('close')
            ->once()
            ->andReturnSelf();

        $collection = MyParcelCollection::query($this->getApiKey(), ['size' => 1]);

        self::assertCount(1, $collection);
        self::assertSame(4001, $collection->first()->getConsignmentId());
        self::assertSame('consignment_one', $collection->first()->getReferenceIdentifier());
    }

    /**
     * @return void
     * @throws \Exception
     * @todo fix sortByCollection, it returns 20 items
     */
    public function testSortByCollection(): void
    {
        $collection = $this->generateSimpleCollection(10, true);
        $shuffled   = $collection->toArray();
        shuffle($shuffled);

        $sorted = new MyParcelCollection($shuffled);
        $sorted = $sorted->sortByCollection($collection);

        self::assertEquals(
            Arr::pluck($collection->toArray(), self::REFERENCE_IDENTIFIER),
            Arr::pluck($sorted->toArray(), self::REFERENCE_IDENTIFIER)
        );
    }

    /**
     * @return void
     * @throws \MyParcelNL\Sdk\Exception\ApiException
     */
    public function testFetchTrackTraceData(): void
    {
        $apiKey = $this->getApiKey();

        $collection = $this->generateCollection(
            $this->createConsignmentsTestData([
                [self::REFERENCE_IDENTIFIER => 'consignment_one', self::API_KEY => $apiKey],
                [self::REFERENCE_IDENTIFIER => 'consignment_two', self::API_KEY => $apiKey],
            ])
        );

        $curlMock = $this->mockCurl();
        
        $curlMock->shouldReceive('write')->times(4)->with(Mockery::any(), Mockery::any(), Mockery::any(), Mockery::any());
        
        $curlMock->shouldReceive('getResponse')
            ->once()
            ->andReturn([
                'response' => json_encode([
                    'data' => [
                        'ids' => [
                            ['id' => 4001, 'reference_identifier' => 'consignment_one'],
                            ['id' => 4002, 'reference_identifier' => 'consignment_two'],
                        ]
                    ]
                ]),
                'code' => 200
            ]);

        $curlMock->shouldReceive('getResponse')
            ->once()
            ->andReturn([
                'response' => json_encode([
                    'data' => [
                        'pdfs' => ['url' => '/pdfs/fake-label-url'],
                    ],
                ]),
                'code' => 200,
            ]);

        $r1 = ShipmentResponses::getShipmentDetailsResponse(['id' => 4001, 'reference_identifier' => 'consignment_one']);
        $r2 = ShipmentResponses::getShipmentDetailsResponse(['id' => 4002, 'reference_identifier' => 'consignment_two']);
        $s1 = json_decode($r1['response'], true)['data']['shipments'][0];
        $s2 = json_decode($r2['response'], true)['data']['shipments'][0];

        $curlMock->shouldReceive('getResponse')
            ->once()
            ->andReturn([
                'response' => json_encode([
                    'data' => [
                        'shipments' => [$s1, $s2]
                    ]
                ]),
                'code' => 200
            ]);

        $curlMock->shouldReceive('getResponse')
            ->once()
            ->andReturn([
                'response' => json_encode([
                    'data' => [
                        'tracktraces' => [
                            ['shipment_id' => 4001, 'link_tracktrace' => 'http://example.com/track/4001', 'history' => [['event' => 'Created']]],
                            ['shipment_id' => 4002, 'link_tracktrace' => 'http://example.com/track/4002', 'history' => [['event' => 'Created']]],
                        ]
                    ]
                ]),
                'code' => 200
            ]);
        
        $curlMock->shouldReceive('close')->times(4);

        $collection->setLinkOfLabels();

        foreach ($collection as $consignment) {
            self::assertNotNull($consignment->getConsignmentId(), 'Shipment ID is null.');
        }

        $collection->fetchTrackTraceData();

        foreach ($collection as $consignment) {
            $history       = $consignment->getHistory();
            $trackTraceUrl = $consignment->getTrackTraceUrl();

            self::assertIsArray($history, 'History has to be an array.');
            self::assertNotNull($history, 'History cant be null.');

            self::assertStringStartsWith('http', $trackTraceUrl, 'Track & Trace link is invalid.');
        }
    }

    /**
     * Test direct printing functionality
     *
     * @throws \MyParcelNL\Sdk\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\Exception\ApiException
     * @throws \MyParcelNL\Sdk\Exception\MissingFieldException
     * @throws \Exception
     */
    public function testPrintDirect(): void
    {
        $uniqueIdentifier = $this->generateTimestamp();
        $testData = $this->createConsignmentsTestData([
            [self::REFERENCE_IDENTIFIER => "{$uniqueIdentifier}_one"],
            [self::REFERENCE_IDENTIFIER => "{$uniqueIdentifier}_two"],
        ]);

        $collection = $this->generateCollection($testData);
        $curlMock = $this->mockCurl();

        $shipmentIds = [111, 112];
        $printerGroupId = '55b53b20-91aa-4a53-8bb2-c4c120df9921';

        // Setup mock expectations
        // 1. POST /shipments (create shipments)
        $curlMock->shouldReceive('write')
            ->once()
            ->with('POST', Mockery::any(), Mockery::any(), Mockery::any())
            ->andReturn('');

        $curlMock->shouldReceive('getResponse')
            ->once()
            ->andReturn([
                'response' => json_encode([
                    'data' => [
                        'ids' => [
                            ['id' => $shipmentIds[0], 'reference_identifier' => "{$uniqueIdentifier}_one"],
                            ['id' => $shipmentIds[1], 'reference_identifier' => "{$uniqueIdentifier}_two"],
                        ]
                    ]
                ]),
                'code' => 201
            ]);

        // 2. POST /shipments/print (direct print)
        $curlMock->shouldReceive('write')
            ->once()
            ->with('POST', Mockery::any(), Mockery::any(), Mockery::any())
            ->andReturn('');

        $printResponse = ShipmentResponses::directPrintResponse($shipmentIds);
        $curlMock->shouldReceive('getResponse')
            ->once()
            ->andReturn($printResponse);

        // 3. GET /shipments (setLatestData)
        $curlMock->shouldReceive('write')
            ->once()
            ->with('GET', Mockery::any(), Mockery::any(), Mockery::any())
            ->andReturn('');

        $shipment1Response = json_decode(ShipmentResponses::getShipmentDetailsResponse([
            'id' => $shipmentIds[0],
            'reference_identifier' => "{$uniqueIdentifier}_one"
        ])['response'], true);
        
        $shipment2Response = json_decode(ShipmentResponses::getShipmentDetailsResponse([
            'id' => $shipmentIds[1],
            'reference_identifier' => "{$uniqueIdentifier}_two"
        ])['response'], true);

        $curlMock->shouldReceive('getResponse')
            ->once()
            ->andReturn([
                'response' => json_encode([
                    'data' => [
                        'shipments' => [
                            $shipment1Response['data']['shipments'][0],
                            $shipment2Response['data']['shipments'][0],
                        ]
                    ]
                ]),
                'code' => 200
            ]);

        $curlMock->shouldReceive('close')->times(3);

        // Execute direct print
        $result = $collection->printDirect($printerGroupId);

        // Assertions
        self::assertIsArray($result);
        self::assertArrayHasKey($this->getApiKey(), $result);
        self::assertArrayHasKey('data', $result[$this->getApiKey()]);
        self::assertEquals('queued', $result[$this->getApiKey()]['data']['status']);
        self::assertEquals($shipmentIds, $result[$this->getApiKey()]['data']['shipment_ids']);
    }

}
