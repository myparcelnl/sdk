<?php declare(strict_types=1);

/**
 * For Dutch consignments the street should be divided into name, number and addition. This code tests whether the
 * street is split properly.
 *
 * If you want to add improvements, please create a fork in our GitHub:
 * https://github.com/myparcelnl
 *
 * @author      Reindert Vetter <reindert@myparcel.nl>
 * @copyright   2010-2020 MyParcel
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US  CC BY-NC-ND 3.0 NL
 * @link        https://github.com/myparcelnl/sdk
 * @since       File available since Release v0.1.0
 */

namespace MyParcelNL\Sdk\src\tests\CreateConsignments\SplitStreetTest;

use MyParcelNL\Sdk\src\Factory\ConsignmentFactory;
use MyParcelNL\Sdk\src\Model\Consignment\PostNLConsignment;

/**
 * Class SplitStreetTest
 */
class SplitLongStreetTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @dataProvider additionProvider()
     *
     * @param $carrierId
     * @param $country
     * @param $fullStreetTest
     * @param $street
     * @param $streetAdditionalInfo
     *
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    public function testSplitStreet($carrierId, $country, $fullStreetTest, $street, $streetAdditionalInfo)
    {
        $consignment = (ConsignmentFactory::createByCarrierId($carrierId))
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
                'carrier_id'             => PostNLConsignment::CARRIER_ID,
                'NZ',
                'full_street_input'      => 'Ir. Mr. Dr. van Waterschoot van der Grachtstraat in Heerlen 14 t',
                'street'                 => 'Ir. Mr. Dr. van Waterschoot van der',
                'street_additional_info' => 'Grachtstraat in Heerlen 14 t',
            ],
            [
                'carrier_id'             => PostNLConsignment::CARRIER_ID,
                'NZ',
                'full_street_input'      => 'Taumatawhakatangihangakoauauotamateaturipukakapikimaungahoronukupokaiwhenuakitanatahu',
                'street'                 => 'Taumatawhakatangihangakoauauotamateaturipukakapikimaungahoronukupokaiwhenuakitanatahu',
                'street_additional_info' => '',
            ],
            [
                'carrier_id'             => PostNLConsignment::CARRIER_ID,
                'NZ',
                'full_street_input'      => 'testtienpp testtienpp',
                'street'                 => 'testtienpp testtienpp',
                'street_additional_info' => '',
            ],
            [
                'carrier_id'             => PostNLConsignment::CARRIER_ID,
                'NZ',
                'full_street_input'      => 'Wethouder Fierman Eduard Meerburg senior kade 14 t',
                'street'                 => 'Wethouder Fierman Eduard Meerburg senior',
                'street_additional_info' => 'kade 14 t',
            ],
            [
                'carrier_id'             => PostNLConsignment::CARRIER_ID,
                'NL',
                'full_street_input'      => 'Ir. Mr. Dr. van Waterschoot van der Grachtstraat 14 t',
                'street'                 => 'Ir. Mr. Dr. van Waterschoot van der 14 t',
                'street_additional_info' => 'Grachtstraat',
            ],
            [
                'carrier_id'             => PostNLConsignment::CARRIER_ID,
                'NL',
                'full_street_input'      => 'Koestraat 554 t',
                'street'                 => 'Koestraat 554 t',
                'street_additional_info' => '',
            ],
        ];
    }
}
