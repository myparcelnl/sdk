<?php

/**
 * Test for split addresses from full street
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

namespace MyParcelNL\Sdk\tests\SendConsignments\
SendOneConsignmentTest;

use MyParcelNL\Sdk\src\Helper\MyParcelCollection;
use MyParcelNL\Sdk\src\Model\MyParcelRequest;
use MyParcelNL\Sdk\src\Model\Repository\MyParcelConsignmentRepository;


/**
 * Class SendOneConsignmentTest
 * @package MyParcelNL\Sdk\tests\SendConsignmentsTest
 */
class SendMultipleConsignmentsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test one shipment with createConcepts()
     */
    public function testSendOneConsignment()
    {
        if (getenv('API_KEY') == null || getenv('API_KEY2') == null) {
            echo "\033[31m Set 2 MyParcel API-keys in 'Environment variables' before running UnitTest. Example: API_KEY=f8912fb260639db3b1ceaef2730a4b0643ff0c31 and API_KEY2=f8912fb260sert4564bdsafds45y6afasd7fdas\n\033[0m";
            return $this;
        }

        /** move to __constructor */
        $myParcelCollection = new MyParcelCollection();

        foreach ($this->additionProvider() as $referenceId => $consignmentTest) {

            $consignment = (new MyParcelConsignmentRepository())
                ->setReferenceId($referenceId)
                ->setApiKey($consignmentTest['api_key'])
                ->setCountry($consignmentTest['cc'])
                ->setPerson($consignmentTest['person'])
                ->setCompany($consignmentTest['company'])
                ->setFullStreet($consignmentTest['full_street_test'])
                ->setPostalCode($consignmentTest['postal_code'])
                ->setPackageType(1)
                ->setCity($consignmentTest['city'])
                ->setEmail('reindert@myparcel.nl')
            ;
            $myParcelCollection->addConsignment($consignment);
        }

        /**
         * Get label
         */
        $myParcelCollection
            ->setLinkOfLabels();

        $this->assertEquals(
            true,
            preg_match("#^" . MyParcelRequest::REQUEST_URL . "/pdfs#", $myParcelCollection->getLinkOfLabels()),
            'Can\'t get link of PDF'
        );

        foreach ($this->additionProvider() as $referenceId => $consignmentTest) {
            $consignment = $myParcelCollection->getConsignmentByReferenceId($referenceId);
            $this->assertEquals(true, preg_match("#^3SMYPA#", $consignment->getBarcode()), 'Barcode is not set');
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
            101 => [
                'api_key' => getenv('API_KEY'),
                'cc' => 'NL',
                'person' => 'Reindert',
                'company' => 'Big Sale BV',
                'full_street_test' => 'Plein 1940-45 3b',
                'full_street' => 'Plein 1940-45 3 b',
                'street' => 'Plein 1940-45',
                'number' => 3,
                'number_suffix' => 'b',
                'postal_code' => '2231 JE',
                'city' => 'Rijnsburg',
            ],
            104 => [
                'api_key' => getenv('API_KEY2'),
                'cc' => 'NL',
                'person' => 'Piet',
                'company' => 'Mega Store',
                'full_street_test' => 'Koestraat 55',
                'full_street' => 'Koestraat 55',
                'street' => 'Koestraat',
                'number' => 55,
                'number_suffix' => '',
                'postal_code' => '2231JE',
                'city' => 'Katwijk',
                'phone' => '123-45-235-435',
                'package_type' => 1,
                'large_format' => false,
                'only_recipient' => false,
                'signature' => false,
                'return' => false,
                'label_description' => 'Label description',
            ],
            105 => [
                'api_key' => getenv('API_KEY2'),
                'cc' => 'NL',
                'person' => 'The insurance man',
                'company' => 'Mega Store',
                'full_street_test' => 'Koestraat 55',
                'full_street' => 'Koestraat 55',
                'street' => 'Koestraat',
                'number' => 55,
                'number_suffix' => '',
                'postal_code' => '2231JE',
                'city' => 'Katwijk',
                'phone' => '123-45-235-435',
                'package_type' => 1,
                'large_format' => true,
                'only_recipient' => true,
                'signature' => true,
                'return' => true,
                'label_description' => 1234,
                'insurance' => 250,
            ]
        ];
    }
}