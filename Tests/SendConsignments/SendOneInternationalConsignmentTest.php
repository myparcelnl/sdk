<?php

/**
 * Create international consignment
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

namespace MyParcelNL\Sdk\tests\SendConsignments\SendOneInternationalConsignmentTest;

use MyParcelNL\Sdk\src\Helper\MyParcelCollection;
use MyParcelNL\Sdk\src\Model\MyParcelCustomsItem;
use MyParcelNL\Sdk\src\Model\Repository\MyParcelConsignmentRepository;


/**
 * Class SendOneInternationalConsignmentTest
 * @package MyParcelNL\Sdk\tests\SendOneConsignmentTest
 */
class SendOneInternationalConsignmentTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test one shipment with createConcepts()
     */
    public function testSendOneConsignment()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        if (getenv('API_KEY') == null) {
            echo "\033[31m Set MyParcel API-key in 'Environment variables' before running UnitTest. Example: API_KEY=f8912fb260639db3b1ceaef2730a4b0643ff0c31. PhpStorm example: http://take.ms/sgpgU5\n\033[0m";
            return $this;
        }

        foreach ($this->additionProvider() as $consignmentTest) {

            $myParcelCollection = new MyParcelCollection();

            $consignment = (new MyParcelConsignmentRepository())
                ->setApiKey($consignmentTest['api_key'])
                ->setCountry($consignmentTest['cc'])
                ->setPerson($consignmentTest['person'])
                ->setCompany($consignmentTest['company'])
                ->setFullStreet($consignmentTest['full_street'])
                ->setPostalCode($consignmentTest['postal_code'])
                ->setCity($consignmentTest['city'])
                ->setEmail('reindert@myparcel.nl')
                ->setPhone($consignmentTest['phone']);

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

            // Add items for international shipments
            foreach ($consignmentTest['custom_items'] as $customItem) {
                $item = (new MyParcelCustomsItem())
                    ->setDescription($customItem['description'])
                    ->setAmount($customItem['amount'])
                    ->setWeight($customItem['weight'])
                    ->setItemValue($customItem['item_value'])
                    ->setClassification($customItem['classification'])
                    ->setCountry($customItem['country']);

                $consignment->addItem($item);
            }

            $myParcelCollection
                ->addConsignment($consignment)
                ->setLinkOfLabels();

            $this->assertEquals(true, preg_match("#^https://api.myparcel.nl/pdfs#", $myParcelCollection->getLinkOfLabels()), 'Can\'t get link of PDF');

            echo "\033[32mGenerated international shipment label: \033[0m";
            print_r($myParcelCollection->getLinkOfLabels());
            echo "\n\033[0m";

            /** @var MyParcelConsignmentRepository $consignment */
            $consignment = $myParcelCollection->getOneConsignment();
            $this->assertEquals(true, preg_match("#^CV#", $consignment->getBarcode()), 'Barcode is not set');

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
                'api_key' => getenv('API_KEY'),
                'cc' => 'CA',
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
                'package_type' => 1,
                'label_description' => 112345,
                'custom_items' => [
                    [
                        'description' => 'Cool Mobile',
                        'amount' => 2,
                        'weight' => 2000,
                        'item_value' => 40000,
                        'classification' => 2008,
                        'country' => 'DU',
                    ]
                ],
            ],
        ];
    }
}