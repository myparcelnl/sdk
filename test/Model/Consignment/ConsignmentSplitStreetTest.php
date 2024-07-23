<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Consignment;

use MyParcelNL\Sdk\src\Model\Carrier\CarrierBpost;
use MyParcelNL\Sdk\src\Model\Carrier\CarrierPostNL;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\Test\Bootstrap\ConsignmentTestCase;

class ConsignmentSplitStreetTest extends ConsignmentTestCase
{
    protected const BOX_NUMBER             = 'box_number';
    protected const NUMBER_SUFFIX          = 'number_suffix';
    protected const STREET_ADDITIONAL_INFO = 'street_additional_info';

    /**
     * @return array[]
     * @throws \Exception
     */
    public function provideSplitStreetData(): array
    {
        return $this->createConsignmentProviderDataset([
            [
                self::FULL_STREET                 => 'Graan voor Visch 19905',
                self::expected(self::FULL_STREET) => 'Graan voor Visch 19905',
                self::expected(self::STREET)      => 'Graan voor Visch',
                self::expected(self::NUMBER)      => 19905,
            ],
            [
                self::FULL_STREET                   => 'Charles Petitweg 7 A-2',
                self::expected(self::FULL_STREET)   => 'Charles Petitweg 7 A-2',
                self::expected(self::STREET)        => 'Charles Petitweg',
                self::expected(self::NUMBER)        => 7,
                self::expected(self::NUMBER_SUFFIX) => 'A-2',
            ],
            [
                self::FULL_STREET                   => 'overtoom 452-2',
                self::expected(self::FULL_STREET)   => 'overtoom 452 -2',
                self::expected(self::STREET)        => 'overtoom',
                self::expected(self::NUMBER)        => '452',
                self::expected(self::NUMBER_SUFFIX) => '-2',
            ],
            [
                self::FULL_STREET                 => 'avenue roger lallemand 13 B13',
                self::COUNTRY                     => 'BE',
                self::CARRIER_ID                  => CarrierBpost::ID,
                self::expected(self::FULL_STREET) => 'avenue roger lallemand 13 bus 13',
                self::expected(self::STREET)      => 'avenue roger lallemand',
                self::expected(self::NUMBER)      => '13',
                self::expected(self::BOX_NUMBER)  => '13',
            ],
            // todo:
            //  [
            //      self::FULL_STREET                 => 'avenue roger lallemand 13 B13',
            //      self::COUNTRY                     => 'BE',
            //      self::expected(self::FULL_STREET) => 'avenue roger lallemand 13 bus 13',
            //      self::expected(self::STREET)      => 'avenue roger lallemand 13 bus 13',
            //      self::expected(self::NUMBER)      => '13',
            //      self::expected(self::BOX_NUMBER)  => '13',
            //  ],
            [
                self::FULL_STREET                 => 'A 109',
                self::expected(self::FULL_STREET) => 'A 109',
                self::expected(self::STREET)      => 'A',
                self::expected(self::NUMBER)      => 109,
            ],
            [
                self::FULL_STREET                 => 'Plein 1945 27',
                self::expected(self::FULL_STREET) => 'Plein 1945 27',
                self::expected(self::STREET)      => 'Plein 1945',
                self::expected(self::NUMBER)      => 27,
            ],
            [
                self::FULL_STREET                   => 'Plein 1940-45 3 b',
                self::expected(self::FULL_STREET)   => 'Plein 1940-45 3 b',
                self::expected(self::STREET)        => 'Plein 1940-45',
                self::expected(self::NUMBER)        => 3,
                self::expected(self::NUMBER_SUFFIX) => 'b',
            ],
            [
                self::FULL_STREET                 => 'Laan 1940-1945 103',
                self::expected(self::FULL_STREET) => 'Laan 1940-1945 103',
                self::expected(self::STREET)      => 'Laan 1940-1945',
                self::expected(self::NUMBER)      => 103,
            ],
            [
                self::FULL_STREET                   => 'Wijk 1 20',
                self::expected(self::FULL_STREET)   => 'Wijk 1 20',
                self::expected(self::STREET)        => 'Wijk 1',
                self::expected(self::NUMBER)        => 20,
                self::expected(self::NUMBER_SUFFIX) => '',
            ],
            [
                self::FULL_STREET                 => '300 laan 3',
                self::expected(self::FULL_STREET) => '300 laan 3',
                self::expected(self::STREET)      => '300 laan',
                self::expected(self::NUMBER)      => 3,
            ],
            [
                self::FULL_STREET                 => 'A.B.C. street 12',
                self::expected(self::FULL_STREET) => 'A.B.C. street 12',
                self::expected(self::STREET)      => 'A.B.C. street',
                self::expected(self::NUMBER)      => 12,
            ],
            [
                self::FULL_STREET                   => 'street street 269 133',
                self::expected(self::FULL_STREET)   => 'street street 269 133',
                self::expected(self::STREET)        => 'street street 269',
                self::expected(self::NUMBER)        => 133,
                self::expected(self::NUMBER_SUFFIX) => '',
            ],
            [
                self::FULL_STREET                 => 'Abeelstreet H 10',
                self::expected(self::FULL_STREET) => 'Abeelstreet H 10',
                self::expected(self::STREET)      => 'Abeelstreet H',
                self::expected(self::NUMBER)      => 10,
            ],
            [
                self::FULL_STREET                   => 'street street 269 1001',
                self::expected(self::FULL_STREET)   => 'street street 269 1001',
                self::expected(self::STREET)        => 'street street 269',
                self::expected(self::NUMBER)        => 1001,
                self::expected(self::NUMBER_SUFFIX) => '',
            ],
            [
                self::FULL_STREET                 => 'Meijhorst 50e 26',
                self::expected(self::FULL_STREET) => 'Meijhorst 50e 26',
                self::expected(self::STREET)      => 'Meijhorst 50e',
                self::expected(self::NUMBER)      => 26,
            ],
            [
                self::FULL_STREET                   => 'street street 12 ZW',
                self::expected(self::FULL_STREET)   => 'street street 12 ZW',
                self::expected(self::STREET)        => 'street street',
                self::expected(self::NUMBER)        => 12,
                self::expected(self::NUMBER_SUFFIX) => 'ZW',
            ],
            [
                self::FULL_STREET                 => 'street 12',
                self::expected(self::FULL_STREET) => 'street 12',
                self::expected(self::STREET)      => 'street',
                self::expected(self::NUMBER)      => 12,
            ],
            [
                self::FULL_STREET                   => 'Biltstreet 113 A BS',
                self::expected(self::FULL_STREET)   => 'Biltstreet 113 A BS',
                self::expected(self::STREET)        => 'Biltstreet',
                self::expected(self::NUMBER)        => 113,
                self::expected(self::NUMBER_SUFFIX) => 'A BS',
            ],
            [
                self::FULL_STREET                 => 'Zonegge 23 12',
                self::expected(self::FULL_STREET) => 'Zonegge 23 12',
                self::expected(self::STREET)      => 'Zonegge 23',
                self::expected(self::NUMBER)      => 12,
            ],
            [
                self::FULL_STREET                   => 'Markerkant 10 142',
                self::expected(self::FULL_STREET)   => 'Markerkant 10 142',
                self::expected(self::STREET)        => 'Markerkant 10',
                self::expected(self::NUMBER)        => 142,
                self::expected(self::NUMBER_SUFFIX) => '',
            ],
            [
                self::FULL_STREET                   => 'Markerkant 10 11e',
                self::expected(self::FULL_STREET)   => 'Markerkant 10 11e',
                self::expected(self::STREET)        => 'Markerkant',
                self::expected(self::NUMBER)        => 10,
                self::expected(self::NUMBER_SUFFIX) => '11e',
            ],
            [
                self::FULL_STREET                   => 'Sir Winston Churchillln 283 F008',
                self::expected(self::FULL_STREET)   => 'Sir Winston Churchillln 283 F008',
                self::expected(self::STREET)        => 'Sir Winston Churchillln',
                self::expected(self::NUMBER)        => 283,
                self::expected(self::NUMBER_SUFFIX) => 'F008',
            ],
            [
                self::FULL_STREET                   => 'Woning Sir Winston Churchillln 283 -9',
                self::expected(self::FULL_STREET)   => 'Woning Sir Winston Churchillln 283 -9',
                self::expected(self::STREET)        => 'Woning Sir Winston Churchillln',
                self::expected(self::NUMBER)        => 283,
                self::expected(self::NUMBER_SUFFIX) => '-9',
            ],
            [
                self::FULL_STREET                   => 'Insulindestreet 69 B03',
                self::expected(self::FULL_STREET)   => 'Insulindestreet 69 B03',
                self::expected(self::STREET)        => 'Insulindestreet',
                self::expected(self::NUMBER)        => 69,
                self::expected(self::NUMBER_SUFFIX) => 'B03',
            ],
            [
                self::FULL_STREET                   => 'Scheepvaartlaan 34 302',
                self::expected(self::FULL_STREET)   => 'Scheepvaartlaan 34 302',
                self::expected(self::STREET)        => 'Scheepvaartlaan 34',
                self::expected(self::NUMBER)        => 302,
                self::expected(self::NUMBER_SUFFIX) => '',
            ],
            [
                self::FULL_STREET                 => 'oan e dijk 48',
                self::expected(self::FULL_STREET) => 'oan e dijk 48',
                self::expected(self::STREET)      => 'oan e dijk',
                self::expected(self::NUMBER)      => 48,
            ],
            [
                self::FULL_STREET                 => 'Vlinderveen 137',
                self::expected(self::FULL_STREET) => 'Vlinderveen 137',
                self::expected(self::STREET)      => 'Vlinderveen',
                self::expected(self::NUMBER)      => 137,
            ],
            [
                self::FULL_STREET                   => 'street 39- 1 hg',
                self::expected(self::FULL_STREET)   => 'street 39- 1 hg',
                self::expected(self::STREET)        => 'street 39-',
                self::expected(self::NUMBER)        => 1,
                self::expected(self::NUMBER_SUFFIX) => 'hg',
            ],
            [
                self::FULL_STREET                   => 'Nicolaas Ruyschstraat 8 02L',
                self::expected(self::FULL_STREET)   => 'Nicolaas Ruyschstraat 8 02L',
                self::expected(self::STREET)        => 'Nicolaas Ruyschstraat',
                self::expected(self::NUMBER)        => 8,
                self::expected(self::NUMBER_SUFFIX) => '02L',
            ],
            [
                self::FULL_STREET                   => 'Landsdijk 49 A',
                self::expected(self::FULL_STREET)   => 'Landsdijk 49 A',
                self::expected(self::STREET)        => 'Landsdijk',
                self::expected(self::NUMBER)        => 49,
                self::expected(self::NUMBER_SUFFIX) => 'A',
            ],
            [
                self::FULL_STREET                   => 'Markerkant 10 apartment a',
                self::expected(self::FULL_STREET)   => 'Markerkant 10 a',
                self::expected(self::STREET)        => 'Markerkant',
                self::expected(self::NUMBER)        => 10,
                self::expected(self::NUMBER_SUFFIX) => 'a',
            ],
            [
                self::FULL_STREET                   => 'Markerkant 10 noordzijde',
                self::expected(self::FULL_STREET)   => 'Markerkant 10 NZ',
                self::expected(self::STREET)        => 'Markerkant',
                self::expected(self::NUMBER)        => 10,
                self::expected(self::NUMBER_SUFFIX) => 'NZ',
            ],
            [
                self::FULL_STREET                   => 'Markerkant 10 west',
                self::expected(self::FULL_STREET)   => 'Markerkant 10 W',
                self::expected(self::STREET)        => 'Markerkant',
                self::expected(self::NUMBER)        => 10,
                self::expected(self::NUMBER_SUFFIX) => 'W',
            ],
            [
                self::FULL_STREET                 => 'Tuinstraat 35 boven',
                self::expected(self::FULL_STREET)   => 'Tuinstraat 35 boven',
                self::expected(self::STREET)        => 'Tuinstraat',
                self::expected(self::NUMBER)        => '35',
                self::expected(self::NUMBER_SUFFIX) => 'boven',
            ],
            [
                self::FULL_STREET                   => 'Nicolaas Ruyschstraat 8 ad hoc',
                self::expected(self::FULL_STREET)   => 'Nicolaas Ruyschstraat 8 ad hoc',
                self::expected(self::STREET)        => 'Nicolaas Ruyschstraat',
                self::expected(self::NUMBER)        => 8,
                self::expected(self::NUMBER_SUFFIX) => 'ad hoc',
            ],
            [
                self::FULL_STREET                   => 'Hoofdweg 679 A',
                self::CARRIER_ID                    => CarrierBpost::ID,
                self::expected(self::FULL_STREET)   => 'Hoofdweg 679 A',
                self::expected(self::STREET)        => 'Hoofdweg',
                self::expected(self::NUMBER)        => '679',
                self::expected(self::NUMBER_SUFFIX) => 'A',
            ],
            [
                self::FULL_STREET                   => 'Manebruggestraat 316 bus 2 R',
                self::COUNTRY                       => 'BE',
                self::CARRIER_ID                    => CarrierBpost::ID,
                self::expected(self::FULL_STREET)   => 'Manebruggestraat 316 bus 2 R',
                self::expected(self::STREET)        => 'Manebruggestraat',
                self::expected(self::NUMBER)        => '316',
                self::expected(self::NUMBER_SUFFIX) => 'R',
                self::expected(self::BOX_NUMBER)    => '2',
            ],
            [
                self::FULL_STREET                 => 'Zennestraat 32 bte 20',
                self::COUNTRY                     => 'BE',
                self::CARRIER_ID                  => CarrierBpost::ID,
                self::expected(self::FULL_STREET) => 'Zennestraat 32 bus 20',
                self::expected(self::STREET)      => 'Zennestraat',
                self::expected(self::NUMBER)      => '32',
                self::expected(self::BOX_NUMBER)  => '20',
            ],
            [
                self::FULL_STREET                 => 'Zennestraat 32 bus 20',
                self::COUNTRY                     => 'BE',
                self::CARRIER_ID                  => CarrierBpost::ID,
                self::expected(self::FULL_STREET) => 'Zennestraat 32 bus 20',
                self::expected(self::STREET)      => 'Zennestraat',
                self::expected(self::NUMBER)      => '32',
                self::expected(self::BOX_NUMBER)  => '20',
            ],
            [
                self::FULL_STREET                 => 'Zennestraat 32 box 32',
                self::COUNTRY                     => 'BE',
                self::CARRIER_ID                  => CarrierBpost::ID,
                self::expected(self::FULL_STREET) => 'Zennestraat 32 bus 32',
                self::expected(self::STREET)      => 'Zennestraat',
                self::expected(self::NUMBER)      => '32',
                self::expected(self::BOX_NUMBER)  => '32',
            ],
            [
                self::FULL_STREET                 => 'Zennestraat 32 boîte 20',
                self::COUNTRY                     => 'BE',
                self::CARRIER_ID                  => CarrierBpost::ID,
                self::expected(self::FULL_STREET) => 'Zennestraat 32 bus 20',
                self::expected(self::STREET)      => 'Zennestraat',
                self::expected(self::NUMBER)      => '32',
                self::expected(self::BOX_NUMBER)  => '20',
            ],
            [
                self::FULL_STREET                 => 'Dendermondestraat 55 bus 12',
                self::COUNTRY                     => 'BE',
                self::CARRIER_ID                  => CarrierBpost::ID,
                self::expected(self::FULL_STREET) => 'Dendermondestraat 55 bus 12',
                self::expected(self::STREET)      => 'Dendermondestraat',
                self::expected(self::NUMBER)      => '55',
                self::expected(self::BOX_NUMBER)  => '12',
            ],
            [
                self::FULL_STREET                 => 'Steengroefstraat 21 bus 27',
                self::COUNTRY                     => 'BE',
                self::CARRIER_ID                  => CarrierBpost::ID,
                self::expected(self::FULL_STREET) => 'Steengroefstraat 21 bus 27',
                self::expected(self::STREET)      => 'Steengroefstraat',
                self::expected(self::NUMBER)      => '21',
                self::expected(self::BOX_NUMBER)  => '27',
            ],
            [
                self::FULL_STREET                 => 'Philippe de Champagnestraat 23',
                self::COUNTRY                     => 'BE',
                self::CARRIER_ID                  => CarrierBpost::ID,
                self::expected(self::FULL_STREET) => 'Philippe de Champagnestraat 23',
                self::expected(self::STREET)      => 'Philippe de Champagnestraat',
                self::expected(self::NUMBER)      => 23,
                self::expected(self::BOX_NUMBER)  => '',
            ],
            [
                self::FULL_STREET                 => 'Straat 23-C11',
                self::COUNTRY                     => 'BE',
                self::CARRIER_ID                  => CarrierBpost::ID,
                self::expected(self::FULL_STREET) => 'Straat 23 bus C11',
                self::expected(self::STREET)      => 'Straat',
                self::expected(self::NUMBER)      => 23,
                self::expected(self::BOX_NUMBER)  => 'C11',
            ],
            [
                self::FULL_STREET                 => 'Kortenberglaan 4 bus 10',
                self::COUNTRY                     => 'BE',
                self::CARRIER_ID                  => CarrierBpost::ID,
                self::expected(self::FULL_STREET) => 'Kortenberglaan 4 bus 10',
                self::expected(self::STREET)      => 'Kortenberglaan',
                self::expected(self::NUMBER)      => '4',
                self::expected(self::BOX_NUMBER)  => '10',
            ],
            [
                self::FULL_STREET                   => 'Ildefonse Vandammestraat 5 D',
                self::COUNTRY                       => 'BE',
                self::CARRIER_ID                    => CarrierBpost::ID,
                self::expected(self::FULL_STREET)   => 'Ildefonse Vandammestraat 5 D',
                self::expected(self::STREET)        => 'Ildefonse Vandammestraat',
                self::expected(self::NUMBER)        => '5',
                self::expected(self::NUMBER_SUFFIX) => 'D',
            ],
            [
                self::FULL_STREET                   => 'I. Vandammestraat 5 D',
                self::COUNTRY                       => 'BE',
                self::CARRIER_ID                    => CarrierBpost::ID,
                self::expected(self::FULL_STREET)   => 'I. Vandammestraat 5 D',
                self::expected(self::STREET)        => 'I. Vandammestraat',
                self::expected(self::NUMBER)        => '5',
                self::expected(self::NUMBER_SUFFIX) => 'D',
            ],
            [
                self::FULL_STREET                   => 'Slameuterstraat 9B',
                self::COUNTRY                       => 'BE',
                self::CARRIER_ID                    => CarrierBpost::ID,
                self::expected(self::FULL_STREET)   => 'Slameuterstraat 9 B',
                self::expected(self::STREET)        => 'Slameuterstraat',
                self::expected(self::NUMBER)        => '9',
                self::expected(self::NUMBER_SUFFIX) => 'B',
            ],
            [
                self::FULL_STREET                 => 'Oud-Dorpsstraat 136 3',
                self::COUNTRY                     => 'BE',
                self::CARRIER_ID                  => CarrierBpost::ID,
                self::expected(self::FULL_STREET) => 'Oud-Dorpsstraat 136 bus 3',
                self::expected(self::STREET)      => 'Oud-Dorpsstraat',
                self::expected(self::NUMBER)      => '136',
                self::expected(self::BOX_NUMBER)  => '3',
            ],
            [
                self::FULL_STREET                   => 'Groenstraat 16 C',
                self::CARRIER_ID                    => CarrierBpost::ID,
                self::expected(self::FULL_STREET)   => 'Groenstraat 16 C',
                self::expected(self::STREET)        => 'Groenstraat',
                self::expected(self::NUMBER)        => '16',
                self::expected(self::NUMBER_SUFFIX) => 'C',
            ],
            [
                self::FULL_STREET                 => 'Brusselsesteenweg 30 /0101',
                self::COUNTRY                     => 'BE',
                self::CARRIER_ID                  => CarrierBpost::ID,
                self::expected(self::FULL_STREET) => 'Brusselsesteenweg 30 bus 0101',
                self::expected(self::STREET)      => 'Brusselsesteenweg',
                self::expected(self::NUMBER)      => 30,
                self::expected(self::BOX_NUMBER)  => '0101',
            ],
            [
                self::FULL_STREET                 => 'Onze-Lieve-Vrouwstraat 150/1',
                self::COUNTRY                     => 'BE',
                self::CARRIER_ID                  => CarrierBpost::ID,
                self::expected(self::FULL_STREET) => 'Onze-Lieve-Vrouwstraat 150 bus 1',
                self::expected(self::STREET)      => 'Onze-Lieve-Vrouwstraat',
                self::expected(self::NUMBER)      => 150,
                self::expected(self::BOX_NUMBER)  => '1',
            ],
            [
                self::FULL_STREET                 => 'Wilgenstraat 6/1',
                self::COUNTRY                     => 'BE',
                self::CARRIER_ID                  => CarrierBpost::ID,
                self::expected(self::FULL_STREET) => 'Wilgenstraat 6 bus 1',
                self::expected(self::STREET)      => 'Wilgenstraat',
                self::expected(self::NUMBER)      => 6,
                self::expected(self::BOX_NUMBER)  => '1',
            ],
            // todo:
            //  [
            //      self::FULL_STREET                            => 'Ir. Mr. Dr. van Waterschoot van der Grachtstraat in Heerlen 14 t',
            //      self::COUNTRY                                => 'NZ',
            //      self::expected(self::FULL_STREET)            => 'Ir. Mr. Dr. van Waterschoot van der',
            //      self::expected(self::STREET)                 => 'Ir. Mr. Dr. van Waterschoot van der',
            //      self::expected(self::STREET_ADDITIONAL_INFO) => 'Grachtstraat in Heerlen 14 t',
            //  ],
            [
                self::FULL_STREET => 'Taumatawhakatangihangakoauauotamateaturipukakapikimaungahoronukupokaiwhenuakitanatahu',
                self::COUNTRY     => 'NZ',
            ],
            [
                self::FULL_STREET            => 'testtienpp testtienpp',
                self::COUNTRY                => 'NZ',
                self::expected(self::STREET) => 'testtienpp testtienpp',
            ],
            // todo:
            //  [
            //      self::FULL_STREET                            => 'Ir. Mr. Dr. van Waterschoot van der Grachtstraat 14 t',
            //      self::expected(self::FULL_STREET)            => 'Ir. Mr. Dr. van Waterschoot van der 14 t',
            //      self::expected(self::STREET)                 => 'Ir. Mr. Dr. van Waterschoot van der 14 t',
            //      self::expected(self::STREET_ADDITIONAL_INFO) => 'Grachtstraat',
            //  ],
            [
                self::FULL_STREET                   => 'Koestraat 554 t',
                self::expected(self::STREET)        => 'Koestraat',
                self::expected(self::NUMBER)        => '554',
                self::expected(self::NUMBER_SUFFIX) => 't',
            ],
            [
                self::FULL_STREET => 'No. 7 street',
                self::COUNTRY     => 'FR',
            ],
            // todo:
            //  [
            //      self::FULL_STREET                            => 'Wethouder Fierman Eduard Meerburg senior kade 14 t',
            //      self::expected(self::STREET)                 => 'Wethouder Fierman Eduard Meerburg senior',
            //      self::expected(self::STREET_ADDITIONAL_INFO) => 'kade 14 t',
            //  ],
        ], [
            self::CARRIER_ID                             => CarrierPostNL::ID,
            self::COUNTRY                                => AbstractConsignment::CC_NL,
            self::FULL_STREET                            => null,
            self::expected(self::BOX_NUMBER)             => null,
            self::expected(self::FULL_STREET)            => null,
            self::expected(self::NUMBER)                 => null,
            self::expected(self::NUMBER_SUFFIX)          => null,
            self::expected(self::POSTAL_CODE)            => null,
            self::expected(self::STREET)                 => null,
            self::expected(self::STREET_ADDITIONAL_INFO) => null,
        ]);
    }

    /**
     * @dataProvider provideSplitStreetData
     *
     * @param  array $testData
     *
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @throws \Exception
     */
    public function testSplitStreet(array $testData): void
    {
        $expectedFullStreet = $testData[self::expected(self::FULL_STREET)] ?? $testData[self::FULL_STREET];
        $expectedStreet     = $testData[self::expected(self::STREET)] ?? $testData[self::FULL_STREET];

        unset($testData[self::POSTAL_CODE]);

        $consignment = $this->generateConsignment($testData);

        self::validateConsignmentOptions(
            [
                self::FULL_STREET            => $expectedFullStreet,
                self::STREET                 => $expectedStreet,
                self::STREET_ADDITIONAL_INFO => $testData[self::expected(self::STREET_ADDITIONAL_INFO)],
                self::NUMBER                 => $testData[self::expected(self::NUMBER)],
                self::NUMBER_SUFFIX          => $testData[self::expected(self::NUMBER_SUFFIX)],
                self::BOX_NUMBER             => $testData[self::expected(self::BOX_NUMBER)],
            ],
            $consignment
        );
    }
}
