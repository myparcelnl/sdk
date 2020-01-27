<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\tests\SendConsignments\SendOneConsignmentPickupAutoDetectTest;

use MyParcelNL\Sdk\src\Factory\ConsignmentFactory;
use MyParcelNL\Sdk\src\Helper\MyParcelCollection;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\src\Model\Consignment\PostNLConsignment;

class SendOneConsignmentPickupAutoDetectTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @return \MyParcelNL\Sdk\tests\SendConsignments\SendOneConsignmentPickupAutoDetectTest\SendOneConsignmentPickupAutoDetectTest
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @throws \Exception
     */
    public function testSendOneConsignmentPickupAutoDetect()
    {
        if (getenv('API_KEY') == null) {
            echo "\033[31m Set MyParcel API-key in 'Environment variables' before running UnitTest. Example: API_KEY=f8912fb260639db3b1ceaef2730a4b0643ff0c31. PhpStorm example: http://take.ms/sgpgU5\n\033[0m";

            return $this;
        }

        foreach ($this->additionProvider() as $consignmentTest) {

            $myParcelCollection = new MyParcelCollection();
            $consignment        = (ConsignmentFactory::createByCarrierId($consignmentTest['carrier_id']))
                ->setApiKey($consignmentTest['api_key'])
                ->setCountry($consignmentTest['cc'])
                ->setPerson($consignmentTest['person'])
                ->setCompany($consignmentTest['company'])
                ->setFullStreet($consignmentTest['full_street'])
                ->setPostalCode($consignmentTest['postal_code'])
                ->setCity($consignmentTest['city'])
                ->setEmail($consignmentTest['email'])
                ->setPhone($consignmentTest['phone']);

            if (key_exists('auto_detect_pickup', $consignmentTest)) {
                $consignment->setAutoDetectPickup($consignmentTest['auto_detect_pickup']);
            }

            $myParcelCollection->addConsignment($consignment)->setLinkOfLabels();

            /** @var \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment $consignment */
            $consignment = $myParcelCollection->getOneConsignment();
            $this->assertEquals($consignmentTest['expected_delivery_type'], $consignment->getDeliveryType(), 'getDeliveryType()');
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
                'api_key'                => getenv('API_KEY'),
                'carrier_id'             => PostNLConsignment::CARRIER_ID,
                'cc'                     => 'NL',
                'person'                 => 'Richard',
                'company'                => 'Big Sale BV',
                'full_street'            => 'Hoofdweg 3',
                'street'                 => '',
                'number'                 => '3',
                'postal_code'            => '2132BA',
                'city'                   => 'Hoofddorp',
                'phone'                  => '123456',
                'email'                  => 'your_email@test.nl',
                'auto_detect_pickup'     => false,
                'expected_delivery_type' => AbstractConsignment::DELIVERY_TYPE_STANDARD,
            ],
            [
                'api_key'                => getenv('API_KEY'),
                'carrier_id'             => PostNLConsignment::CARRIER_ID,
                'cc'                     => 'NL',
                'person'                 => 'Richard',
                'company'                => 'Big Sale BV',
                'full_street'            => 'Hoofdweg 3',
                'street'                 => '',
                'number'                 => '3',
                'postal_code'            => '2132BA',
                'city'                   => 'Hoofddorp',
                'phone'                  => '123456',
                'email'                  => 'your_email@test.nl',
                'auto_detect_pickup'     => true,
                'expected_delivery_type' => AbstractConsignment::DELIVERY_TYPE_PICKUP,
            ],
        ];
    }
}
