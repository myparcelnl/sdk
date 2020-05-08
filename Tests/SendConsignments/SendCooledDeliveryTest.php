<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\tests\SendConsignments\SendCooledDeliveryTest;

use MyParcelNL\Sdk\src\Factory\ConsignmentFactory;
use MyParcelNL\Sdk\src\Helper\MyParcelCollection;
use MyParcelNL\Sdk\src\Model\Consignment\PostNLConsignment;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;

class SendCooledDeliveryTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @return $this
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @throws \Exception
     */
    public function testCooledDelivery()
    {
        if (getenv('API_KEY') == null) {
            echo "\033[31m Set MyParcel API-key in 'Environment variables' before running UnitTest. Example: API_KEY=f8912fb260639db3b1ceaef2730a4b0643ff0c31. PhpStorm example: http://take.ms/sgpgU5\n\033[0m";

            return $this;
        }

        foreach ($this->additionProvider() as $consignmentTest) {

            if (isset($consignmentTest['exception'])) {
                $this->expectExceptionMessage($consignmentTest['exception']);
            }

            $myParcelCollection = new MyParcelCollection();

            $consignment = (ConsignmentFactory::createByCarrierName($consignmentTest['carrier_id']))
                ->setApiKey($consignmentTest['api_key'])
                ->setCountry($consignmentTest['cc'])
                ->setPerson($consignmentTest['person'])
                ->setCompany($consignmentTest['company'])
                ->setFullStreet($consignmentTest['full_street'])
                ->setPostalCode($consignmentTest['postal_code'])
                ->setCity($consignmentTest['city'])
                ->setLabelDescription($consignmentTest['label_description']);

            if (key_exists('package_type', $consignmentTest)) {
                $consignment->setPackageType($consignmentTest['package_type']);
            }

            if (key_exists('cooled_delivery', $consignmentTest)) {
                $consignment->setCooledDelivery($consignmentTest['cooled_delivery']);
            }

            $myParcelCollection->addConsignment($consignment);

            /** @var AbstractConsignment $consignment */
            $consignment = $myParcelCollection->createConcepts()->setLatestData()->first();

            $this->assertEquals(true, $consignment->getConsignmentId() > 1, 'No id found');
            $this->assertEquals($consignmentTest['api_key'], $consignment->getApiKey(), 'getApiKey()');
            $this->assertEquals($consignmentTest['cc'], $consignment->getCountry(), 'getCountry()');

            if (key_exists('package_type', $consignmentTest)) {
                $this->assertEquals($consignmentTest['package_type'], $consignment->getPackageType(), 'getPackageType()');
            } else {
                $this->assertEquals(1, $consignment->getPackageType(), 'getPackageType()');
            }

            if (key_exists('cooled_delivery', $consignmentTest)) {
                $this->assertEquals($consignmentTest['cooled_delivery'], $consignment->hasCooledDelivery(), 'hasCooledDelivery()');
            }
            /**
             * Get label
             */
            $myParcelCollection
                ->setLinkOfLabels();

            $this->assertEquals(true, preg_match("#^https://api.myparcel.nl/pdfs#", $myParcelCollection->getLinkOfLabels()), 'Can\'t get link of PDF');

            /** @var AbstractConsignment $consignment */
            $consignment = $myParcelCollection->getOneConsignment();
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
            'Cooled delivery true'  => [
                'api_key'           => getenv('API_KEY'),
                'carrier_id'        => PostNLConsignment::CARRIER_NAME,
                'cc'                => 'NL',
                'person'            => 'Piet',
                'company'           => 'Mega Store',
                'full_street'       => 'Koestraat 55',
                'street'            => 'Koestraat',
                'number'            => 55,
                'number_suffix'     => '',
                'postal_code'       => '2231JE',
                'city'              => 'Katwijk',
                'package_type'      => AbstractConsignment::PACKAGE_TYPE_PACKAGE,
                'cooled_delivery'   => true,
                'label_description' => 'Cooled delivery true',
            ],
            'Cooled delivery false' => [
                'api_key'           => getenv('API_KEY'),
                'carrier_id'        => PostNLConsignment::CARRIER_NAME,
                'cc'                => 'NL',
                'person'            => 'Piet',
                'company'           => 'Mega Store',
                'full_street'       => 'Koestraat 55',
                'street'            => 'Koestraat',
                'number'            => 55,
                'number_suffix'     => '',
                'postal_code'       => '2231JE',
                'city'              => 'Katwijk',
                'package_type'      => AbstractConsignment::PACKAGE_TYPE_PACKAGE,
                'cooled_delivery'   => false,
                'label_description' => 'Cooled delivery false',
            ],
        ];
    }
}
