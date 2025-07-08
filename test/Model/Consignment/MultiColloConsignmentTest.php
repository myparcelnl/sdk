<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Consignment;

use MyParcelNL\Sdk\Model\Carrier\CarrierPostNL;
use MyParcelNL\Sdk\Model\Carrier\CarrierDPD;
use MyParcelNL\Sdk\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\Model\Consignment\PostNLConsignment;
use MyParcelNL\Sdk\Model\Consignment\DPDConsignment;
use MyParcelNL\Sdk\Test\Bootstrap\ConsignmentTestCase;
use MyParcelNL\Sdk\Helper\MyParcelCollection;

class MultiColloConsignmentTest extends ConsignmentTestCase
{
    public function provideMultiColloData(): array
    {
        return [
            'two parcels with different weight and options' => [
                [
                    (new PostNLConsignment())
                        ->setApiKey('irrelevant')
                        ->setPackageType(AbstractConsignment::PACKAGE_TYPE_PACKAGE)
                        ->setTotalWeight(1000)
                        ->setSignature(true),
                    (new PostNLConsignment())
                        ->setApiKey('irrelevant')
                        ->setPackageType(AbstractConsignment::PACKAGE_TYPE_PACKAGE)
                        ->setTotalWeight(2000)
                        ->setAgeCheck(true),
                ],
                true, // should support multi collo
            ],
            'one parcel' => [
                [
                    (new PostNLConsignment())
                        ->setApiKey('irrelevant')
                        ->setPackageType(AbstractConsignment::PACKAGE_TYPE_PACKAGE)
                        ->setTotalWeight(1500),
                ],
                true,
            ],
            'three parcels' => [
                [
                    (new PostNLConsignment())
                        ->setApiKey('irrelevant')
                        ->setPackageType(AbstractConsignment::PACKAGE_TYPE_PACKAGE)
                        ->setTotalWeight(500),
                    (new PostNLConsignment())
                        ->setApiKey('irrelevant')
                        ->setPackageType(AbstractConsignment::PACKAGE_TYPE_PACKAGE)
                        ->setTotalWeight(750),
                    (new PostNLConsignment())
                        ->setApiKey('irrelevant')
                        ->setPackageType(AbstractConsignment::PACKAGE_TYPE_PACKAGE)
                        ->setTotalWeight(1250),
                ],
                true,
            ],
            'different carriers (should fail)' => [
                [
                    (new PostNLConsignment())
                        ->setApiKey('irrelevant')
                        ->setPackageType(AbstractConsignment::PACKAGE_TYPE_PACKAGE)
                        ->setTotalWeight(1000),
                    (new DPDConsignment())
                        ->setApiKey('irrelevant')
                        ->setPackageType(AbstractConsignment::PACKAGE_TYPE_PACKAGE)
                        ->setTotalWeight(2000),
                ],
                false,
            ],
        ];
    }

    /**
     * @dataProvider provideMultiColloData
     */
    public function testMultiColloConsignments(array $consignments, bool $shouldSupportMultiCollo): void
    {
        $collection = new MyParcelCollection();
        $exceptionThrown = false;
        try {
            $collection->addMultiColloConsignments($consignments);
        } catch (\Exception $e) {
            $exceptionThrown = true;
        }

        if (! $shouldSupportMultiCollo) {
            self::assertTrue($exceptionThrown, 'Expected exception for different carriers.');
            return;
        }

        self::assertCount(count($consignments), $collection, 'Number of consignments in collection does not match.');

        // Check multi collo properties if more than one consignment
        if (count($consignments) > 1) {
            $referenceId = $collection->getConsignments()[0]->getReferenceIdentifier();
            foreach ($collection as $index => $consignment) {
                self::assertTrue(
                    $consignment->isPartOfMultiCollo(),
                    sprintf('Consignment %d is not marked as multi collo.', $index + 1)
                );
                self::assertEquals(
                    $referenceId,
                    $consignment->getReferenceIdentifier(),
                    sprintf('Consignment %d does not have the correct reference identifier.', $index + 1)
                );
            }
        }

        // Check that shipment options and weight are unique per consignment
        foreach ($collection as $index => $consignment) {
            self::assertEquals(
                $consignments[$index]->getTotalWeight(),
                $consignment->getTotalWeight(),
                sprintf('Consignment %d does not have the correct weight.', $index + 1)
            );
            if (method_exists($consignments[$index], 'isSignature')) {
                self::assertEquals(
                    $consignments[$index]->isSignature(),
                    $consignment->isSignature(),
                    sprintf('Consignment %d does not have the correct signature option.', $index + 1)
                );
            }
            if (method_exists($consignments[$index], 'hasAgeCheck')) {
                self::assertEquals(
                    $consignments[$index]->hasAgeCheck(),
                    $consignment->hasAgeCheck(),
                    sprintf('Consignment %d does not have the correct age check option.', $index + 1)
                );
            }
        }
    }
}
