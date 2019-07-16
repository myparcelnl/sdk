<?php

/**
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

use MyParcelNL\Sdk\src\Factory\ConsignmentFactory;
use MyParcelNL\Sdk\src\Helper\MyParcelCollection;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\src\Model\Consignment\PostNLConsignment;


/**
 * Class SendOneConsignmentTest
 * @package MyParcelNL\Sdk\tests\SendOneConsignmentTest
 */
class SendMuliColoConsignmentTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @return $this
     * @throws \Exception
     */
    public function testMultiColo()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

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

            $myParcelCollection->addMultiCollo($consignment, $consignmentTest['multi_collo_amount']);


            $this->assertCount($consignmentTest['multi_collo_amount'], $myParcelCollection);

            /**
             * Get label
             */
            $myParcelCollection->setLinkOfLabels();

            $this->assertEquals(true, preg_match("#^https://api.myparcel.nl/pdfs#", $myParcelCollection->getLinkOfLabels()), 'Can\'t get link of PDF');

            /** @var AbstractConsignment[] $consignments */
            $consignments = $myParcelCollection->getConsignments();

            $this->assertNotEquals($consignments[0]->getConsignmentId(), $consignments[1]->getConsignmentId());
            $this->assertNotEquals($consignments[0]->getBarcode(), $consignments[1]->getBarcode());
            $this->assertTrue($consignments[0]->isPartOfMultiCollo());
            $this->assertTrue($consignments[1]->isPartOfMultiCollo());
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
                'api_key'            => getenv('API_KEY'),
                'carrier_id'         => PostNLConsignment::CARRIER_ID,
                'cc'                 => 'NL',
                'person'             => 'Reindert',
                'company'            => 'Big Sale BV',
                'full_street_input'  => 'Plein 1940-45 3b',
                'full_street'        => 'Plein 1940-45 3 b',
                'street'             => 'Plein 1940-45',
                'number'             => 3,
                'number_suffix'      => 'b',
                'postal_code'        => '2231JE',
                'city'               => 'Rijnsburg',
                'phone'              => '123456',
                'multi_collo_amount' => 3,
            ],
        ];
    }
}