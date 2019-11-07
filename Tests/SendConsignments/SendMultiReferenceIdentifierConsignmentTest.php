<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\tests\SendConsignments\SendMultiReferenceIdentifierConsignmentTest;

use MyParcelNL\Sdk\src\Factory\ConsignmentFactory;
use MyParcelNL\Sdk\src\Helper\MyParcelCollection;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\src\Model\Consignment\PostNLConsignment;

class SendMultiReferenceIdentifierConsignmentTest extends \PHPUnit\Framework\TestCase
{

    private $timestamp;

    public function setUp()
    {
        $this->timestamp = (new \DateTime())->getTimestamp();
    }

    /**
     * Test one shipment with createConcepts()
     * @throws \Exception
     */
    public function testSendMultiReferenceIdentifierConsignment()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', '1');

        if (getenv('API_KEY') == null) {
            echo "\033[31m Set MyParcel API-key in 'Environment variables' before running UnitTest. Example: API_KEY=f8912fb260639db3b1ceaef2730a4b0643ff0c31. PhpStorm example: http://take.ms/sgpgU5\n\033[0m";

            return $this;
        }

        $myParcelCollection = new MyParcelCollection();

        foreach ($this->additionProvider() as $consignmentTest) {
            $consignment = (ConsignmentFactory::createByCarrierId($consignmentTest['carrier_id']))
                ->setApiKey($consignmentTest['api_key'])
                ->setReferenceId($consignmentTest['reference_identifier'])
                ->setCountry($consignmentTest['cc'])
                ->setPerson($consignmentTest['person'])
                ->setCompany($consignmentTest['company'])
                ->setStreet($consignmentTest['street'])
                ->setNumber((string) $consignmentTest['number'])
                ->setNumberSuffix($consignmentTest['number_suffix'])
                ->setPostalCode($consignmentTest['postal_code'])
                ->setCity($consignmentTest['city'])
                ->setEmail($consignmentTest['email'])
                ->setPhone($consignmentTest['phone']);

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
        }

        /**
         * Create concept
         */
        $myParcelCollection->createConcepts();

        $savedCollection = new MyParcelCollection();

        foreach ($this->additionProvider() as $consignmentTest) {
            /**
             * @var $savedConsignment AbstractConsignment
             */
            $savedConsignment = (ConsignmentFactory::createByCarrierId($consignmentTest['carrier_id']))
                ->setApiKey($consignmentTest['api_key'])
                ->setReferenceId($consignmentTest['reference_identifier']);

            $savedCollection->addConsignment($savedConsignment);
        }

        $savedCollection->setLatestData();

        $consignmentTest  = $this->additionProvider()[0];
        $savedConsignment = $savedCollection->getConsignmentsByReferenceId($consignmentTest['reference_identifier'])->first();

        $this->assertEquals(true, $savedConsignment->getMyParcelConsignmentId() > 1, 'No id found');
        $this->assertEquals($consignmentTest['api_key'], $savedConsignment->getApiKey(), 'getApiKey()');
        $this->assertEquals($consignmentTest['reference_identifier'], $savedConsignment->getReferenceId(), 'referenceId()');
        $this->assertEquals($consignmentTest['cc'], $savedConsignment->getCountry(), 'getCountry()');
        $this->assertEquals($consignmentTest['person'], $savedConsignment->getPerson(), 'getPerson()');
        $this->assertEquals($consignmentTest['company'], $savedConsignment->getCompany(), 'getCompany()');
        $this->assertEquals($consignmentTest['full_street'], $savedConsignment->getFullStreet(), 'getFullStreet()');
        $this->assertEquals($consignmentTest['number'], $savedConsignment->getNumber(), 'getNumber()');
        $this->assertEquals($consignmentTest['number_suffix'], $savedConsignment->getNumberSuffix(), 'getNumberSuffix()');
        $this->assertEquals($consignmentTest['postal_code'], $savedConsignment->getPostalCode(), 'getPostalCode()');
        $this->assertEquals($consignmentTest['city'], $savedConsignment->getCity(), 'getCity()');
        $this->assertEquals($consignmentTest['phone'], $savedConsignment->getPhone(), 'getPhone()');

        if (key_exists('package_type', $consignmentTest)) {
            $this->assertEquals($consignmentTest['package_type'], $savedConsignment->getPackageType(), 'getPackageType()');
        }

        if (key_exists('large_format', $consignmentTest)) {
            $this->assertEquals($consignmentTest['large_format'], $savedConsignment->isLargeFormat(), 'isLargeFormat()');
        }

        if (key_exists('age_check', $consignmentTest)) {
            $this->assertEquals($consignmentTest['age_check'], $savedConsignment->hasAgeCheck(), 'hasAgeCheck()');
        }

        if (key_exists('only_recipient', $consignmentTest)) {
            $this->assertEquals($consignmentTest['only_recipient'], $savedConsignment->isOnlyRecipient(), 'isOnlyRecipient()');
        }

        if (key_exists('signature', $consignmentTest)) {
            $this->assertEquals($consignmentTest['signature'], $savedConsignment->isSignature(), 'isSignature()');
        }

        if (key_exists('return', $consignmentTest)) {
            $this->assertEquals($consignmentTest['return'], $savedConsignment->isReturn(), 'isReturn()');
        }

        if (key_exists('label_description', $consignmentTest)) {
            $this->assertEquals($consignmentTest['label_description'], $savedConsignment->getLabelDescription(), 'getLabelDescription()');
        }

        if (key_exists('insurance', $consignmentTest)) {
            $this->assertEquals($consignmentTest['insurance'], $savedConsignment->getInsurance(), 'getInsurance()');
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
                'api_key'              => getenv('API_KEY'),
                'reference_identifier' => (string) $this->timestamp . '_input3',
                'carrier_id'           => PostNLConsignment::CARRIER_ID,
                'cc'                   => 'NL',
                'person'               => 'Reindert',
                'company'              => 'Big Sale BV',
                'full_street_input'    => 'Plein 1940-45 3b',
                'full_street'          => 'Plein 1940-45 3 b',
                'street'               => 'Plein 1940-45',
                'number'               => 3,
                'number_suffix'        => 'b',
                'postal_code'          => '2231JE',
                'city'                 => 'Rijnsburg',
                'phone'                => '123456',
                'email'                => 'your_email@test.nl',
            ],
            [
                'api_key'              => getenv('API_KEY'),
                'reference_identifier' => (string) $this->timestamp . '_input2',
                'carrier_id'           => PostNLConsignment::CARRIER_ID,
                'cc'                   => 'NL',
                'person'               => 'Reindert',
                'company'              => 'Big Sale BV',
                'full_street_input'    => 'Plein 1940-45 3b',
                'full_street'          => 'Plein 1940-45 3 b',
                'street'               => 'Plein 1940-45',
                'number'               => 3,
                'number_suffix'        => 'b',
                'postal_code'          => '2231JE',
                'city'                 => 'Rijnsburg',
                'phone'                => '123456',
                'email'                => 'your_email@test.nl',
            ],
        ];
    }
}
