<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\tests\SendConsignments\SendReferenceIdentifierConsignmentTest;

use MyParcelNL\Sdk\src\Factory\ConsignmentFactory;
use MyParcelNL\Sdk\src\Helper\MyParcelCollection;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\src\Model\Consignment\BaseConsignment;
use MyParcelNL\Sdk\src\Model\Consignment\PostNLConsignment;

/**
 * Class SendReferenceIdentifierConsignmentTest
 */
class SendReferenceIdentifierConsignmentTest extends \PHPUnit\Framework\TestCase
{

    /**
     * Test one shipment with createConcepts()
     * @throws \Exception
     */
    public function testSendReferenceIdentifierConsignment()
    {
        if (getenv('API_KEY') == null) {
            echo "\033[31m Set MyParcel API-key in 'Environment variables' before running UnitTest. Example: API_KEY=f8912fb260639db3b1ceaef2730a4b0643ff0c31. PhpStorm example: http://take.ms/sgpgU5\n\033[0m";

            return $this;
        }

        foreach ($this->additionProvider() as $consignmentTest) {

            $myParcelCollection = new MyParcelCollection();

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
                ->setEmail('your_email@test.nl')
                ->setPhone($consignmentTest['phone']);

            if (key_exists('package_type', $consignmentTest)) {
                $consignment->setPackageType($consignmentTest['package_type']);
            }

            if (key_exists('label_description', $consignmentTest)) {
                $consignment->setLabelDescription($consignmentTest['label_description']);
            }

            $myParcelCollection->addConsignment($consignment);

            /**
             * Create concept
             */
            $myParcelCollection->createConcepts();

            /**
             * @var AbstractConsignment $savedConsignment
             */
            $savedConsignment = (new BaseConsignment())
                ->setApiKey($consignmentTest['api_key'])
                ->setReferenceId($consignmentTest['reference_identifier']);
            $savedCollection  = (new MyParcelCollection())
                ->addConsignment($savedConsignment)
                ->setLatestData();

            $savedConsignment = $savedCollection->getOneConsignment();

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

            if (key_exists('label_description', $consignmentTest)) {
                $this->assertEquals($consignmentTest['label_description'], $savedConsignment->getLabelDescription(), 'getLabelDescription()');
            }
        }
    }

    /**
     * Data for the test
     *
     * @return array
     * @throws \Exception
     */
    public function additionProvider()
    {
        return [
            [
                'api_key'              => getenv('API_KEY'),
                'carrier_id'           => PostNLConsignment::CARRIER_ID,
                'reference_identifier' => 'prefix_' . (string) (new \DateTime())->getTimestamp(),
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
            ],
        ];
    }
}
