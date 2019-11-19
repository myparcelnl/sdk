<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\tests\CreateConsignments\SplitStreetTest;

use MyParcelNL\Sdk\src\Factory\ConsignmentFactory;
use MyParcelNL\Sdk\src\Model\Consignment\BpostConsignment;
use MyParcelNL\Sdk\src\Model\Consignment\PostNLConsignment;


/**
 * Class SplitStreetTest
 */
class SplitStreetTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @covers       \MyParcelNL\Sdk\src\Model\AbstractConsignment::setFullStreet
     * @dataProvider additionProvider()
     *
     * @param $carrierId
     * @param $fullStreetTest
     *
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    public function testSplitStreet($carrierId, $fullStreetTest)
    {
        foreach ($this->additionProvider() as $partOfStreet) {
            $consignment = (ConsignmentFactory::createByCarrierId($carrierId))
                ->setCountry($partOfStreet['country'])
                ->setFullStreet($partOfStreet['full_street_input']);


//            var_dump($consignment->getFullStreet());
//            var_dump($consignment->getStreet());
            var_dump($consignment->getNumber());
//            var_dump($consignment->getNumberSuffix());
//            var_dump($consignment->getBoxNumber());

//            $this->assertEquals($partOfStreet['full_street'], $consignment->getFullStreet(), 'Full street: ' . $fullStreetTest);
            $this->assertEquals($partOfStreet['street'], $consignment->getStreet(), 'Street: ' . $fullStreetTest);
            $this->assertEquals($partOfStreet['number'], $consignment->getNumber(), 'Number from: ' . $fullStreetTest);

            if (key_exists('number_suffix', $partOfStreet)) {
                $this->assertEquals($partOfStreet['number_suffix'], $consignment->getNumberSuffix(), 'Number suffix from: ' . $fullStreetTest);
            }

            if (key_exists('box_number', $partOfStreet)) {
                $this->assertEquals($partOfStreet['box_number'], $consignment->getBoxNumber(), 'Box number from: ' . $fullStreetTest);
            }
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
//            [
//                'carrier_id'        => PostNLConsignment::CARRIER_ID,
//                'country'           => 'NL',
//                'full_street_input' => 'Plein 1945 27',
//                'full_street'       => 'Plein 1945 27',
//                'street'            => 'Plein 1945',
//                'number'            => 27,
//                'number_suffix'     => '',
//            ],
//            [
//                'carrier_id'        => PostNLConsignment::CARRIER_ID,
//                'country'           => 'NL',
//                'full_street_input' => 'Plein 1940-45 3 b',
//                'full_street'       => 'Plein 1940-45 3 b',
//                'street'            => 'Plein 1940-45',
//                'number'            => 3,
//                'number_suffix'     => 'b',
//            ],
//            [
//                'carrier_id'        => PostNLConsignment::CARRIER_ID,
//                'country'           => 'NL',
//                'full_street_input' => '300 laan 3',
//                'full_street'       => '300 laan 3',
//                'street'            => '300 laan',
//                'number'            => 3,
//                'number_suffix'     => '',
//            ],
//            [
//                'carrier_id'        => PostNLConsignment::CARRIER_ID,
//                'country'           => 'NL',
//                'full_street_input' => 'A.B.C. street 12',
//                'full_street'       => 'A.B.C. street 12',
//                'street'            => 'A.B.C. street',
//                'number'            => 12,
//                'number_suffix'     => '',
//            ],
//            [
//                'carrier_id'        => PostNLConsignment::CARRIER_ID,
//                'country'           => 'NL',
//                'full_street_input' => 'street street 269 133',
//                'full_street'       => 'street street 269 133',
//                'street'            => 'street street',
//                'number'            => 269,
//                'number_suffix'     => '133',
//            ],
//            [
//                'carrier_id' => PostNLConsignment::CARRIER_ID,
//                'country'    => 'NL',
//
//                'full_street_input' => 'Abeelstreet H 10',
//                'full_street'       => 'Abeelstreet H 10',
//                'street'            => 'Abeelstreet H',
//                'number'            => 10,
//                'number_suffix'     => '',
//            ],
//            [
//                'carrier_id'        => PostNLConsignment::CARRIER_ID,
//                'country'           => 'NL',
//                'full_street_input' => 'street street 269 1001',
//                'full_street'       => 'street street 269 1001',
//                'street'            => 'street street',
//                'number'            => 269,
//                'number_suffix'     => '1001',
//            ],
//            [
//                'carrier_id'        => PostNLConsignment::CARRIER_ID,
//                'country'           => 'NL',
//                'full_street_input' => 'Meijhorst 50e 26',
//                'full_street'       => 'Meijhorst 50e 26',
//                'street'            => 'Meijhorst 50e',
//                'number'            => 26,
//                'number_suffix'     => '',
//            ],
//            [
//                'carrier_id'        => PostNLConsignment::CARRIER_ID,
//                'country'           => 'NL',
//                'full_street_input' => 'street street 12 ZW',
//                'full_street'       => 'street street 12 ZW',
//                'street'            => 'street street',
//                'number'            => 12,
//                'number_suffix'     => 'ZW',
//            ],
//            [
//                'carrier_id' => PostNLConsignment::CARRIER_ID,
//                'country'    => 'NL',
//
//                'full_street_input' => 'street 12',
//                'full_street'       => 'street 12',
//                'street'            => 'street',
//                'number'            => 12,
//                'number_suffix'     => '',
//            ],
//            [
//                'carrier_id'        => PostNLConsignment::CARRIER_ID,
//                'country'           => 'NL',
//                'full_street_input' => 'Biltstreet 113 A BS',
//                'full_street'       => 'Biltstreet 113 A BS',
//                'street'            => 'Biltstreet',
//                'number'            => 113,
//                'number_suffix'     => 'A BS',
//            ],
//            [
//                'carrier_id'        => PostNLConsignment::CARRIER_ID,
//                'country'           => 'NL',
//                'full_street_input' => 'Zonegge 23 12',
//                'full_street'       => 'Zonegge 23 12',
//                'street'            => 'Zonegge 23',
//                'number'            => 12,
//                'number_suffix'     => '',
//            ],
//            [
//                'carrier_id'        => PostNLConsignment::CARRIER_ID,
//                'country'           => 'NL',
//                'full_street_input' => 'Markerkant 10 142',
//                'full_street'       => 'Markerkant 10 142',
//                'street'            => 'Markerkant',
//                'number'            => 10,
//                'number_suffix'     => '142',
//            ],
//            [
//                'carrier_id'        => PostNLConsignment::CARRIER_ID,
//                'country'           => 'NL',
//                'full_street_input' => 'Markerkant 10 11e',
//                'full_street'       => 'Markerkant 10 11e',
//                'street'            => 'Markerkant',
//                'number'            => 10,
//                'number_suffix'     => '11e',
//            ],
//            [
//                'carrier_id'        => PostNLConsignment::CARRIER_ID,
//                'country'           => 'NL',
//                'full_street_input' => 'Sir Winston Churchillln 283 F008',
//                'full_street'       => 'Sir Winston Churchillln 283 F008',
//                'street'            => 'Sir Winston Churchillln',
//                'number'            => 283,
//                'number_suffix'     => 'F008',
//            ],
//            [
//                'carrier_id'        => PostNLConsignment::CARRIER_ID,
//                'country'           => 'NL',
//                'full_street_input' => 'Woning Sir Winston Churchillln 283 -9',
//                'full_street'       => 'Woning Sir Winston Churchillln 283 -9',
//                'street'            => 'Woning Sir Winston Churchillln',
//                'number'            => 283,
//                'number_suffix'     => '-9',
//            ],
//            [
//                'carrier_id'        => PostNLConsignment::CARRIER_ID,
//                'country'           => 'NL',
//                'full_street_input' => 'Insulindestreet 69 B03',
//                'full_street'       => 'Insulindestreet 69 B03',
//                'street'            => 'Insulindestreet',
//                'number'            => 69,
//                'number_suffix'     => 'B03',
//            ],
//            [
//                'carrier_id'        => PostNLConsignment::CARRIER_ID,
//                'country'           => 'NL',
//                'full_street_input' => 'Scheepvaartlaan 34 302',
//                'full_street'       => 'Scheepvaartlaan 34 302',
//                'street'            => 'Scheepvaartlaan',
//                'number'            => 34,
//                'number_suffix'     => '302',
//            ],
//            [
//                'carrier_id'        => PostNLConsignment::CARRIER_ID,
//                'country'           => 'NL',
//                'full_street_input' => 'oan e dijk 48',
//                'full_street'       => 'oan e dijk 48',
//                'street'            => 'oan e dijk',
//                'number'            => 48,
//                'number_suffix'     => '',
//            ],
//            [
//                'carrier_id'        => PostNLConsignment::CARRIER_ID,
//                'country'           => 'NL',
//                'full_street_input' => 'Vlinderveen 137',
//                'full_street'       => 'Vlinderveen 137',
//                'street'            => 'Vlinderveen',
//                'number'            => 137,
//                'number_suffix'     => '',
//            ],
//            [
//                'carrier_id'        => PostNLConsignment::CARRIER_ID,
//                'country'           => 'NL',
//                'full_street_input' => 'street 39- 1 hg',
//                'full_street'       => 'street 39- 1 hg',
//                'street'            => 'street 39-',
//                'number'            => 1,
//                'number_suffix'     => 'hg',
//            ],
//            [
//                'carrier_id'        => PostNLConsignment::CARRIER_ID,
//                'country'           => 'NL',
//                'full_street_input' => 'Nicolaas Ruyschstraat 8 02L',
//                'full_street'       => 'Nicolaas Ruyschstraat 8 02L',
//                'street'            => 'Nicolaas Ruyschstraat',
//                'number'            => 8,
//                'number_suffix'     => '02L',
//            ],
//            [
//                'carrier_id'        => PostNLConsignment::CARRIER_ID,
//                'country'           => 'NL',
//                'full_street_input' => 'Landsdijk 49 A',
//                'full_street'       => 'Landsdijk 49 A',
//                'street'            => 'Landsdijk',
//                'number'            => 49,
//                'number_suffix'     => 'A',
//            ],
//            [
//                'carrier_id'        => BpostConsignment::CARRIER_ID,
//                'country'           => 'BE',
//                'full_street_input' => 'Zennestraat 32 bte 20',
//                'full_street'       => 'Zennestraat 32 bus 20',
//                'street'            => 'Zennestraat',
//                'number'            => '32',
//                'box_number'        => '20',
//            ],
//            [
//                'carrier_id'        => BpostConsignment::CARRIER_ID,
//                'country'           => 'BE',
//                'full_street_input' => 'Zennestraat 32 bus 20',
//                'full_street'       => 'Zennestraat 32 bus 20',
//                'street'            => 'Zennestraat',
//                'number'            => '32',
//                'box_number'        => '20',
//            ],
//            [
//                'carrier_id'        => BpostConsignment::CARRIER_ID,
//                'country'           => 'BE',
//                'full_street_input' => 'Zennestraat 32 box 32',
//                'full_street'       => 'Zennestraat 32 bus 32',
//                'street'            => 'Zennestraat',
//                'number'            => '32',
//                'box_number'        => '32',
//            ],
//            [
//                'carrier_id'        => BpostConsignment::CARRIER_ID,
//                'country'           => 'BE',
//                'full_street_input' => 'Zennestraat 32 boîte 20',
//                'full_street'       => 'Zennestraat 32 bus 20',
//                'street'            => 'Zennestraat',
//                'number'            => '32',
//                'box_number'        => '20',
//            ],
//            [
//                'carrier_id'        => BpostConsignment::CARRIER_ID,
//                'country'           => 'BE',
//                'full_street_input' => 'Dendermondestraat 55 bus 12',
//                'full_street'       => 'Dendermondestraat 55 bus 12',
//                'street'            => 'Dendermondestraat',
//                'number'            => '55',
//                'box_number'        => '12',
//            ],
//            [
//                'carrier_id'        => BpostConsignment::CARRIER_ID,
//                'country'           => 'BE',
//                'full_street_input' => 'Steengroefstraat 21 bus 27',
//                'full_street'       => 'Steengroefstraat 21 bus 27',
//                'street'            => 'Steengroefstraat',
//                'number'            => '21',
//                'box_number'        => '27',
//            ],
//            [
//                'carrier_id'        => BpostConsignment::CARRIER_ID,
//                'country'           => 'BE',
//                'full_street_input' => 'Philippe de Champagnestraat 23',
//                'full_street'       => 'Philippe de Champagnestraat 23',
//                'street'            => 'Philippe de Champagnestraat',
//                'number'            => 23,
//                'box_number'        => '',
//            ],
//            [
//                'carrier_id'        => BpostConsignment::CARRIER_ID,
//                'country'           => 'BE',
//                'full_street_input' => 'Kortenberglaan 4 bus 10',
//                'full_street'       => 'Kortenberglaan 4 bus 10',
//                'street'            => 'Kortenberglaan',
//                'number'            => '4',
//                'box_number'        => '10',
//            ],
//            [
//                'carrier_id'        => BpostConsignment::CARRIER_ID,
//                'country'           => 'BE',
//                'full_street_input' => 'Ildefonse Vandammestraat 5 D',
//                'full_street'       => 'Ildefonse Vandammestraat 5 bus D',
//                'street'            => 'Ildefonse Vandammestraat',
//                'number'            => '5',
//                'box_number'        => 'D',
//            ],
//            [
//                'carrier_id'        => BpostConsignment::CARRIER_ID,
//                'country'           => 'BE',
//                'full_street_input' => 'I. Vandammestraat 5 D',
//                'full_street'       => 'I. Vandammestraat 5 bus D',
//                'street'            => 'I. Vandammestraat',
//                'number'            => '5',
//                'box_number'        => 'D',
//            ],
            [
                'carrier_id'        => BpostConsignment::CARRIER_ID,
                'country'           => 'BE',
                'full_street_input' => 'Slameuterstraat 9B',
                'full_street'       => 'Slameuterstraat 9B',
                'street'            => 'Slameuterstraat',
                'number'            => '9B',
                'box_number'        => '',
            ],

        ];
    }
}
