<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\tests\SendConsignments\SendOneConsignmentTest;

use MyParcelNL\Sdk\src\Factory\ConsignmentFactory;
use MyParcelNL\Sdk\src\Helper\MyParcelCollection;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\src\Model\Consignment\BpostConsignment;
use MyParcelNL\Sdk\src\Model\Consignment\DPDConsignment;
use MyParcelNL\Sdk\src\Model\Consignment\PostNLConsignment;

/**
 * Class SendOneConsignmentTest
 */
class SendOneConsignmentTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @return \MyParcelNL\Sdk\tests\SendConsignments\SendOneConsignmentTest\SendOneConsignmentTest
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @throws \Exception
     */
    public function testSendOneConsignment()
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
                ->setEmail('your_email@test.nl')
                ->setPhone($consignmentTest['phone']);

            if (key_exists('weight', $consignmentTest)) {
                $consignment->setTotalWeight($consignmentTest['weight']);
            }

            if (key_exists('package_type', $consignmentTest)) {
                $consignment->setPackageType($consignmentTest['package_type']);
            }

            if (key_exists('large_format', $consignmentTest)) {
                $consignment->setLargeFormat($consignmentTest['large_format']);
            }

            if (key_exists('age_check', $consignmentTest)) {
                $consignment->setAgeCheck($consignmentTest['age_check']);
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

            if (key_exists('weight', $consignmentTest)) {
                $this->assertEquals($consignmentTest['weight'], $consignment->getTotalWeight(), 'weight()');
            }

            if (key_exists('number_suffix', $consignmentTest)) {
                $this->assertEquals($consignmentTest['number_suffix'], $consignment->getNumberSuffix(), 'getNumberSuffix()');
            }

            if (key_exists('box_number', $consignmentTest)) {
                $this->assertEquals($consignmentTest['box_number'], $consignment->getBoxNumber(), 'getBoxNumber()');
            }

            if (key_exists('package_type', $consignmentTest)) {
                $this->assertEquals($consignmentTest['package_type'], $consignment->getPackageType(), 'getPackageType()');
            } else {
                $this->assertEquals(1, $consignment->getPackageType(), 'getPackageType()');
            }

            if (key_exists('large_format', $consignmentTest)) {
                $this->assertEquals($consignmentTest['large_format'], $consignment->isLargeFormat(), 'isLargeFormat()');
            }

            if (key_exists('age_check', $consignmentTest)) {
                $this->assertEquals($consignmentTest['age_check'], $consignment->hasAgeCheck(), 'hasAgeCheck()');
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
                // Since 1-1-2019 it is not possible to get an insurance with 50 euros from MyParcel, instead the insurance 100 euros has been added.
                $insurance = $consignmentTest['insurance'];

                if ($insurance == 50) {
                    $insurance = 100;
                }

                $this->assertEquals($insurance, $consignment->getInsurance(), 'getInsurance()');
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
                'api_key'           => getenv('API_KEY_BE'),
                'carrier_id'        => DPDConsignment::CARRIER_ID,
                'cc'                => 'BE',
                'person'            => 'Richard',
                'company'           => 'Big Sale BV',
                'full_street_input' => 'Hoofdweg 16',
                'full_street'       => 'Hoofdweg 16',
                'street'            => 'Hoofdweg',
                'number'            => 16,
                'box_number'        => '',
                'postal_code'       => '2000',
                'city'              => 'Antwerpen',
                'phone'             => '123456',
                'weight'            => 100,
            ],
            [
                'api_key'           => getenv('API_KEY_BE'),
                'carrier_id'        => BpostConsignment::CARRIER_ID,
                'cc'                => 'BE',
                'person'            => 'RichardTest',
                'company'           => 'Big Sale BV',
                'full_street_input' => 'Hoofdweg 16',
                'full_street'       => 'Hoofdweg 16',
                'street'            => 'Hoofdweg',
                'number'            => 16,
                'box_number'        => '',
                'postal_code'       => '2000',
                'city'              => 'Antwerpen',
                'phone'             => '123456',
                'weight'            => 1500,
            ],
            [
                'api_key'           => getenv('API_KEY'),
                'carrier_id'        => PostNLConsignment::CARRIER_ID,
                'cc'                => 'FR',
                'person'            => 'Richard',
                'company'           => 'Big Sale BV',
                'full_street_input' => 'Hoofdweg 16',
                'full_street'       => 'Hoofdweg 16',
                'street'            => '',
                'number'            => null,
                'postal_code'       => '2000',
                'city'              => 'Antwerpen',
                'phone'             => '123456',
            ],
            [
                'api_key'           => getenv('API_KEY'),
                'carrier_id'        => PostNLConsignment::CARRIER_ID,
                'cc'                => 'NL',
                'person'            => 'Reindert',
                'company'           => 'Big Sale BV',
                'full_street_input' => 'Plein 1940-45 3b',
                'full_street'       => 'Plein 1940-45 3 b',
                'street'            => 'Plein 1940-45',
                'number'            => 3,
                'number_suffix'     => 'b',
                'postal_code'       => '2231JE',
                'city'              => 'Rijnsburg',
                'phone'             => '123456',
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
                'package_type'      => AbstractConsignment::PACKAGE_TYPE_PACKAGE,
                'large_format'      => false,
                'age_check'         => false,
                'only_recipient'    => false,
                'signature'         => false,
                'return'            => false,
                'label_description' => 'Label description',
            ],
            [
                'api_key'                => getenv('API_KEY'),
                'carrier_id'             => PostNLConsignment::CARRIER_ID,
                'cc'                     => 'NL',
                'person'                 => 'Piet',
                'company'                => 'Mega Store',
                'full_street_input'      => 'Wethouder Fierman Eduard Meerburg senior kade 14 t',
                'full_street'            => 'Wethouder Fierman Eduard Meerburg senior 14 t',
                'street'                 => 'Wethouder Fierman Eduard Meerburg senior',
                'street_additional_info' => 'kade',
                'number'                 => 14,
                'number_suffix'          => 't',
                'postal_code'            => '2231JE',
                'city'                   => 'Katwijk',
                'phone'                  => '123-45-235-435',
                'package_type'           => AbstractConsignment::PACKAGE_TYPE_PACKAGE,
                'large_format'           => false,
                'age_check'              => false,
                'only_recipient'         => false,
                'signature'              => false,
                'return'                 => false,
                'label_description'      => 'Label description',
            ],
            [
                'api_key'           => getenv('API_KEY'),
                'carrier_id'        => PostNLConsignment::CARRIER_ID,
                'cc'                => 'NL',
                'person'            => 'The insurance man',
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
                'large_format'      => true,
                'age_check'         => false,
                'only_recipient'    => true,
                'signature'         => true,
                'return'            => true,
                'label_description' => 1234,
                'insurance'         => 250,
            ],
            [
                'api_key'           => getenv('API_KEY'),
                'carrier_id'        => PostNLConsignment::CARRIER_ID,
                'cc'                => 'NL',
                'person'            => 'The insurance man',
                'company'           => 'Mega Store',
                'full_street_input' => 'Koestraat\n55',
                'full_street'       => 'Koestraat 55',
                'street'            => 'Koestraat',
                'number'            => 55,
                'number_suffix'     => '',
                'postal_code'       => '2231JE',
                'city'              => 'Katwijk',
                'phone'             => '123-45-235-435',
                'package_type'      => AbstractConsignment::PACKAGE_TYPE_PACKAGE,
                'large_format'      => true,
                'age_check'         => false,
                'only_recipient'    => true,
                'signature'         => true,
                'return'            => true,
                'label_description' => 1234,
                'insurance'         => 250,
            ],
            [
                'api_key'           => getenv('API_KEY'),
                'carrier_id'        => PostNLConsignment::CARRIER_ID,
                'cc'                => 'NL',
                'person'            => 'The insurance man',
                'company'           => 'Mega Store',
                'full_street_input' => 'Runstraat 14 3',
                'full_street'       => 'Runstraat 14 3',
                'street'            => 'Runstraat 14',
                'number'            => 3,
                'number_suffix'     => '',
                'postal_code'       => '1016GK',
                'city'              => 'Amsterdam',
                'phone'             => '123-45-235-435',
                'package_type'      => AbstractConsignment::PACKAGE_TYPE_PACKAGE,
                'large_format'      => true,
                'age_check'         => false,
                'only_recipient'    => true,
                'signature'         => true,
                'return'            => true,
                'label_description' => 1234,
                'insurance'         => 250,
            ],
            [
                'api_key'           => getenv('API_KEY'),
                'carrier_id'        => PostNLConsignment::CARRIER_ID,
                'cc'                => 'BE',
                'person'            => 'Richard',
                'company'           => 'MyParcelNL',
                'full_street_input' => 'Berghelaan\n34\n2',
                'full_street'       => 'Berghelaan 34 2',
                'street'            => 'Berghelaan',
                'number'            => null,
                'number_suffix'     => '',
                'postal_code'       => '2630',
                'city'              => 'Aartselaar',
                'phone'             => '123-45-235-435',
                'package_type'      => AbstractConsignment::PACKAGE_TYPE_PACKAGE,
                'large_format'      => false,
                'age_check'         => false,
                'only_recipient'    => false,
                'signature'         => false,
                'return'            => false,
                'label_description' => 1234,
                'insurance'         => 500,
            ],
        ];
    }
}
