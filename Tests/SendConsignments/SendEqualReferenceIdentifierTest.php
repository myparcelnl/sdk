<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\tests\SendConsignments\SendMultiReferenceIdentifierConsignmentTest;

use MyParcelNL\Sdk\src\Helper\MyParcelCollection;
use MyParcelNL\Sdk\src\Factory\ConsignmentFactory;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\src\Model\Consignment\PostNLConsignment;

class SendEqualReferenceIdentifierTest extends \PHPUnit\Framework\TestCase
{

    private $timestamp;

    public function setUp()
    {
        $this->timestamp = (new \DateTime())->getTimestamp();
    }

    /**
     * Test one shipment with createConcepts()
     *
     * @param array $consignmentTest
     *
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @throws \Exception
     *
     * @dataProvider additionProvider()
     */
    public function testSendEqualReferenceIdentifier(array $consignmentTest): void
    {
        if (getenv('API_KEY') == null) {
            echo "\033[31m Set MyParcel API-key in 'Environment variables' before running UnitTest. Example: API_KEY=f8912fb260639db3b1ceaef2730a4b0643ff0c31. PhpStorm example: http://take.ms/sgpgU5\n\033[0m";

            return;
        }

        $myParcelCollection = new MyParcelCollection();

        $consignment = (ConsignmentFactory::createByCarrierId($consignmentTest['carrier_id']))
            ->setApiKey($consignmentTest['api_key'])
            ->setReferenceId($consignmentTest['reference_identifier'])
            ->setCountry($consignmentTest['cc'])
            ->setPerson($consignmentTest['person'])
            ->setCompany($consignmentTest['company'])
            ->setFullStreet($consignmentTest['full_street'])
            ->setPostalCode($consignmentTest['postal_code'])
            ->setCity($consignmentTest['city'])
            ->setEmail('your_email@test.nl')
            ->setPhone($consignmentTest['phone']);

        $myParcelCollection->addConsignment($consignment);

        /**
         * Create concept
         */
        $myParcelCollection->createConcepts();

        $savedCollection = MyParcelCollection::findByReferenceId($consignmentTest['reference_identifier'], $consignmentTest['api_key']);

        $savedCollection->setLatestData();
        $savedConsignments = $savedCollection->getConsignmentsByReferenceId($consignmentTest['reference_identifier']);
        $this->assertCount(1, $savedConsignments);
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
            'normal_consignment' => [
                [
                    'api_key'              => getenv('API_KEY'),
                    'carrier_id'           => PostNLConsignment::CARRIER_ID,
                    'reference_identifier' => (string) (new \DateTime())->getTimestamp() . '_input',
                    'cc'                   => 'NL',
                    'person'               => 'Reindert',
                    'company'              => 'Big Sale BV',
                    'full_street'          => 'Plein 1940-45 3 b',
                    'street'               => 'Plein 1940-45',
                    'number'               => 3,
                    'number_suffix'        => 'b',
                    'postal_code'          => '2231JE',
                    'city'                 => 'Rijnsburg',
                    'phone'                => '123456',
                ],
            ],
        ];
    }
}
