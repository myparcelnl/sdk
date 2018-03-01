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
class SplitLongStreetTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \MyParcelNL\Sdk\src\Model\Repository\MyParcelConsignmentRepository::setFullStreet
     * @dataProvider additionProvider()
     */
    public function testSplitStreet($country, $fullStreetTest, $street, $streetAdditionalInfo)
    {
        $consignment = (new MyParcelConsignmentRepository())
            ->setCountry($country)
            ->setFullStreet($fullStreetTest);

        $this->assertEquals(
            $street,
            $consignment->getFullStreet(true),
            'Street: ' . $street
        );

        $this->assertEquals(
            $streetAdditionalInfo,
            $consignment->getStreetAdditionalInfo(),
            'Street additional info: ' . $streetAdditionalInfo
        );
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
                'BE',
                'full_street_test' => 'Ir. Mr. Dr. van Waterschoot van der Grachtstraat in Heerlen 14 t',
                'street' => 'Ir. Mr. Dr. van Waterschoot van der',
                'street_additional_info' => 'Grachtstraat in Heerlen 14 t',
            ],
            [
                'NZ',
                'full_street_test' => 'Taumatawhakatangihangakoauauotamateaturipukakapikimaungahoronukupokaiwhenuakitanatahu',
                'street' => 'Taumatawhakatangihangakoauauotamateaturipukakapikimaungahoronukupokaiwhenuakitanatahu',
                'street_additional_info' => '',
            ],
            [
                'BE',
                'full_street_test' => 'testtienpp testtienpp',
                'street' => 'testtienpp testtienpp',
                'street_additional_info' => '',
            ],
            [
                'BE',
                'full_street_test' => 'Wethouder Fierman Eduard Meerburg senior kade 14 t',
                'street' => 'Wethouder Fierman Eduard Meerburg senior',
                'street_additional_info' => 'kade 14 t',
            ],
            [
                'NL',
                'full_street_test' => 'Ir. Mr. Dr. van Waterschoot van der Grachtstraat 14 t',
                'street' => 'Ir. Mr. Dr. van Waterschoot van der 14 t',
                'street_additional_info' => 'Grachtstraat',
            ],
            [
                'NL',
                'full_street_test' => 'Koestraat 554 t',
                'street' => 'Koestraat 554 t',
                'street_additional_info' => '',
            ],
        ];
    }
}