<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Consignment;

use MyParcelNL\Sdk\Model\Carrier\CarrierPostNL;
use MyParcelNL\Sdk\Model\Carrier\CarrierDPD;
use MyParcelNL\Sdk\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\Test\Bootstrap\ConsignmentTestCase;
use MyParcelNL\Sdk\Helper\MyParcelCollection;
use MyParcelNL\Sdk\Helper\MultiColloConsignmentData;

class MultiColloConsignmentTest extends ConsignmentTestCase
{
    public function provideMultiColloData(): array
    {
        return [
            'two parcels with different weight' => [
                [
                    new MultiColloConsignmentData(CarrierPostNL::ID, 'irrelevant', 1000),
                    new MultiColloConsignmentData(CarrierPostNL::ID, 'irrelevant', 2000),
                ],
                true, // should support multi collo
            ],
            'one parcel' => [
                [
                    new MultiColloConsignmentData(CarrierPostNL::ID, 'irrelevant', 1500),
                ],
                true,
            ],
            'three parcels' => [
                [
                    new MultiColloConsignmentData(CarrierPostNL::ID, 'irrelevant', 500),
                    new MultiColloConsignmentData(CarrierPostNL::ID, 'irrelevant', 750),
                    new MultiColloConsignmentData(CarrierPostNL::ID, 'irrelevant', 1250),
                ],
                true,
            ],
            'carrier without multi collo support' => [
                [
                    new MultiColloConsignmentData(CarrierDPD::ID, 'irrelevant', 1000),
                    new MultiColloConsignmentData(CarrierDPD::ID, 'irrelevant', 2000),
                ],
                false, // DPD supports only single collo
            ],
        ];
    }

    /**
     * @dataProvider provideMultiColloData
     */
    public function testMultiColloWeights(array $consignmentDataList, bool $shouldSupportMultiCollo): void
    {
        $referenceId = 'test-multicollo-123';
        $collection = new MyParcelCollection();
        $exceptionThrown = false;
        try {
            $collection->addMultiColloDataList($consignmentDataList, $referenceId);
        } catch (\Exception $e) {
            $exceptionThrown = true;
        }

        if (! $shouldSupportMultiCollo) {
            self::assertTrue($exceptionThrown, 'Expected exception for carrier without multi collo support.');
            return;
        }

        self::assertCount(count($consignmentDataList), $collection, 'Number of parcels in collection does not match.');

        foreach ($collection as $index => $consignment) {
            $expectedWeight = $consignmentDataList[$index]->totalWeight;
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
