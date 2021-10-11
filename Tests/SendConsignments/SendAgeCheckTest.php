<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\tests\SendConsignments\SendAgeCheckTest;

use MyParcelNL\Sdk\src\Factory\ConsignmentFactory;
use MyParcelNL\Sdk\src\Helper\MyParcelCollection;
use MyParcelNL\Sdk\src\Model\Consignment\PostNLConsignment;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;

class SendAgeCheckTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @return $this
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @throws \Exception
     */
    public function testSendAgeCheck()
    {
        if (getenv('API_KEY') == null) {
            echo "\033[31m Set MyParcel API-key in 'Environment variables' before running UnitTest. Example: API_KEY=f8912fb260639db3b1ceaef2730a4b0643ff0c31. PhpStorm example: http://take.ms/sgpgU5\n\033[0m";

            return $this;
        }

        foreach ($this->additionProvider() as $consignmentTest) {

            if (isset($consignmentTest['exception'])) {
                $this->expectExceptionMessage($consignmentTest['exception']);
            }

            $myParcelCollection = new MyParcelCollection();

            $consignment = (ConsignmentFactory::createByCarrierId($consignmentTest['carrier_id']))
                ->setApiKey($consignmentTest['api_key'])
                ->setCountry($consignmentTest['cc'])
                ->setPerson($consignmentTest['person'])
                ->setCompany($consignmentTest['company'])
                ->setFullStreet($consignmentTest['full_street'])
                ->setPostalCode($consignmentTest['postal_code'])
                ->setCity($consignmentTest['city'])
                ->setEmail('your_email@test.nl')
                ->setPhone($consignmentTest['phone']);

            if (key_exists('package_type', $consignmentTest)) {
                $consignment->setPackageType($consignmentTest['package_type']);
            }

            if (key_exists('only_recipient_input', $consignmentTest)) {
                $consignment->setOnlyRecipient($consignmentTest['only_recipient_input']);
            }

            if (key_exists('signature_input', $consignmentTest)) {
                $consignment->setSignature($consignmentTest['signature_input']);
            }

            if (key_exists('age_check', $consignmentTest)) {
                $consignment->setAgeCheck($consignmentTest['age_check']);
            }

            $myParcelCollection->addConsignment($consignment);

            /** @var AbstractConsignment $consignment */
            $consignment = $myParcelCollection->createConcepts()->setLatestData()->first();

            $this->assertEquals(true, $consignment->getMyParcelConsignmentId() > 1, 'No id found');
            $this->assertEquals($consignmentTest['api_key'], $consignment->getApiKey(), 'getApiKey()');
            $this->assertEquals($consignmentTest['cc'], $consignment->getCountry(), 'getCountry()');

            if (key_exists('package_type', $consignmentTest)) {
                $this->assertEquals($consignmentTest['package_type'], $consignment->getPackageType(), 'getPackageType()');
            } else {
                $this->assertEquals(1, $consignment->getPackageType(), 'getPackageType()');
            }

            if (key_exists('only_recipient', $consignmentTest)) {
                $this->assertEquals($consignmentTest['only_recipient'], $consignment->isOnlyRecipient(), 'isOnlyRecipient() test' . $consignmentTest['label_description']);
            } else {
                $this->assertEquals(true, $consignment->isOnlyRecipient(), 'isOnlyRecipient() with ageCheck true');
            }

            if (key_exists('signature', $consignmentTest)) {
                $this->assertEquals($consignmentTest['signature'], $consignment->isSignature(), 'isSignature() test' . $consignmentTest['label_description']);
            } else {
                $this->assertEquals(true, $consignment->isSignature(), 'isSignature() with ageCheck true');
            }

            if (key_exists('age_check', $consignmentTest)) {
                $this->assertEquals($consignmentTest['age_check'], $consignment->hasAgeCheck(), 'hasAgeCheck()');
            }
            /**
             * Get label
             */
            $myParcelCollection
                ->setLinkOfLabels();

            $this->assertEquals(true, preg_match("#^https://api.myparcel.nl/pdfs#", $myParcelCollection->getLinkOfLabels()), 'Can\'t get link of PDF');

            /** @var AbstractConsignment $consignment */
            $consignment = $myParcelCollection->getOneConsignment();
            $this->assertStringMatchesFormat('3SMYPA', preg_match("#^3SMYPA#", $consignment->getBarcode()), 'Barcode is not set');
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
            'Normal check'           => [
                'api_key'           => getenv('API_KEY'),
                'carrier_id'        => PostNLConsignment::CARRIER_ID,
                'cc'                => 'NL',
                'person'            => 'Piet',
                'company'           => 'Mega Store',
                'full_street'       => 'Koestraat 55',
                'street'            => 'Koestraat',
                'number'            => 55,
                'number_suffix'     => '',
                'postal_code'       => '2231JE',
                'city'              => 'Katwijk',
                'phone'             => '123-45-235-435',
                'package_type'      => 1,
                'large_format'      => false,
                'age_check'         => false,
                'only_recipient'    => false,
                'signature'         => false,
                'return'            => false,
                'label_description' => '18+ check',
            ],
            'Normal 18+ check'       => [
                'api_key'           => getenv('API_KEY'),
                'carrier_id'        => PostNLConsignment::CARRIER_ID,
                'cc'                => 'NL',
                'person'            => 'Piet',
                'company'           => 'Mega Store',
                'full_street'       => 'Koestraat 55',
                'street'            => 'Koestraat',
                'number'            => 55,
                'number_suffix'     => '',
                'postal_code'       => '2231JE',
                'city'              => 'Katwijk',
                'phone'             => '123-45-235-435',
                'package_type'      => 1,
                'large_format'      => false,
                'age_check'         => true,
                'only_recipient'    => true,
                'signature'         => true,
                'return'            => false,
                'label_description' => '18+ check',
            ],
            '18+ check no signature' => [
                'api_key'              => getenv('API_KEY'),
                'carrier_id'           => PostNLConsignment::CARRIER_ID,
                'cc'                   => 'NL',
                'person'               => 'Piet',
                'company'              => 'Mega Store',
                'full_street'          => 'Koestraat 55',
                'street'               => 'Koestraat',
                'number'               => 55,
                'number_suffix'        => '',
                'postal_code'          => '2231JE',
                'city'                 => 'Katwijk',
                'phone'                => '123-45-235-435',
                'package_type'         => AbstractConsignment::PACKAGE_TYPE_PACKAGE,
                'age_check'            => true,
                'only_recipient_input' => false,
                'only_recipient'       => true,
                'signature_input'      => false,
                'signature'            => true,
                'label_description'    => '18+ check no signature',
            ],
            '18+ check EU shipment'  => [
                'api_key'           => getenv('API_KEY'),
                'carrier_id'        => PostNLConsignment::CARRIER_ID,
                'cc'                => 'BE',
                'person'            => 'BETest',
                'company'           => 'Mega Store',
                'full_street'       => 'hoofdstraat 16',
                'street'            => 'hoofdstraat',
                'number'            => 16,
                'number_suffix'     => '',
                'postal_code'       => '2000',
                'city'              => 'Antwerpen',
                'phone'             => '123-45-235-435',
                'package_type'      => AbstractConsignment::PACKAGE_TYPE_PACKAGE,
                'large_format'      => false,
                'age_check'         => true,
                'only_recipient'    => false,
                'signature_input'   => false,
                'signature'         => false,
                'return'            => false,
                'label_description' => '18+ check no signature',
                'exception'         => 'The age check is not possible with an EU shipment or world shipment',
            ],
        ];
    }
}
