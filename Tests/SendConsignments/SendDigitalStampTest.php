<?php declare(strict_types=1);

namespace MyParcelNL\Sdk\tests\SendConsignments\SendDigitalStampTest;

use MyParcelNL\Sdk\src\Helper\MyParcelCollection;
use MyParcelNL\Sdk\src\Concerns\HasDebugLabels;
use MyParcelNL\Sdk\src\Factory\ConsignmentFactory;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\src\Model\Consignment\PostNLConsignment;

/**
 * Class SendDigitalStampTest
 * @package MyParcelNL\Sdk\tests\SendDigitalStampTest
 */
class SendDigitalStampTest extends \PHPUnit\Framework\TestCase
{
    use HasDebugLabels;

    /**
     * Test one shipment with createConcepts()
     * @throws \Exception
     *
     * @return void
     */
    public function testSendOneConsignment(): void
    {
        if (getenv('API_KEY') == null) {
            echo "\033[31m Set MyParcel API-key in 'Environment variables' before running UnitTest. Example: API_KEY=f8912fb260639db3b1ceaef2730a4b0643ff0c31. PhpStorm example: http://take.ms/sgpgU5\n\033[0m";
            return;
        }

        foreach ($this->additionProvider() as $consignmentTest) {

            $myParcelCollection = new MyParcelCollection();

            $consignment = (ConsignmentFactory::createByCarrierId($consignmentTest['carrier_id']))
                ->setApiKey($consignmentTest['api_key'])
                ->setPackageType(AbstractConsignment::PACKAGE_TYPE_DIGITAL_STAMP)
                ->setCountry($consignmentTest['cc'])
                ->setPerson($consignmentTest['person'])
                ->setCompany($consignmentTest['company'])
                ->setFullStreet($consignmentTest['full_street'])
                ->setPostalCode($consignmentTest['postal_code'])
                ->setCity($consignmentTest['city'])
                ->setEmail('your_email@test.nl')
                ->setPhone($consignmentTest['phone'])
                ->setTotalWeight($consignmentTest['weight']);

            if (key_exists('package_type', $consignmentTest)) {
                $consignment->setPackageType($consignmentTest['package_type']);
            }

            if (key_exists('label_description', $consignmentTest)) {
                $consignment->setLabelDescription($consignmentTest['label_description']);
            }

            $myParcelCollection
                ->addConsignment($consignment)
                ->setLinkOfLabels();

            $this->assertEquals(true, preg_match("#^https://api.myparcel.nl/pdfs#", $myParcelCollection->getLinkOfLabels()), 'Can\'t get link of PDF');

            $this->debugLinkOfLabels($myParcelCollection, 'digital stamp shipment');
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
                'api_key'           => getenv('API_KEY'),
                'carrier_id'        => PostNLConsignment::CARRIER_ID,
                'cc'                => 'NL',
                'person'            => 'Reindert',
                'company'           => 'Big Sale BV',
                'full_street_input' => 'Plein 1940-45 3b',
                'full_street'       => 'Plein 1940-45 3 b',
                'street'            => 'Plein 1940-45',
                'number'            => 3,
                'number_suffix'     => 'b',
                'postal_code'       => '2231JE',
                'city'              => 'Rijnsburg',
                'phone'             => '123456',
                'package_type'      => AbstractConsignment::PACKAGE_TYPE_DIGITAL_STAMP,
                'label_description' => 112345,
                'weight'            => 76
            ],
            [
                'api_key'           => getenv('API_KEY'),
                'carrier_id'        => PostNLConsignment::CARRIER_ID,
                'cc'                => 'NL',
                'person'            => 'Reindert',
                'company'           => 'Big Sale BV',
                'full_street_input' => 'Plein 1940-45 3b',
                'full_street'       => 'Plein 1940-45 3 b',
                'street'            => 'Plein 1940-45',
                'number'            => 3,
                'number_suffix'     => 'b',
                'postal_code'       => '2231JE',
                'city'              => 'Rijnsburg',
                'phone'             => '123456',
                'package_type'      => AbstractConsignment::PACKAGE_TYPE_DIGITAL_STAMP,
                'label_description' => 112345,
                'weight'            => 1999
            ],
            [
                'api_key'           => getenv('API_KEY'),
                'carrier_id'        => PostNLConsignment::CARRIER_ID,
                'cc'                => 'NL',
                'person'            => 'Reindert',
                'company'           => 'Big Sale BV',
                'full_street_input' => 'Plein 1940-45 3b',
                'full_street'       => 'Plein 1940-45 3 b',
                'street'            => 'Plein 1940-45',
                'number'            => 3,
                'number_suffix'     => 'b',
                'postal_code'       => '2231JE',
                'city'              => 'Rijnsburg',
                'phone'             => '123456',
                'package_type'      => AbstractConsignment::PACKAGE_TYPE_DIGITAL_STAMP,
                'label_description' => 112345,
                'weight'            => 0

            ],
        ];
    }
}