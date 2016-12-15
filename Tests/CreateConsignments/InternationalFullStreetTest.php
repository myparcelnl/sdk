<?php

    /**
     * Test no split address from full street for international address
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
     */
    namespace myparcelnl\sdk\tests\CreateConsignments\InternationalFullStreetTest;
    use myparcelnl\sdk\Model\Repository\MyParcelConsignmentRepository;


    /**
     * Class InternationalFullStreetTest
     * @package myparcelnl\sdk\tests\InternationalFullStreetTest
     */
    class InternationalFullStreetTest extends \PHPUnit_Framework_TestCase
    {

        /**
         * Test setFullStreet()
         */
        public function testSplitStreet()
        {
            foreach ($this->additionProvider() as $consignmentTest) {

                $consignment = new MyParcelConsignmentRepository();
                $consignment
                    ->setCountry($consignmentTest['cc'])
                    ->setFullStreet($consignmentTest['full_street'])
                ;

                $this->assertEquals($consignmentTest['full_street'],    $consignment->getFullStreet(),  'Full street: ' . $consignmentTest['full_street']);
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
                    'cc'  => 'FR',
                    'full_street'       => 'No. 7 street',
                ],
            ];
        }
    }