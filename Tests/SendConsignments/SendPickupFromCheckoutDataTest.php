<?php

/**
 * Create one concept
 *
 * If you want to add improvements, please create a fork in our GitHub:
 * https://github.com/myparcelnl
 *
 * @author      Reindert Vetter <reindert@myparcel.nl>
 * @copyright   2010-2017 MyParcel
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US  CC BY-NC-ND 3.0 NL
 * @link        https://github.com/myparcelnl/sdk
 * @since       File available since Release v0.1.0
 */

namespace MyParcelNL\Sdk\tests\SendConsignments\SendOneConsignmentTest;

use MyParcelNL\Sdk\src\Helper\MyParcelCollection;
use MyParcelNL\Sdk\src\Model\Repository\MyParcelConsignmentRepository;


/**
 * Class SendPickupFromCheckoutDataTest
 * @package MyParcelNL\Sdk\tests\SendOneConsignmentTest
 */
class SendPickupFromCheckoutDataTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test one shipment with createConcepts()
     */
    public function testSendOneConsignment()
    {
        foreach ($this->additionProvider() as $consignmentTest) {

            $myParcelCollection = new MyParcelCollection();

            $consignment = (new MyParcelConsignmentRepository())
                ->setApiKey($consignmentTest['api_key'])
                ->setCountry($consignmentTest['cc'])
                ->setPerson($consignmentTest['person'])
                ->setCompany($consignmentTest['company'])
                ->setFullStreet($consignmentTest['full_street_test'])
                ->setPostalCode($consignmentTest['postal_code'])
                ->setCity($consignmentTest['city'])
                ->setEmail('reindert@myparcel.nl')
                ->setPhone($consignmentTest['phone']);

            if (key_exists('checkout_data', $consignmentTest)) {
                $consignment->setPickupAddressFromCheckout($consignmentTest['checkout_data']);
            }

            if (key_exists('package_type', $consignmentTest)) {
                $consignment->setPackageType($consignmentTest['package_type']);
            }

            if (key_exists('large_format', $consignmentTest)) {
                $consignment->setLargeFormat($consignmentTest['large_format']);
            }

            if (key_exists('only_recipient', $consignmentTest)) {
                $consignment->setOnlyRecipient($consignmentTest['only_recipient']);
            }

            if (key_exists('signature', $consignmentTest)) {
                $consignment->setSignature($consignmentTest['signature']);
            }

            if (key_exists('return', $consignmentTest)) {
                $consignment->setReturn($consignmentTest['return']);
            }

            if (key_exists('insurance', $consignmentTest)) {
                $consignment->setInsurance($consignmentTest['insurance']);
            }

            if (key_exists('label_description', $consignmentTest)) {
                $consignment->setLabelDescription($consignmentTest['label_description']);
            }

            if (key_exists('delivery_type', $consignmentTest)) {
                $consignment->setDeliveryType($consignmentTest['delivery_type']);
            }

            $myParcelCollection->addConsignment($consignment);

            /**
             * Create concept
             */
            $myParcelCollection->createConcepts()->setLatestData();

            $this->assertEquals(true, $consignment->getMyParcelConsignmentId() > 1, 'No id found');
            $this->assertEquals($consignmentTest['api_key'], $consignment->getApiKey(), 'getApiKey()');
            $this->assertEquals($consignmentTest['cc'], $consignment->getCountry(), 'getCountry()');
            $this->assertEquals($consignmentTest['person'], $consignment->getPerson(), 'getPerson()');
            $this->assertEquals($consignmentTest['company'], $consignment->getCompany(), 'getCompany()');
            $this->assertEquals($consignmentTest['full_street'], $consignment->getFullStreet(), 'getFullStreet()');
            $this->assertEquals($consignmentTest['number'], $consignment->getNumber(), 'getNumber()');
            $this->assertEquals($consignmentTest['number_suffix'], $consignment->getNumberSuffix(), 'getNumberSuffix()');
            $this->assertEquals($consignmentTest['postal_code'], $consignment->getPostalCode(), 'getPostalCode()');
            $this->assertEquals($consignmentTest['city'], $consignment->getCity(), 'getCity()');
            $this->assertEquals($consignmentTest['phone'], $consignment->getPhone(), 'getPhone()');

            if (key_exists('package_type', $consignmentTest)) {
                $this->assertEquals($consignmentTest['package_type'], $consignment->getPackageType(), 'getPackageType()');
            }

            if (key_exists('large_format', $consignmentTest)) {
                $this->assertEquals($consignmentTest['large_format'], $consignment->isLargeFormat(), 'isLargeFormat()');
            }

            if (key_exists('only_recipient', $consignmentTest)) {
                $this->assertEquals($consignmentTest['only_recipient'], $consignment->isOnlyRecipient(), 'isOnlyRecipient()');
            }

            if (key_exists('signature', $consignmentTest)) {
                $this->assertEquals($consignmentTest['signature'], $consignment->isSignature(), 'isSignature()');
            }

            if (key_exists('return', $consignmentTest)) {
                $this->assertEquals($consignmentTest['return'], $consignment->isReturn(), 'isReturn()');
            }

            if (key_exists('label_description', $consignmentTest)) {
                $this->assertEquals($consignmentTest['label_description'], $consignment->getLabelDescription(), 'getLabelDescription()');
            }

            if (key_exists('insurance', $consignmentTest)) {
                $this->assertEquals($consignmentTest['insurance'], $consignment->getInsurance(), 'getInsurance()');
            }

            if (key_exists('delivery_type', $consignmentTest)) {
                $this->assertEquals($consignmentTest['delivery_type'], $consignment->getDeliveryType(), 'getDeliveryType()');
            }

            $this->assertEquals($consignmentTest['delivery_type'], $consignment->getDeliveryType(), 'getDeliveryType()');
            $this->assertEquals($consignmentTest['pickup_postal_code'], $consignment->getPickupPostalCode(), 'getPickupPostalCode()');
            $this->assertEquals($consignmentTest['pickup_street'], $consignment->getPickupStreet(), 'getPickupStreet()');
            $this->assertEquals($consignmentTest['pickup_city'], $consignment->getPickupCity(), 'getPickupCity()');
            $this->assertEquals($consignmentTest['pickup_number'], $consignment->getPickupNumber(), 'getPickupNumber()');
            $this->assertEquals($consignmentTest['pickup_location_name'], $consignment->getPickupLocationName(), 'getPickupLocationName()');

            /**
             * Get label
             */
            $myParcelCollection
                ->setLinkOfLabels();

            $this->assertEquals(true, preg_match("#^https://api.myparcel.nl/pdfs#", $myParcelCollection->getLinkOfLabels()), 'Can\'t get link of PDF');

            print_r($myParcelCollection->getLinkOfLabels());
            echo '
';

            /** @var MyParcelConsignmentRepository $consignment */
            $consignment = $myParcelCollection->getOneConsignment();
            $this->assertEquals(true, preg_match("#^3SMYPA#", $consignment->getBarcode()), 'Barcode is not set');

            /** @todo; clear consignment in MyParcelCollection */
        }
    }

    /**
     * Data for the test
     *
     * @return array
     */
    public function additionProvider()
    {
        return [
            [
                'api_key' => '94a98610ab9bf67a82873196c9ca688c601c179a',
                'cc' => 'NL',
                'person' => 'Piet',
                'company' => 'Mega Store',
                'full_street_test' => 'Koestraat 55',
                'full_street' => 'Koestraat 55',
                'street' => 'Koestraat',
                'number' => 55,
                'number_suffix' => '',
                'postal_code' => '2231JE',
                'city' => 'Katwijk',
                'phone' => '123-45-235-435',
                'package_type' => 1,
                'delivery_type' => 4,
                'label_description' => 'Label description',
                'checkout_data' => '{"date":"2019-05-31","time":[{"start":"16:00:00","type":4,"price":{"amount":0,"currency":"EUR"}}],"location":"The Read Shop","street":"Anjelierenstraat","number":"43","postal_code":"2231GT","city":"Rijnsburg","start_time":"16:00:00","price":0,"price_comment":"retail","comment":"Dit is een Postkantoor. Post en pakketten die u op werkdagen vóór de lichtingstijd afgeeft, bezorgen we binnen Nederland de volgende dag.","phone_number":"071-4023063","opening_hours":{"monday":["08:00-18:00"],"tuesday":["08:00-18:00"],"wednesday":["08:00-18:00"],"thursday":["08:00-18:00"],"friday":["08:00-19:00"],"saturday":["08:00-18:00"],"sunday":[]},"distance":"253","location_code":"163463","options":{"signature":false,"only_recipient":false}}',
                'pickup_postal_code' => '2231GT',
                'pickup_street' => 'Anjelierenstraat',
                'pickup_city' => 'Rijnsburg',
                'pickup_number' => '43',
                'pickup_location_name' => 'The Read Shop',
            ],
            [
                'api_key' => '94a98610ab9bf67a82873196c9ca688c601c179a',
                'cc' => 'NL',
                'person' => 'Piet',
                'company' => 'Mega Store',
                'full_street_test' => 'Koestraat 55',
                'full_street' => 'Koestraat 55',
                'street' => 'Koestraat',
                'number' => 55,
                'number_suffix' => '',
                'postal_code' => '2231JE',
                'city' => 'Katwijk',
                'phone' => '123-45-235-435',
                'package_type' => 1,
                'delivery_type' => 5,
                'label_description' => 'Label description',
                'checkout_data' => '{"date":"2019-05-31","time":[{"start":"16:00:00","type":4,"price":{"amount":0,"currency":"EUR"}},{"start":"08:30:00","type":5,"price":{"amount":125,"currency":"EUR"}}],"location":"KantoorExpert Katwijk","street":"Scheepmakerstraat","number":"63","postal_code":"2222AB","city":"Katwijk","start_time":"08:30:00","price":125,"price_comment":"retailexpress","comment":"Dit is een Business Point. Post en pakketten die u op werkdagen vóór de lichtingstijd afgeeft, bezorgen we binnen Nederland de volgende dag.","phone_number":"088-3224400","opening_hours":{"monday":["08:00-18:30"],"tuesday":["08:00-18:30"],"wednesday":["08:00-18:30"],"thursday":["08:00-18:30"],"friday":["08:00-18:30"],"saturday":["08:00-09:00"],"sunday":[]},"distance":"1006","location_code":"159146","options":{"signature":false,"only_recipient":false}}',
                'pickup_postal_code' => '2222AB',
                'pickup_street' => 'Scheepmakerstraat',
                'pickup_city' => 'Katwijk',
                'pickup_number' => '63',
                'pickup_location_name' => 'KantoorExpert Katwijk',
            ]
        ];
    }
}