<?php

    /**
     * Test split address from street and number for international address
     *
     * LICENSE: This source file is subject to the Creative Commons License.
     * It is available through the world-wide-web at this URL:
     * http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US
     *
     * If you want to add improvements, please create a fork in our GitHub:
     * https://github.com/myparcelnl
     *
     * @author      Reindert Vetter <reindert@myparcel.nl>
     * @copyright   2010-2016 MyParcel
     * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US  CC BY-NC-ND 3.0 NL
     * @link        https://github.com/myparcelnl/sdk
     * @since       File available since Release 0.1.0
     * @todo        Allow street number for international shipment
     */
    namespace MyParcelNL\Sdk\tests\CreateConsignments\InternationalStreetTest;


    /**
     * Class InternationalStreetTest
     * @package MyParcelNL\Sdk\tests\InternationalStreetTest
     */
    class InternationalStreetTest extends \PHPUnit_Framework_TestCase
    {

        /**
         * Test setFullStreet()
         */
        public function testSplitStreet()
        {
            /*foreach ($this->additionProvider() as $consignmentTest) {

                $consignment = new MyParcelConsignmentRepository();
                $consignment->setCc($consignmentTest['cc']);
                $consignment->setStreet($consignmentTest['street']);
                $consignment->setNumber($consignmentTest['number']);

                $this->assertEquals($consignmentTest['number'], $consignment->getNumber(), 'Number from: ' . $consignmentTest['full_street_test']);
                $this->assertEquals($consignmentTest['street'], $consignment->getStreet(), 'Street: ' . $consignmentTest['full_street_test']);
                $this->assertEquals($consignmentTest['full_street'], $consignment->getFullStreet(), 'Full street: ' . $consignmentTest['full_street_test']);
            }*/
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
                    'cc'  => 'FR',
                    'street'       => 'Street',
                    'number' => 12
                ],
            ];
        }
    }