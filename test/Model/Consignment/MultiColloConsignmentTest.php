<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Consignment;

use MyParcelNL\Sdk\Model\Carrier\CarrierPostNL;
use MyParcelNL\Sdk\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\Test\Bootstrap\ConsignmentTestCase;
use MyParcelNL\Sdk\Factory\ConsignmentFactory;
use MyParcelNL\Sdk\Helper\MyParcelCollection;

class MultiColloConsignmentTest extends ConsignmentTestCase
{
    public function provideMultiColloData(): array
    {
        return [
            'two parcels with different weight' => [
                [
                    [
                        self::CARRIER_ID    => CarrierPostNL::ID,
                        self::API_KEY       => 'irrelevant',
                        self::TOTAL_WEIGHT  => 1000,
                    ],
                    [
                        self::CARRIER_ID    => CarrierPostNL::ID,
                        self::API_KEY       => 'irrelevant',
                        self::TOTAL_WEIGHT  => 2000,
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider provideMultiColloData
     */
    public function testMultiColloWeights(array $parcelsData): void
    {
        $referenceId = 'test-multicollo-123';
        $collection = new MyParcelCollection();
        $collection->addMultiCollo(
            array_map(function ($data) use ($referenceId) {
                $data['reference_identifier'] = $referenceId;
                return $data;
            }, $parcelsData),
            function ($data) {
                $consignment = ConsignmentFactory::createByCarrierId($data[self::CARRIER_ID]);
                $consignment->setApiKey($data[self::API_KEY]);
                $consignment->setTotalWeight($data[self::TOTAL_WEIGHT]);
                if (isset($data['reference_identifier'])) {
                    $consignment->setReferenceIdentifier($data['reference_identifier']);
                }
                return $consignment;
            },
            $referenceId
        );

        self::assertCount(count($parcelsData), $collection, 'Number of parcels in collection does not match.');

        foreach ($collection as $index => $consignment) {
            $expectedWeight = $parcelsData[$index][self::TOTAL_WEIGHT];
            self::assertEquals(
                $expectedWeight,
                $consignment->getTotalWeight(),
                sprintf('Parcel %d does not have the correct weight.', $index + 1)
            );
            self::assertTrue(
                $consignment->isPartOfMultiCollo(),
                sprintf('Parcel %d is not marked as multi collo.', $index + 1)
            );
            self::assertEquals(
                $referenceId,
                $consignment->getReferenceIdentifier(),
                sprintf('Parcel %d does not have the correct reference identifier.', $index + 1)
            );
        }
    }
}
