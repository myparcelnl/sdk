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

namespace MyParcelNL\Sdk\tests\SendConsignments\SendMultiReferenceIdentifierConsignmentTest;

use MyParcelNL\Sdk\src\Helper\MyParcelCollection;
use MyParcelNL\Sdk\src\Model\Repository\MyParcelConsignmentRepository;


/**
 * Class SendOneReferenceIdentifierConsignmentTest
 * @package MyParcelNL\Sdk\tests\SendOneConsignmentTest
 */
class SendEqualReferenceIdentifierTest extends \PHPUnit\Framework\TestCase
{

    private $timestamp;

    public function setUp() {
        $this->timestamp = (new \DateTime())->getTimestamp();
    }

    /**
     * Test one shipment with createConcepts()
     * @throws \Exception
     */
    public function testSendOneConsignment()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        if (getenv('API_KEY') == null) {
            echo "\033[31m Set MyParcel API-key in 'Environment variables' before running UnitTest. Example: API_KEY=f8912fb260639db3b1ceaef2730a4b0643ff0c31. PhpStorm example: http://take.ms/sgpgU5\n\033[0m";
            return $this;
        }

        $myParcelCollection = new MyParcelCollection();

        foreach ($this->additionProvider() as $consignmentTest) {
            $consignment = (new MyParcelConsignmentRepository())
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

            $myParcelCollection->addConsignment($consignment);
        }

        /**
         * Create concept
         */
        $myParcelCollection->createConcepts();

        $savedCollection = new MyParcelCollection();

        foreach ($this->additionProvider() as $consignmentTest) {
            /**
             * @var $savedConsignment MyParcelConsignmentRepository
             */
            $savedConsignment = (new MyParcelConsignmentRepository())
                ->setApiKey($consignmentTest['api_key'])
                ->setReferenceId($consignmentTest['reference_identifier']);
            $savedCollection->addConsignment($savedConsignment);
        }

        $savedCollection->setLatestData();

        $consignmentTest = $this->additionProvider()[0];
        $savedConsignments = $savedCollection->getByReferenceId($consignmentTest['reference_identifier']);
        $this->assertCount(2, $savedConsignments);
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
                'api_key' => getenv('API_KEY'),
                'reference_identifier' => (string)$this->timestamp . '_test',
                'cc' => 'NL',
                'person' => 'Reindert',
                'company' => 'Big Sale BV',
                'full_street_test' => 'Plein 1940-45 3b',
                'full_street' => 'Plein 1940-45 3 b',
                'street' => 'Plein 1940-45',
                'number' => 3,
                'number_suffix' => 'b',
                'postal_code' => '2231JE',
                'city' => 'Rijnsburg',
                'phone' => '123456',
            ],
            [
                'api_key' => getenv('API_KEY'),
                'reference_identifier' => (string)$this->timestamp . '_test',
                'cc' => 'NL',
                'person' => 'Reindert',
                'company' => 'Big Sale BV',
                'full_street_test' => 'Plein 1940-45 3b',
                'full_street' => 'Plein 1940-45 3 b',
                'street' => 'Plein 1940-45',
                'number' => 3,
                'number_suffix' => 'b',
                'postal_code' => '2231JE',
                'city' => 'Rijnsburg',
                'phone' => '123456',
            ],
        ];
    }
}