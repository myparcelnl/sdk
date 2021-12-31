<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Consignment;

use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\Test\Bootstrap\ConsignmentTestCase;

class ConsignmentShipmentOptionsTest extends ConsignmentTestCase
{
    /**
     * @return array
     * @throws \Exception
     */
    public function provide18PlusCheckData(): array
    {
        return $this->createConsignmentProviderDataset([
            'Normal 18+ check'      => [
                self::AGE_CHECK      => true,
                self::ONLY_RECIPIENT => true,
                self::SIGNATURE      => true,
            ],
            // todo:
            //  '18+ check no signature' => [
            //      self::AGE_CHECK                      => true,
            //      self::ONLY_RECIPIENT                 => false,
            //      self::SIGNATURE                      => false,
            //      self::expected(self::ONLY_RECIPIENT) => true,
            //      self::expected(self::SIGNATURE)      => true,
            //  ],
            '18+ check EU shipment' => $this->getDefaultAddress(AbstractConsignment::CC_BE) + [
                    self::AGE_CHECK => true,
                    self::EXCEPTION => 'The age check is not possible with an EU shipment or world shipment',
                ],
        ]);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function provideDeliveryMomentData(): array
    {
        return $this->createConsignmentProviderDataset([
            'Morning delivery'                => [
                self::DELIVERY_TYPE => AbstractConsignment::DELIVERY_TYPE_MORNING,
                self::DELIVERY_DATE => $this->generateDeliveryDate(),
            ],
            'Morning delivery with signature' => [
                self::SIGNATURE     => true,
                self::DELIVERY_TYPE => AbstractConsignment::DELIVERY_TYPE_MORNING,
                self::DELIVERY_DATE => $this->generateDeliveryDate(),
            ],
            'Evening delivery'                => [
                self::DELIVERY_DATE => $this->generateDeliveryDate(),
                self::DELIVERY_TYPE => AbstractConsignment::DELIVERY_TYPE_EVENING,
            ],
            'Evening delivery with signature' => [
                self::SIGNATURE     => true,
                self::DELIVERY_DATE => $this->generateDeliveryDate(),
                self::DELIVERY_TYPE => AbstractConsignment::DELIVERY_TYPE_EVENING,
            ],
        ]);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function provideDigitalStampData(): array
    {
        return $this->createConsignmentProviderDataset([
            'Digital stamp 80 grams' => [
                self::LABEL_DESCRIPTION => 112345,
                self::PACKAGE_TYPE      => AbstractConsignment::PACKAGE_TYPE_DIGITAL_STAMP,
                self::TOTAL_WEIGHT      => 76,
            ],
            'Digital stamp 2kg'      => [
                self::LABEL_DESCRIPTION => 112345,
                self::PACKAGE_TYPE      => AbstractConsignment::PACKAGE_TYPE_DIGITAL_STAMP,
                self::TOTAL_WEIGHT      => 1999,
            ],
            // todo:
            //  'Digital stamp no weight' => [
            //      self::LABEL_DESCRIPTION            => 112345,
            //      self::PACKAGE_TYPE                 => AbstractConsignment::PACKAGE_TYPE_DIGITAL_STAMP,
            //      self::TOTAL_WEIGHT                 => 0,
            //      self::expected(self::TOTAL_WEIGHT) => 1,
            //  ],
        ]);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function provideLargeFormatData(): array
    {
        return $this->createConsignmentProviderDataset([
            'Large format national'   => [
                self::CUSTOMS_DECLARATION => $this->getDefaultCustomsDeclaration(),
                self::LARGE_FORMAT        => true,
            ],
            // todo:
            //  'Large format set from true to false' => $this->getDefaultAddress('CA') + [
            //          self::CUSTOMS_DECLARATION          => $this->getDefaultCustomsDeclaration('CA'),
            //          self::LARGE_FORMAT                 => true,
            //          self::expected(self::LARGE_FORMAT) => false,
            //      ],
            'Large format to Belgium' => $this->getDefaultAddress(AbstractConsignment::CC_BE) + [
                    self::CUSTOMS_DECLARATION => $this->getDefaultCustomsDeclaration(AbstractConsignment::CC_BE),
                    self::LARGE_FORMAT        => true,
                ],
        ]);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function provideMailboxData(): array
    {
        return $this->createConsignmentProviderDataset([
            'Mailbox shipment'              => [
                self::PACKAGE_TYPE => AbstractConsignment::PACKAGE_TYPE_MAILBOX,
            ],
            'Mailbox with shipment options' => [
                self::INSURANCE                      => 250,
                self::LABEL_DESCRIPTION              => 1234,
                self::LARGE_FORMAT                   => true,
                self::ONLY_RECIPIENT                 => true,
                self::PACKAGE_TYPE                   => AbstractConsignment::PACKAGE_TYPE_MAILBOX,
                self::RETURN                         => true,
                self::SIGNATURE                      => true,
                self::expected(self::INSURANCE)      => 0,
                self::expected(self::LARGE_FORMAT)   => false,
                self::expected(self::ONLY_RECIPIENT) => false,
                self::expected(self::RETURN)         => false,
                self::expected(self::SIGNATURE)      => false,
            ],
        ]);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function providePickupLocationData(): array
    {
        return $this->createConsignmentProviderDataset([
            'Pickup location' => [
                self::DELIVERY_DATE        => $this->generateDeliveryDate(),
                self::DELIVERY_TYPE        => AbstractConsignment::DELIVERY_TYPE_PICKUP,
                self::PICKUP_CITY          => 'Hoofddorp',
                self::PICKUP_COUNTRY       => AbstractConsignment::CC_NL,
                self::PICKUP_LOCATION_NAME => 'Primera Sanders',
                self::PICKUP_NUMBER        => '1',
                self::PICKUP_POSTAL_CODE   => '2132BA',
                self::PICKUP_STREET        => 'Polderplein',
                self::RETAIL_NETWORK_ID    => 'PNPNL-01',
            ],
        ]);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function provideReferenceIdentifierData(): array
    {
        return $this->createConsignmentProviderDataset([
            //            'normal consignment with reference id'        => [
            //                [self::REFERENCE_IDENTIFIER => $this->generateTimestamp() . '_normal_consignment'],
            //            ],
            'two consignments with reference identifiers' => [
                [self::REFERENCE_IDENTIFIER => $this->generateTimestamp() . '_2_1'],
                [self::REFERENCE_IDENTIFIER => $this->generateTimestamp() . '_2_2'],
            ],
        ]);
    }

    /**
     * @param  array $testData
     *
     * @throws \Exception
     * @throws \Exception
     * @dataProvider provide18PlusCheckData
     */
    public function test18PlusCheck(array $testData): void
    {
        $this->doConsignmentTest($testData);
    }

    /**
     * @param  array $testData
     *
     * @throws \Exception
     * @throws \Exception
     * @dataProvider provideDigitalStampData
     */
    public function testDigitalStamp(array $testData): void
    {
        $this->doConsignmentTest($testData);
    }

    /**
     * @param  array $testData
     *
     * @throws \Exception
     * @throws \Exception
     * @dataProvider provideLargeFormatData
     */
    public function testLargeFormat(array $testData): void
    {
        $this->doConsignmentTest($testData);
    }

    /**
     * @param  array $testData
     *
     * @throws \Exception
     * @throws \Exception
     * @dataProvider provideMailboxData
     */
    public function testMailbox(array $testData): void
    {
        $this->doConsignmentTest($testData);
    }

    /**
     * @param  array $testData
     *
     * @throws \Exception
     * @throws \Exception
     * @dataProvider providePickupLocationData
     */
    public function testPickupLocation(array $testData): void
    {
        $this->doConsignmentTest($testData);
    }

    /**
     * @param  array $testData
     *
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @throws \Exception
     * @dataProvider provideReferenceIdentifierData
     */
    public function testReferenceIdentifier(array $testData): void
    {
        $collection = $this->generateCollection($testData);
        $collection->createConcepts();

        $savedCollection = $this->generateCollection($testData);
        $savedCollection->setLatestData();

        $referenceIdentifier = $collection->getOneConsignment()
            ->getReferenceIdentifier();
        $consignment         = $savedCollection->getConsignmentsByReferenceId($referenceIdentifier)
            ->first();

        self::assertNotEmpty($consignment, 'Consignment is not found');
        self::assertEquals($consignment->getReferenceIdentifier(), $referenceIdentifier);
        self::validateConsignmentOptions($testData, $consignment);
    }
}
