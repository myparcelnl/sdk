<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Helper;

use MyParcelNL\Sdk\src\Helper\MyParcelCollection;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\src\Support\Arr;
use MyParcelNL\Sdk\Test\Bootstrap\CollectionTestCase;

class MyParcelCollectionTest extends CollectionTestCase
{
    /**
     * @covers \MyParcelNL\Sdk\src\Helper\MyParcelCollection::find
     * @covers \MyParcelNL\Sdk\src\Helper\MyParcelCollection::findMany
     * @covers \MyParcelNL\Sdk\src\Helper\MyParcelCollection::findByReferenceId
     * @covers \MyParcelNL\Sdk\src\Helper\MyParcelCollection::findManyByReferenceId
     * @return void
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
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
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
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

        self::assertEquals('first consignment', $collection[0]->getLabelDescription());
        self::assertEquals('Return: first consignment', $collection[1]->getLabelDescription());
        self::assertEquals('second consignment', $collection[2]->getLabelDescription());
        self::assertEquals('Return: second consignment', $collection[3]->getLabelDescription());

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
        self::markTestBroken();

        $collection = $this->generateSimpleCollection(10);
        $shuffled   = $collection->toArray();
        shuffle($shuffled);

        $sorted = new MyParcelCollection($shuffled);
        $sorted = $sorted->sortByCollection($collection);

        self::assertEquals(
            Arr::pluck($collection->toArray(), self::REFERENCE_IDENTIFIER),
            Arr::pluck($sorted->toArray(), self::REFERENCE_IDENTIFIER)
        );
    }
}
