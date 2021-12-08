<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Consignment;

use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\Test\Bootstrap\ConsignmentTestCase;

class ConsignmentOtherOptionsTest extends ConsignmentTestCase
{
    /**
     * @return array
     * @throws \Exception
     */
    public function provideAutoDetectPickupData(): array
    {
        return $this->createConsignmentProviderDataset([
            'Auto detect pickup' => [
                self::AUTO_DETECT_PICKUP            => true,
                self::expected(self::DELIVERY_TYPE) => AbstractConsignment::DELIVERY_TYPE_PICKUP,
            ],
        ]);
    }

    /**
     * @return array
     * @throws \Exception
     * @deprecated
     */
    public function provideCheckoutDataData(): array
    {
        $deliveryDate = $this->generateDeliveryDate();
        return $this->createConsignmentProviderDataset([
            '[DEPRECATED] checkout data' => [
                'checkout_data'                            => sprintf(
                    '{"date":"%s","time":[{"start":"16:00:00","type":4,"price":{"currency":"EUR","amount":0}}],"location":"Primera Sanders","street":"Polderplein","number":"3","postal_code":"2132BA","city":"Hoofddorp","cc":"NL","start_time":"16:00:00","price":0,"price_comment":"retail","comment":"Dit is een Postkantoor. Post en pakketten die u op werkdagen vóór de lichtingstijd afgeeft, bezorgen we binnen Nederland de volgende dag. Op zaterdag worden alléén pakketten die u afgeeft voor 15:00 uur maandag bezorgd.","phone_number":"","opening_hours":{"monday":["11:00-18:00"],"tuesday":["09:00-18:00"],"wednesday":["09:00-18:00"],"thursday":["09:00-18:00"],"friday":["09:00-21:00"],"saturday":["09:00-18:00"],"sunday":["12:00-17:00"]},"distance":"312","latitude":"52.30329367","longitude":"4.69476214","location_code":"176227","retail_network_id":"PNPNL-01","holiday":[]}',
                    $deliveryDate
                ),
                self::expected(self::DELIVERY_DATE)        => $deliveryDate,
                self::expected(self::DELIVERY_TYPE)        => AbstractConsignment::DELIVERY_TYPE_PICKUP,
                self::expected(self::PICKUP_CITY)          => 'Hoofddorp',
                self::expected(self::PICKUP_COUNTRY)       => AbstractConsignment::CC_NL,
                self::expected(self::PICKUP_LOCATION_NAME) => 'Primera Sanders',
                self::expected(self::PICKUP_NUMBER)        => '3',
                self::expected(self::PICKUP_POSTAL_CODE)   => '2132BA',
                self::expected(self::PICKUP_STREET)        => 'Polderplein',
            ],
        ]);
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
     * @todo         fix test
     */
    public function testAutoDetectPickup(array $testData): void
    {
        self::markTestBroken();
        $this->doConsignmentTest($testData);
    }

    /**
     * @param  array $testData
     *
     * @throws \Exception
     * @dataProvider provideCheckoutDataData
     * @todo         fix test
     */
    public function testCheckoutData(array $testData): void
    {
        self::markTestBroken();
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
