<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\tests\SendConsignments;

use Exception;
use MyParcelNL\Sdk\src\Factory\ConsignmentFactory;
use MyParcelNL\Sdk\src\Helper\MyParcelCollection;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\src\Model\Consignment\PostNLConsignment;
use MyParcelNL\Sdk\src\Model\MyParcelRequest;
use PHPUnit\Framework\TestCase;

class SendMultipleConsignmentsTest extends TestCase
{

    /**
     * Create multiple shipments with createConcepts()
     *
     * @throws Exception
     */
    public function testSendMultipleConsignments()
    {
        if (getenv('API_KEY') == null || getenv('API_KEY2') == null) {
            echo "\033[31m Set 2 MyParcel API-keys in 'Environment variables' before running UnitTest. Example: API_KEY=f8912fb260639db3b1ceaef2730a4b0643ff0c31 and API_KEY2=f8912fb260sert4564bdsafds45y6afasd7fdas\n\033[0m";

            return $this;
        }

        /** move to __constructor */
        $myParcelCollection = new MyParcelCollection();

        foreach ($this->additionProvider() as $referenceId => $consignmentTest) {
            $consignment =
                (ConsignmentFactory::createByCarrierId($consignmentTest['carrier_id']))
                    ->setReferenceId($referenceId)
                    ->setApiKey($consignmentTest['api_key'])
                    ->setCountry($consignmentTest['cc'])
                    ->setPerson($consignmentTest['person'])
                    ->setCompany($consignmentTest['company'])
                    ->setFullStreet($consignmentTest['full_street'])
                    ->setPostalCode($consignmentTest['postal_code'])
                    ->setPackageType($consignmentTest['package_type'])
                    ->setCity($consignmentTest['city'])
                    ->setEmail($consignmentTest['email']);
            $myParcelCollection->addConsignment($consignment);
        }

        $myParcelCollection->createConcepts();

        $prevConsignmentId = null;

        /**
         * Check if each shipment gets a unique consignment id
         */
        foreach ($myParcelCollection->getConsignments() as $consignment) {
            $this->assertNotEquals($prevConsignmentId, $consignment->getConsignmentId());
            $prevConsignmentId = $consignment->getConsignmentId();
        }

        /**
         * Get label
         */
        $myParcelCollection->setLinkOfLabels();

        $this->assertEquals(
            true,
            preg_match("#^" . (new MyParcelRequest())->getRequestUrl() . "/pdfs#", $myParcelCollection->getLinkOfLabels()),
            'Can\'t get link of PDF'
        );

        foreach ($this->additionProvider() as $referenceId => $consignmentTest) {
            $consignment = $myParcelCollection->getConsignmentsByReferenceId($referenceId)->first();
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
            'prefix_101' => [
                'api_key'           => getenv('API_KEY'),
                'carrier_id'        => PostNLConsignment::CARRIER_ID,
                'cc'                => 'NL',
                'person'            => 'Reindert',
                'company'           => 'Big Sale BV',
                'full_street'       => 'Plein 1940-45 3 b',
                'street'            => 'Plein 1940-45',
                'number'            => 3,
                'number_suffix'     => 'b',
                'postal_code'       => '2231 JE',
                'city'              => 'Rijnsburg',
                'email'             => 'your_email@test.nl',
                'package_type'      => AbstractConsignment::PACKAGE_TYPE_PACKAGE,
            ],
            'prefix_104' => [
                'api_key'           => getenv('API_KEY2'),
                'carrier_id'        => PostNLConsignment::CARRIER_ID,
                'cc'                => 'NL',
                'person'            => 'Piet',
                'company'           => 'Mega Store',
                'full_street'       => 'Koestraat 55',
                'street'            => 'Koestraat',
                'number'            => 55,
                'number_suffix'     => '',
                'postal_code'       => '2231JE',
                'city'              => 'Katwijk',
                'email'             => 'your_email@test.nl',
                'package_type'      => AbstractConsignment::PACKAGE_TYPE_PACKAGE,
                'large_format'      => false,
                'age_check'         => false,
                'only_recipient'    => false,
                'signature'         => false,
                'return'            => false,
                'label_description' => 'Label description',
            ],
            'prefix_105' => [
                'api_key'           => getenv('API_KEY2'),
                'carrier_id'        => PostNLConsignment::CARRIER_ID,
                'cc'                => 'NL',
                'person'            => 'The insurance man',
                'company'           => 'Mega Store',
                'full_street'       => 'Koestraat 55',
                'street'            => 'Koestraat',
                'number'            => 55,
                'number_suffix'     => '',
                'postal_code'       => '2231JE',
                'city'              => 'Katwijk',
                'email'             => 'your_email@test.nl',
                'package_type'      => AbstractConsignment::PACKAGE_TYPE_PACKAGE,
                'large_format'      => true,
                'age_check'         => false,
                'only_recipient'    => true,
                'signature'         => true,
                'return'            => true,
                'label_description' => 1234,
                'insurance'         => 250,
            ],
            'prefix_106' => [
                'api_key'           => getenv('API_KEY2'),
                'carrier_id'        => PostNLConsignment::CARRIER_ID,
                'cc'                => 'NL',
                'person'            => 'The insurance man',
                'company'           => 'Mega Store',
                'full_street'       => 'Koestraat 55',
                'street'            => 'Koestraat',
                'number'            => 55,
                'number_suffix'     => '',
                'postal_code'       => '2231JE',
                'city'              => 'Katwijk',
                'email'             => 'your_email@test.nl',
                'package_type'      => AbstractConsignment::PACKAGE_TYPE_PACKAGE,
                'large_format'      => true,
                'age_check'         => false,
                'only_recipient'    => true,
                'signature'         => true,
                'return'            => true,
                'label_description' => 1234,
                'insurance'         => 250,
            ],
            'prefix_107' => [
                'api_key'           => getenv('API_KEY2'),
                'carrier_id'        => PostNLConsignment::CARRIER_ID,
                'cc'                => 'NL',
                'person'            => 'The insurance man',
                'company'           => 'Mega Store',
                'full_street'       => 'Koestraat 55',
                'street'            => 'Koestraat',
                'number'            => 55,
                'number_suffix'     => '',
                'postal_code'       => '2231JE',
                'city'              => 'Katwijk',
                'email'             => 'your_email@test.nl',
                'package_type'      => AbstractConsignment::PACKAGE_TYPE_PACKAGE,
                'large_format'      => true,
                'age_check'         => false,
                'only_recipient'    => true,
                'signature'         => true,
                'return'            => true,
                'label_description' => 1234,
                'insurance'         => 250,
            ],
            'prefix_108' => [
                'api_key'           => getenv('API_KEY2'),
                'carrier_id'        => PostNLConsignment::CARRIER_ID,
                'cc'                => 'NL',
                'person'            => 'The insurance man',
                'company'           => 'Mega Store',
                'full_street'       => 'Koestraat 55',
                'street'            => 'Koestraat',
                'number'            => 55,
                'number_suffix'     => '',
                'postal_code'       => '2231JE',
                'city'              => 'Katwijk',
                'email'             => 'your_email@test.nl',
                'package_type'      => AbstractConsignment::PACKAGE_TYPE_PACKAGE,
                'large_format'      => true,
                'age_check'         => false,
                'only_recipient'    => true,
                'signature'         => true,
                'return'            => true,
                'label_description' => 1234,
                'insurance'         => 250,
            ],
            'prefix_109' => [
                'api_key'           => getenv('API_KEY2'),
                'carrier_id'        => PostNLConsignment::CARRIER_ID,
                'cc'                => 'NL',
                'person'            => 'The insurance man',
                'company'           => 'Mega Store',
                'full_street'       => 'Koestraat 55',
                'street'            => 'Koestraat',
                'number'            => 55,
                'number_suffix'     => '',
                'postal_code'       => '2231JE',
                'city'              => 'Katwijk',
                'email'             => 'your_email@test.nl',
                'package_type'      => AbstractConsignment::PACKAGE_TYPE_PACKAGE,
                'large_format'      => true,
                'age_check'         => false,
                'only_recipient'    => true,
                'signature'         => true,
                'return'            => true,
                'label_description' => 1234,
                'insurance'         => 250,
            ],
            'prefix_110' => [
                'api_key'           => getenv('API_KEY2'),
                'carrier_id'        => PostNLConsignment::CARRIER_ID,
                'cc'                => 'NL',
                'person'            => 'The insurance man',
                'company'           => 'Mega Store',
                'full_street'       => 'Koestraat 55',
                'street'            => 'Koestraat',
                'number'            => 55,
                'number_suffix'     => '',
                'postal_code'       => '2231JE',
                'city'              => 'Katwijk',
                'email'             => 'your_email@test.nl',
                'package_type'      => AbstractConsignment::PACKAGE_TYPE_PACKAGE,
                'large_format'      => true,
                'age_check'         => false,
                'only_recipient'    => true,
                'signature'         => true,
                'return'            => true,
                'label_description' => 1234,
                'insurance'         => 250,
            ],
            'prefix_111' => [
                'api_key'           => getenv('API_KEY2'),
                'carrier_id'        => PostNLConsignment::CARRIER_ID,
                'cc'                => 'NL',
                'person'            => 'The insurance man',
                'company'           => 'Mega Store',
                'full_street'       => 'Koestraat 55',
                'street'            => 'Koestraat',
                'number'            => 55,
                'number_suffix'     => '',
                'postal_code'       => '2231JE',
                'city'              => 'Katwijk',
                'email'             => 'your_email@test.nl',
                'package_type'      => AbstractConsignment::PACKAGE_TYPE_PACKAGE,
                'large_format'      => true,
                'age_check'         => false,
                'only_recipient'    => true,
                'signature'         => true,
                'return'            => true,
                'label_description' => 1234,
                'insurance'         => 250,
            ],
            'prefix_112' => [
                'api_key'           => getenv('API_KEY2'),
                'carrier_id'        => PostNLConsignment::CARRIER_ID,
                'cc'                => 'NL',
                'person'            => 'The insurance man',
                'company'           => 'Mega Store',
                'full_street'       => 'Koestraat 55',
                'street'            => 'Koestraat',
                'number'            => 55,
                'number_suffix'     => '',
                'postal_code'       => '2231JE',
                'city'              => 'Katwijk',
                'email'             => 'your_email@test.nl',
                'package_type'      => AbstractConsignment::PACKAGE_TYPE_PACKAGE,
                'large_format'      => true,
                'age_check'         => false,
                'only_recipient'    => true,
                'signature'         => true,
                'return'            => true,
                'label_description' => 1234,
                'insurance'         => 250,
            ],
            'prefix_113' => [
                'api_key'           => getenv('API_KEY2'),
                'carrier_id'        => PostNLConsignment::CARRIER_ID,
                'cc'                => 'NL',
                'person'            => 'The insurance man',
                'company'           => 'Mega Store',
                'full_street'       => 'Koestraat 55',
                'street'            => 'Koestraat',
                'number'            => 55,
                'number_suffix'     => '',
                'postal_code'       => '2231JE',
                'city'              => 'Katwijk',
                'email'             => 'your_email@test.nl',
                'package_type'      => AbstractConsignment::PACKAGE_TYPE_PACKAGE,
                'large_format'      => true,
                'age_check'         => false,
                'only_recipient'    => true,
                'signature'         => true,
                'return'            => true,
                'label_description' => 1234,
                'insurance'         => 250,
            ],
            'prefix_114' => [
                'api_key'           => getenv('API_KEY2'),
                'carrier_id'        => PostNLConsignment::CARRIER_ID,
                'cc'                => 'NL',
                'person'            => 'The insurance man',
                'company'           => 'Mega Store',
                'full_street'       => 'Koestraat 55',
                'street'            => 'Koestraat',
                'number'            => 55,
                'number_suffix'     => '',
                'postal_code'       => '2231JE',
                'city'              => 'Katwijk',
                'email'             => 'your_email@test.nl',
                'package_type'      => AbstractConsignment::PACKAGE_TYPE_PACKAGE,
                'large_format'      => true,
                'age_check'         => false,
                'only_recipient'    => true,
                'signature'         => true,
                'return'            => true,
                'label_description' => 1234,
                'insurance'         => 250,
            ],
            'prefix_115' => [
                'api_key'           => getenv('API_KEY2'),
                'carrier_id'        => PostNLConsignment::CARRIER_ID,
                'cc'                => 'NL',
                'person'            => 'The insurance man',
                'company'           => 'Mega Store',
                'full_street'       => 'Koestraat 55',
                'street'            => 'Koestraat',
                'number'            => 55,
                'number_suffix'     => '',
                'postal_code'       => '2231JE',
                'city'              => 'Katwijk',
                'email'             => 'your_email@test.nl',
                'package_type'      => AbstractConsignment::PACKAGE_TYPE_PACKAGE,
                'large_format'      => true,
                'age_check'         => false,
                'only_recipient'    => true,
                'signature'         => true,
                'return'            => true,
                'label_description' => 1234,
                'insurance'         => 250,
            ],
            'prefix_116' => [
                'api_key'           => getenv('API_KEY2'),
                'carrier_id'        => PostNLConsignment::CARRIER_ID,
                'cc'                => 'NL',
                'person'            => 'The insurance man',
                'company'           => 'Mega Store',
                'full_street'       => 'Koestraat 55',
                'street'            => 'Koestraat',
                'number'            => 55,
                'number_suffix'     => '',
                'postal_code'       => '2231JE',
                'city'              => 'Katwijk',
                'email'             => 'your_email@test.nl',
                'package_type'      => AbstractConsignment::PACKAGE_TYPE_PACKAGE,
                'large_format'      => true,
                'age_check'         => false,
                'only_recipient'    => true,
                'signature'         => true,
                'return'            => true,
                'label_description' => 1234,
                'insurance'         => 250,
            ],
            'prefix_117' => [
                'api_key'           => getenv('API_KEY2'),
                'carrier_id'        => PostNLConsignment::CARRIER_ID,
                'cc'                => 'NL',
                'person'            => 'The insurance man',
                'company'           => 'Mega Store',
                'full_street'       => 'Koestraat 55',
                'street'            => 'Koestraat',
                'number'            => 55,
                'number_suffix'     => '',
                'postal_code'       => '2231JE',
                'city'              => 'Katwijk',
                'email'             => 'your_email@test.nl',
                'package_type'      => AbstractConsignment::PACKAGE_TYPE_PACKAGE,
                'large_format'      => true,
                'age_check'         => false,
                'only_recipient'    => true,
                'signature'         => true,
                'return'            => true,
                'label_description' => 1234,
                'insurance'         => 250,
            ],
            'prefix_118' => [
                'api_key'           => getenv('API_KEY2'),
                'carrier_id'        => PostNLConsignment::CARRIER_ID,
                'cc'                => 'NL',
                'person'            => 'The insurance man',
                'company'           => 'Mega Store',
                'full_street'       => 'Koestraat 55',
                'street'            => 'Koestraat',
                'number'            => 55,
                'number_suffix'     => '',
                'postal_code'       => '2231JE',
                'city'              => 'Katwijk',
                'email'             => 'your_email@test.nl',
                'package_type'      => AbstractConsignment::PACKAGE_TYPE_PACKAGE,
                'large_format'      => true,
                'age_check'         => false,
                'only_recipient'    => true,
                'signature'         => true,
                'return'            => true,
                'label_description' => 1234,
                'insurance'         => 250,
            ],
            'prefix_119' => [
                'api_key'           => getenv('API_KEY2'),
                'carrier_id'        => PostNLConsignment::CARRIER_ID,
                'cc'                => 'NL',
                'person'            => 'The insurance man',
                'company'           => 'Mega Store',
                'full_street'       => 'Koestraat 55',
                'street'            => 'Koestraat',
                'number'            => 55,
                'number_suffix'     => '',
                'postal_code'       => '2231JE',
                'city'              => 'Katwijk',
                'email'             => 'your_email@test.nl',
                'package_type'      => AbstractConsignment::PACKAGE_TYPE_PACKAGE,
                'large_format'      => true,
                'age_check'         => false,
                'only_recipient'    => true,
                'signature'         => true,
                'return'            => true,
                'label_description' => 1234,
                'insurance'         => 250,
            ],
            'prefix_120' => [
                'api_key'           => getenv('API_KEY2'),
                'carrier_id'        => PostNLConsignment::CARRIER_ID,
                'cc'                => 'NL',
                'person'            => 'The insurance man',
                'company'           => 'Mega Store',
                'full_street'       => 'Koestraat 55',
                'street'            => 'Koestraat',
                'number'            => 55,
                'number_suffix'     => '',
                'postal_code'       => '2231JE',
                'city'              => 'Katwijk',
                'email'             => 'your_email@test.nl',
                'package_type'      => AbstractConsignment::PACKAGE_TYPE_PACKAGE,
                'large_format'      => true,
                'age_check'         => false,
                'only_recipient'    => true,
                'signature'         => true,
                'return'            => true,
                'label_description' => 1234,
                'insurance'         => 250,
            ],
            'prefix_121' => [
                'api_key'           => getenv('API_KEY2'),
                'carrier_id'        => PostNLConsignment::CARRIER_ID,
                'cc'                => 'NL',
                'person'            => 'The insurance man',
                'company'           => 'Mega Store',
                'full_street'       => 'Koestraat 55',
                'street'            => 'Koestraat',
                'number'            => 55,
                'number_suffix'     => '',
                'postal_code'       => '2231JE',
                'city'              => 'Katwijk',
                'email'             => 'your_email@test.nl',
                'package_type'      => AbstractConsignment::PACKAGE_TYPE_PACKAGE,
                'large_format'      => true,
                'age_check'         => false,
                'only_recipient'    => true,
                'signature'         => true,
                'return'            => true,
                'label_description' => 1234,
                'insurance'         => 250,
            ],
            'prefix_122' => [
                'api_key'           => getenv('API_KEY2'),
                'carrier_id'        => PostNLConsignment::CARRIER_ID,
                'cc'                => 'NL',
                'person'            => 'The insurance man',
                'company'           => 'Mega Store',
                'full_street'       => 'Koestraat 55',
                'street'            => 'Koestraat',
                'number'            => 55,
                'number_suffix'     => '',
                'postal_code'       => '2231JE',
                'city'              => 'Katwijk',
                'email'             => 'your_email@test.nl',
                'package_type'      => AbstractConsignment::PACKAGE_TYPE_PACKAGE,
                'large_format'      => true,
                'age_check'         => false,
                'only_recipient'    => true,
                'signature'         => true,
                'return'            => true,
                'label_description' => 1234,
                'insurance'         => 250,
            ],
        ];
    }
}
