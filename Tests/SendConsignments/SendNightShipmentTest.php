<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\tests\SendConsignments\SendOneConsignmentTest;

use MyParcelNL\Sdk\src\Factory\ConsignmentFactory;
use MyParcelNL\Sdk\src\Helper\MyParcelCollection;
use MyParcelNL\Sdk\src\Concerns\HasDebugLabels;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\src\Model\Consignment\PostNLConsignment;

class SendNightShipmentTest extends \PHPUnit\Framework\TestCase
{
    use HasDebugLabels;

    /**
     * Test one shipment with createConcepts()
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @throws \Exception
     */
    public function testSendNightShipment()
    {
        if (getenv('API_KEY') == null) {
            echo "\033[31m Set MyParcel API-key in 'Environment variables' before running UnitTest. Example: API_KEY=f8912fb260639db3b1ceaef2730a4b0643ff0c31. PhpStorm example: http://take.ms/sgpgU5\n\033[0m";

            return $this;
        }

        foreach ($this->additionProvider() as $consignmentTest) {

            $myParcelCollection = new MyParcelCollection();

            $consignment = (ConsignmentFactory::createByCarrierId($consignmentTest['carrier_id']))
                ->setApiKey($consignmentTest['api_key'])
                ->setCountry($consignmentTest['cc'])
                ->setPerson($consignmentTest['person'])
                ->setCompany($consignmentTest['company'])
                ->setFullStreet($consignmentTest['full_street_input'])
                ->setPostalCode($consignmentTest['postal_code'])
                ->setCity($consignmentTest['city'])
                ->setEmail('your_email@test.nl')
                ->setPhone($consignmentTest['phone']);

            if (key_exists('delivery_date', $consignmentTest)) {
                $consignment->setDeliveryDate($consignmentTest['delivery_date']);
            }

            if (key_exists('package_type', $consignmentTest)) {
                $consignment->setPackageType($consignmentTest['package_type']);
            }

            if (key_exists('signature', $consignmentTest)) {
                $consignment->setSignature($consignmentTest['signature']);
            }

            if (key_exists('label_description', $consignmentTest)) {
                $consignment->setLabelDescription($consignmentTest['label_description']);
            }

            if (key_exists('delivery_type', $consignmentTest)) {
                $consignment->setDeliveryType($consignmentTest['delivery_type']);
            }

            $myParcelCollection->addConsignment($consignment);

            /** @var AbstractConsignment $consignment */
            $consignment = $myParcelCollection->createConcepts()->setLatestData()->first();

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

            if (key_exists('delivery_date', $consignmentTest)) {
                $this->assertEquals($consignmentTest['delivery_date'] . ' 00:00:00', $consignment->getDeliveryDate(), 'getDeliveryDate()');
            }

            if (key_exists('package_type', $consignmentTest)) {
                $this->assertEquals($consignmentTest['package_type'], $consignment->getPackageType(), 'getPackageType()');
            }

            if (key_exists('signature', $consignmentTest)) {
                $this->assertEquals($consignmentTest['signature'], $consignment->isSignature(), 'isSignature()');
            }

            if (key_exists('label_description', $consignmentTest)) {
                $this->assertEquals($consignmentTest['label_description'], $consignment->getLabelDescription(), 'getLabelDescription()');
            }

            if (key_exists('delivery_type', $consignmentTest)) {
                $this->assertEquals($consignmentTest['delivery_type'], $consignment->getDeliveryType(), 'getDeliveryType()');
            }

            /**
             * Get label
             */
            $myParcelCollection
                ->setLinkOfLabels();

            $this->assertEquals(true, preg_match("#^https://api.myparcel.nl/pdfs#", $myParcelCollection->getLinkOfLabels()), 'Can\'t get link of PDF');

            $this->debugLinkOfLabels($myParcelCollection, 'night shipment label');

            /** @var AbstractConsignment $consignment */
            $consignment = $myParcelCollection->getOneConsignment();
            $this->assertEquals(true, preg_match("#^3SMYPA#", $consignment->getBarcode()), 'Barcode is not set');
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
                'api_key'           => getenv('API_KEY'),
                'carrier_id'        => PostNLConsignment::CARRIER_ID,
                'cc'                => 'NL',
                'person'            => 'Piet',
                'company'           => 'Mega Store',
                'full_street_input' => 'Koestraat 55',
                'full_street'       => 'Koestraat 55',
                'street'            => 'Koestraat',
                'number'            => 55,
                'number_suffix'     => '',
                'postal_code'       => '2231JE',
                'city'              => 'Katwijk',
                'phone'             => '123-45-235-435',
                'package_type'      => AbstractConsignment::PACKAGE_TYPE_PACKAGE,
                'delivery_type'     => AbstractConsignment::DELIVERY_TYPE_EVENING,
                'label_description' => 'Label description',
                'delivery_date'     => date("Y-m-d", strtotime("+ 1 day"))
            ],
            [
                'api_key'           => getenv('API_KEY'),
                'carrier_id'        => PostNLConsignment::CARRIER_ID,
                'cc'                => 'NL',
                'person'            => 'Piet',
                'company'           => 'Mega Store',
                'full_street_input' => 'Koestraat 55',
                'full_street'       => 'Koestraat 55',
                'street'            => 'Koestraat',
                'number'            => 55,
                'number_suffix'     => '',
                'postal_code'       => '2231JE',
                'city'              => 'Katwijk',
                'phone'             => '123-45-235-435',
                'signature'         => true,
                'package_type'      => AbstractConsignment::PACKAGE_TYPE_PACKAGE,
                'delivery_type'     => AbstractConsignment::DELIVERY_TYPE_EVENING,
                'label_description' => 'Label description',
                'delivery_date'     => date("Y-m-d", strtotime("+ 1 day"))
            ]
        ];
    }
}
