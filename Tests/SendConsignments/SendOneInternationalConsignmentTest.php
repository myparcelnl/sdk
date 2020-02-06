<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\tests\SendConsignments\SendOneInternationalConsignmentTest;

use MyParcelNL\Sdk\src\Factory\ConsignmentFactory;
use MyParcelNL\Sdk\src\Helper\MyParcelCollection;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\src\Model\Consignment\PostNLConsignment;
use MyParcelNL\Sdk\src\Model\MyParcelCustomsItem;
use MyParcelNL\Sdk\src\Concerns\HasDebugLabels;

/**
 * Class SendOneInternationalConsignmentTest
 */
class SendOneInternationalConsignmentTest extends \PHPUnit\Framework\TestCase
{
    use HasDebugLabels;

    /**
     * Test one shipment with createConcepts()
     * @throws \Exception
     */
    public function testSendOneInternationalConsignment()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', '1');

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
                ->setFullStreet($consignmentTest['full_street'])
                ->setPostalCode($consignmentTest['postal_code'])
                ->setCity($consignmentTest['city'])
                ->setEmail('your_email@test.nl')
                ->setPhone($consignmentTest['phone'])
                ->setContents($consignmentTest['contents'])
                ->setInvoice($consignmentTest['invoice']);

            if (key_exists('package_type', $consignmentTest)) {
                $consignment->setPackageType($consignmentTest['package_type']);
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

            $this->debugLinkOfLabels($myParcelCollection, 'international shipment');

            /** @var AbstractConsignment $consignment */
            $consignment = $myParcelCollection->getOneConsignment();
            $this->assertEquals(true, preg_match("#^C#", $consignment->getBarcode()), 'Barcode is not set');
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
                'cc'                => 'CA',
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
                'package_type'      => AbstractConsignment::PACKAGE_TYPE_PACKAGE,
                'contents'          => 1,
                'label_description' => 112345,
                'invoice'           => '1231235345345',
                'custom_items'      => [
                    [
                        'description'    => 'Cool Mobile',
                        'amount'         => 2,
                        'weight'         => 2000,
                        'item_value'     => 40000,
                        'classification' => 2008,
                        'country'        => 'DE',
                    ]
                ],
            ],
        ];
    }
}
