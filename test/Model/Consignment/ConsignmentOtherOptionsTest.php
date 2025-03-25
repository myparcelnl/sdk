<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Consignment;

use MyParcelNL\Sdk\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\Test\Bootstrap\ConsignmentTestCase;

class ConsignmentOtherOptionsTest extends ConsignmentTestCase
{
    /**
     * @return array
     * @throws \Exception
     */
    public function provideAutoDetectPickupData(): array
    {
        $deliveryDate = $this->generateDeliveryDate();
        return $this->createConsignmentProviderDataset(
            [
                'Auto detect pickup' => [
                    self::FULL_STREET                   => 'Aankomstpassage 4',
                    self::POSTAL_CODE                   => '1118AX',
                    self::CITY                          => 'Schiphol',
                    self::AUTO_DETECT_PICKUP            => true,
                    self::DELIVERY_DATE                 => $deliveryDate,
                    self::expected(self::DELIVERY_TYPE) => AbstractConsignment::DELIVERY_TYPE_PICKUP,
                    self::expected(self::DELIVERY_DATE) => $deliveryDate,
                ], [
                    self::FULL_STREET                   => 'Aankomstpassage 4',
                    self::POSTAL_CODE                   => '1118AX',
                    self::CITY                          => 'Schiphol',
                    self::AUTO_DETECT_PICKUP            => false,
                    self::DELIVERY_DATE                 => $deliveryDate,
                    self::expected(self::DELIVERY_TYPE) => AbstractConsignment::DELIVERY_TYPE_STANDARD,
                    self::expected(self::DELIVERY_DATE) => $deliveryDate,
                ],
            ]
        );
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function provideSaveRecipientAddressData(): array
    {
        return $this->createConsignmentProviderDataset([
            'Save recipient address' => [
                self::SAVE_RECIPIENT_ADDRESS => true,
            ],
        ]);
    }

    /**
     * @param  array $testData
     *
     * @throws \Exception
     * @dataProvider provideAutoDetectPickupData
     */
    public function testAutoDetectPickup(array $testData): void
    {
        $this->doConsignmentTest($testData);
    }

    /**
     * @param  array $testData
     *
     * @throws \Exception
     * @dataProvider provideSaveRecipientAddressData
     */
    public function testSaveRecipientAddress(array $testData): void
    {
        $this->doConsignmentTest($testData);
    }
}
