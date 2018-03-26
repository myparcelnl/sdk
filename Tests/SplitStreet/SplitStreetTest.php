<?php

/**
 * For Dutch consignments the street should be divided into name, number and addition. This code tests whether the
 * street is split properly.
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

namespace MyParcelNL\Sdk\tests\CreateConsignments\SplitStreetTest;
use MyParcelNL\Sdk\src\Model\Repository\MyParcelConsignmentRepository;


/**
 * Class SplitStreetTest
 * @package MyParcelNL\Sdk\tests\SplitStreetTest
 */
class SplitStreetTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \MyParcelNL\Sdk\src\Model\Repository\MyParcelConsignmentRepository::setFullStreet
     * @dataProvider additionProvider()
     */
    public function testSplitStreet($fullStreetTest, $fullStreet, $street, $number, $numberSuffix)
    {
        $consignment = (new MyParcelConsignmentRepository())
            ->setCountry('NL')
            ->setFullStreet($fullStreetTest);

        $this->assertEquals($fullStreet, $consignment->getFullStreet(), 'Full street: ' . $fullStreetTest);
        $this->assertEquals($street, $consignment->getStreet(), 'Street: ' . $fullStreetTest);
        $this->assertEquals($number, $consignment->getNumber(), 'Number from: ' . $fullStreetTest);
        $this->assertEquals($numberSuffix, $consignment->getNumberSuffix(), 'Number suffix from: ' . $fullStreetTest);
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
                'full_street_test' => 'A.B.C. street 12',
                'full_street' => 'A.B.C. street 12',
                'street' => 'A.B.C. street',
                'number' => 12,
                'number_suffix' => '',
            ],
            [
                'full_street_test' => 'street street 269-133',
                'full_street' => 'street street 269 133',
                'street' => 'street street',
                'number' => 269,
                'number_suffix' => '133',
            ],
            [
                'full_street_test' => 'Abeelstreet H10',
                'full_street' => 'Abeelstreet H 10',
                'street' => 'Abeelstreet H',
                'number' => 10,
                'number_suffix' => '',
            ],
            [
                'full_street_test' => 'street street 269-1001',
                'full_street' => 'street street 269 1001',
                'street' => 'street street',
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
                'full_street_test' => 'street street 12 ZW',
                'full_street' => 'street street 12 ZW',
                'street' => 'street street',
                'number' => 12,
                'number_suffix' => 'ZW',
            ],
            [
                'full_street_test' => 'street 12',
                'full_street' => 'street 12',
                'street' => 'street',
                'number' => 12,
                'number_suffix' => '',
            ],
            [
                'full_street_test' => 'Biltstreet 113 A BS',
                'full_street' => 'Biltstreet 113 A BS',
                'street' => 'Biltstreet',
                'number' => 113,
                'number_suffix' => 'A BS',
            ],
            [
                'full_street_test' => 'Zonegge 23 12',
                'full_street' => 'Zonegge 23 12',
                'street' => 'Zonegge',
                'number' => 23,
                'number_suffix' => '12',
            ],
            [
                'full_street_test' => 'Markerkant 10 142',
                'full_street' => 'Markerkant 10 142',
                'street' => 'Markerkant',
                'number' => 10,
                'number_suffix' => '142',
            ],
            [
                'full_street_test' => 'Markerkant 10 11e',
                'full_street' => 'Markerkant 10 11e',
                'street' => 'Markerkant',
                'number' => 10,
                'number_suffix' => '11e',
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
                'street' => 'Sir Winston Churchilllaan',
                'number' => 283,
                'number_suffix' => '59',
            ],
            [
                'full_street_test' => 'Insulindestreet 69 B03',
                'full_street' => 'Insulindestreet 69 B03',
                'street' => 'Insulindestreet',
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
            [
                'full_street_test' => 'oan e dijk 48',
                'full_street' => 'oan e dijk 48',
                'street' => 'oan e dijk',
                'number' => 48,
                'number_suffix' => '',
            ],
            [
                'full_street_test' => 'Vlinderveen137',
                'full_street' => 'Vlinderveen 137',
                'street' => 'Vlinderveen',
                'number' => 137,
                'number_suffix' => '',
            ],
            [
                'full_street_test' => 'street 39-1hg',
                'full_street' => 'street 39- 1 hg',
                'street' => 'street 39-',
                'number' => 1,
                'number_suffix' => 'hg',
            ],
            [
                'full_street_test' => 'Nicolaas Ruyschstraat 8 02L',
                'full_street' => 'Nicolaas Ruyschstraat 8 02L',
                'street' => 'Nicolaas Ruyschstraat',
                'number' => 8,
                'number_suffix' => '02L',
            ],
            [
                'full_street_test' => 'Landsdijk 49 -A',
                'full_street' => 'Landsdijk 49 A',
                'street' => 'Landsdijk',
                'number' => 49,
                'number_suffix' => 'A',
            ],
        ];
    }
}