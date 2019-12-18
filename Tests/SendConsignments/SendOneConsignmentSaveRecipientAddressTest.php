<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\tests\SendConsignments\SendOneConsignmentSaveRecipientAddressTest;

use MyParcelNL\Sdk\src\Factory\ConsignmentFactory;
use MyParcelNL\Sdk\src\Helper\MyParcelCollection;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\src\Model\Consignment\PostNLConsignment;

class SendOneConsignmentSaveRecipientAddressTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @return \MyParcelNL\Sdk\tests\SendConsignments\SendOneConsignmentSaveRecipientAddressTest\SendOneConsignmentSaveRecipientAddressTest
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @throws \Exception
     */
    public function testSendOneConsignmentSaveRecipientAddress()
    {
        if (getenv('API_KEY') == null) {
            echo "\033[31m Set MyParcel API-key in 'Environment variables' before running UnitTest. Example: API_KEY=f8912fb260639db3b1ceaef2730a4b0643ff0c31. PhpStorm example: http://take.ms/sgpgU5\n\033[0m";

            return $this;
        }

        foreach ($this->additionProvider() as $consignmentTest) {

            $myParcelCollection = new MyParcelCollection();
            $consignment        = (ConsignmentFactory::createByCarrierId($consignmentTest['carrier_id']))
                ->setApiKey($consignmentTest['api_key'])
                ->setCountry($consignmentTest['cc'])
                ->setPerson($consignmentTest['person'])
                ->setCompany($consignmentTest['company'])
                ->setFullStreet($consignmentTest['full_street_input'])
                ->setPostalCode($consignmentTest['postal_code'])
                ->setCity($consignmentTest['city'])
                ->setEmail($consignmentTest['email'])
                ->setPhone($consignmentTest['phone']);

            if (key_exists('package_type', $consignmentTest)) {
                $consignment->setPackageType($consignmentTest['package_type']);
            }

            if (key_exists('save_recipient_address', $consignmentTest)) {
                $consignment->setAutoSaveRecipientAddress($consignmentTest['save_recipient_address']);
            }

            if (key_exists('label_description', $consignmentTest)) {
                $consignment->setLabelDescription($consignmentTest['label_description']);
            }
            $myParcelCollection->addConsignment($consignment);

            /** @var AbstractConsignment $consignment */
            $consignment = $myParcelCollection->createConcepts()->setLatestData()->first();
            $this->assertEquals(true, $consignment->getConsignmentId() > 1, 'No id found');
            $this->assertEquals($consignmentTest['api_key'], $consignment->getApiKey(), 'getApiKey()');
            $this->assertEquals($consignmentTest['cc'], $consignment->getCountry(), 'getCountry()');
            $this->assertEquals($consignmentTest['person'], $consignment->getPerson(), 'getPerson()');
            $this->assertEquals($consignmentTest['company'], $consignment->getCompany(), 'getCompany()');
            $this->assertEquals($consignmentTest['full_street'], $consignment->getFullStreet(), 'getFullStreet()');
            $this->assertEquals($consignmentTest['number'], $consignment->getNumber(), 'getNumber()');
            $this->assertEquals($consignmentTest['postal_code'], $consignment->getPostalCode(), 'getPostalCode()');
            $this->assertEquals($consignmentTest['city'], $consignment->getCity(), 'getCity()');
            $this->assertEquals($consignmentTest['phone'], $consignment->getPhone(), 'getPhone()');

            if (key_exists('number_suffix', $consignmentTest)) {
                $this->assertEquals($consignmentTest['number_suffix'], $consignment->getNumberSuffix(), 'getNumberSuffix()');
            }

            if (key_exists('package_type', $consignmentTest)) {
                $this->assertEquals($consignmentTest['package_type'], $consignment->getPackageType(), 'getPackageType()');
            } else {
                $this->assertEquals(1, $consignment->getPackageType(), 'getPackageType()');
            }

            if (key_exists('label_description', $consignmentTest)) {
                $this->assertEquals($consignmentTest['label_description'], $consignment->getLabelDescription(), 'getLabelDescription()');
            }

            if (key_exists('save_recipient_address', $consignmentTest)) {
                $consignment->setAutoSaveRecipientAddress($consignmentTest['save_recipient_address']);
            }

            /**
             * Get label
             */
            $myParcelCollection
                ->setLinkOfLabels();

            $this->assertEquals(true, preg_match("#^https://api.myparcel.nl/pdfs#", $myParcelCollection->getLinkOfLabels()), 'Can\'t get link of PDF');

            /** @var AbstractConsignment $consignment */
            $consignment = $myParcelCollection->getOneConsignment();
            $this->assertEquals(true, preg_match("#^3SMYPA|\d{14,24}#", $consignment->getBarcode()), 'Barcode is not set');
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
                'api_key'                => getenv('API_KEY'),
                'carrier_id'             => PostNLConsignment::CARRIER_ID,
                'cc'                     => 'NL',
                'person'                 => 'Richard',
                'company'                => 'MyParcel',
                'full_street_input'      => 'Hoofdweg 679',
                'full_street'            => 'Hoofdweg 679',
                'street'                 => 'Hoofdweg',
                'number'                 => 679,
                'number_suffix'          => '',
                'postal_code'            => '2131BC',
                'city'                   => 'Hoofddorp',
                'phone'                  => '123-45-235-435',
                'email'                  => 'your_email@test.nl',
                'package_type'           => AbstractConsignment::PACKAGE_TYPE_PACKAGE,
                'label_description'      => 1234,
                'save_recipient_address' => true,
            ],
            [
                'api_key'                => getenv('API_KEY'),
                'carrier_id'             => PostNLConsignment::CARRIER_ID,
                'cc'                     => 'NL',
                'person'                 => 'Richard',
                'company'                => 'MyParcel',
                'full_street_input'      => 'Hoofdweg 677',
                'full_street'            => 'Hoofdweg 677',
                'street'                 => 'Hoofdweg',
                'number'                 => 677,
                'number_suffix'          => '',
                'postal_code'            => '2131BC',
                'city'                   => 'Hoofddorp',
                'phone'                  => '123-45-235-435',
                'email'                  => 'your_email@test.nl',
                'package_type'           => AbstractConsignment::PACKAGE_TYPE_PACKAGE,
                'label_description'      => 1234,
                'save_recipient_address' => false,
            ],
        ];
    }
}
