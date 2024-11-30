<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Bootstrap;

use MyParcelNL\Sdk\Helper\MyParcelCollection;

class CollectionTestCase extends ConsignmentTestCase
{
    protected const CONSIGNMENT_ID = 'consignment_id';

    /**
     * @param  \MyParcelNL\Sdk\Helper\MyParcelCollection $collection
     * @param  int[]                                         $ids
     *
     * @return void
     */
    protected function compareCollection(MyParcelCollection $collection, array $ids): void
    {
        self::assertCount(count($ids), $collection, 'The returned collection does not have the expected size');
        self::assertNotEmpty($collection, 'The returned collection is not the same as the given ids');

        foreach ($collection as $consignment) {
            self::assertIsString($consignment->getStreet());
        }
    }

    /**
     * @param  int  $amountOfConsignments
     * @param  bool $addProps
     *
     * @return \MyParcelNL\Sdk\Helper\MyParcelCollection
     * @throws \MyParcelNL\Sdk\Exception\MissingFieldException
     * @throws \Exception
     */
    protected function generateSimpleCollection(
        int  $amountOfConsignments = 1,
        bool $addProps = false
    ): MyParcelCollection {
        $testData = [];

        for ($i = 1; $i <= $amountOfConsignments; $i++) {
            if ($addProps) {
                $testData[] = $this->getDefaultConsignmentData() + [
                        self::CONSIGNMENT_ID                      => $i,
                        ConsignmentTestCase::LABEL_DESCRIPTION    => (string) $i,
                        ConsignmentTestCase::REFERENCE_IDENTIFIER => (string) $i,
                    ];
            } else {
                $testData[] = $this->getDefaultConsignmentData();
            }
        }

        return $this->generateCollection($testData);
    }
}
