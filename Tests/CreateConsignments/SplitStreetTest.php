<?php

/**
 * Test for split addresses from full street
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
namespace MyParcel\sdk\tests\CreateConsignments\SplitStreetTest;
use MyParcel\sdk\Model\Repository\MyParcelConsignmentRepository;


/**
 * Class SplitStreetTest
 * @package MyParcel\sdk\tests\SplitStreetTest
 */
class SplitStreetTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test setFullStreet()
     */
    public function testSplitStreet()
    {
        foreach ($this->additionProvider() as $consignmentTest) {

            $consignment = new MyParcelConsignmentRepository();
            $consignment->setCc('NL');
            $consignment->setFullStreet($consignmentTest['full_street_test']);

            $this->assertEquals($consignmentTest['number_suffix'], $consignment->getNumberSuffix(), 'Number suffix from: ' . $consignmentTest['full_street_test']);
            $this->assertEquals($consignmentTest['number'], $consignment->getNumber(), 'Number from: ' . $consignmentTest['full_street_test']);
            $this->assertEquals($consignmentTest['street'], $consignment->getStreet(), 'Street: ' . $consignmentTest['full_street_test']);
            $this->assertEquals($consignmentTest['full_street'], $consignment->getFullStreet(), 'Full street: ' . $consignmentTest['full_street_test']);
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
                'full_street_test' => 'Plein 1940-45 3b',
                'full_street' => 'Plein 1940-45 3 b',
                'street' => 'Plein 1940-45',
                'number' => 3,
                'number_suffix' => 'b',
            ],
            [
                'full_street_test' => '300 laan 3',
                'full_street' => '300 laan 3',
                'street' => '300 laan',
                'number' => 3,
                'number_suffix' => '',
            ],
            [
                'full_street_test' => 'A.B.C. straat 12',
                'full_street' => 'A.B.C. straat 12',
                'street' => 'A.B.C. straat',
                'number' => 12,
                'number_suffix' => '',
            ],
            [
                'full_street_test' => 'straat straat 269-133',
                'full_street' => 'straat straat 269 133',
                'street' => 'straat straat',
                'number' => 269,
                'number_suffix' => '133',
            ],
            [
                'full_street_test' => 'Abeelstraat H10',
                'full_street' => 'Abeelstraat H 10',
                'street' => 'Abeelstraat H',
                'number' => 10,
                'number_suffix' => '',
            ],
            [
                'full_street_test' => 'straat straat 269-1001',
                'full_street' => 'straat straat 269 1001',
                'street' => 'straat straat',
                'number' => 269,
                'number_suffix' => '1001',
            ],
            [
                'full_street_test' => 'Meijhorst 50e 26',
                'full_street' => 'Meijhorst 50e 26',
                'street' => 'Meijhorst 50e',
                'number' => 26,
                'number_suffix' => '',
            ],
            [
                'full_street_test' => 'straat straat 12 ZW',
                'full_street' => 'straat straat 12 ZW',
                'street' => 'straat straat',
                'number' => 12,
                'number_suffix' => 'ZW',
            ],
            [
                'full_street_test' => 'straat 12',
                'full_street' => 'straat 12',
                'street' => 'straat',
                'number' => 12,
                'number_suffix' => '',
            ],
            [
                'full_street_test' => 'Biltstraat 113 A BS',
                'full_street' => 'Biltstraat 113 A BS',
                'street' => 'Biltstraat',
                'number' => 113,
                'number_suffix' => 'A BS',
            ],
            [
                'full_street_test' => 'Zonegge 23 12',
                'full_street' => 'Zonegge 23 12',
                'street' => 'Zonegge 23',
                'number' => 12,
                'number_suffix' => '',
            ],
            [
                'full_street_test' => 'Markerkant 10 142',
                'full_street' => 'Markerkant 10 142',
                'street' => 'Markerkant 10',
                'number' => 142,
                'number_suffix' => '',
            ],
            [
                'full_street_test' => 'Markerkant 10 11e',
                'full_street' => 'Markerkant 10 11 e',
                'street' => 'Markerkant 10',
                'number' => 11,
                'number_suffix' => 'e',
            ],
            [
                'full_street_test' => 'Sir Winston Churchillln 283 F008',
                'full_street' => 'Sir Winston Churchillln 283 F008',
                'street' => 'Sir Winston Churchillln',
                'number' => 283,
                'number_suffix' => 'F008',
            ],
            [
                'full_street_test' => 'Sir Winston Churchilllaan 283 59',
                'full_street' => 'Sir Winston Churchilllaan 283 59',
                'street' => 'Sir Winston Churchilllaan 283',
                'number' => 59,
                'number_suffix' => '',
            ],
            [
                'full_street_test' => 'Insulindestraat 69 B03',
                'full_street' => 'Insulindestraat 69 B03',
                'street' => 'Insulindestraat',
                'number' => 69,
                'number_suffix' => 'B03',
            ],
            [
                'full_street_test' => 'Scheepvaartlaan 34/302',
                'full_street' => 'Scheepvaartlaan 34 /302',
                'street' => 'Scheepvaartlaan',
                'number' => 34,
                'number_suffix' => '/302',
            ],
        ];
    }
}