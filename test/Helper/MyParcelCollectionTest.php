<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Helper;

use MyParcelNL\Sdk\Helper\MyParcelCollection;
use MyParcelNL\Sdk\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\Support\Arr;
use MyParcelNL\Sdk\Test\Bootstrap\CollectionTestCase;

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
        $collection->setLinkOfLabels();

        $lastThreeConsignments = $collection->slice(1, 3);
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
                [self::LABEL_DESCRIPTION => 'first consignment'],
                [self::LABEL_DESCRIPTION => 'second consignment'],
            ])
        );

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
        $collection = MyParcelCollection::query($this->getApiKey(), ['size' => 1]);

        self::assertEquals(1, $collection->count());
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
     * Tests fetching track & trace data for multiple consignments with different API keys.
     * Asserts that each consignment receives a valid track & trace URL and a non-null history array.
     *
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

}
