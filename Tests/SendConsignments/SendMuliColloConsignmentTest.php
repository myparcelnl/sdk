<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\tests\SendConsignments\SendOneConsignmentTest;

use MyParcelNL\Sdk\src\Factory\ConsignmentFactory;
use MyParcelNL\Sdk\src\Helper\MyParcelCollection;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\src\Model\Consignment\PostNLConsignment;

class SendMuliColloConsignmentTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @return $this
     * @throws \Exception
     */
    public function testSendMuliColloConsignment()
    {
        if (getenv('API_KEY') == null) {
            echo "\033[31m Set MyParcel API-key in 'Environment variables' before running UnitTest. Example: API_KEY=f8912fb260639db3b1ceaef2730a4b0643ff0c31. PhpStorm example: http://take.ms/sgpgU5\n\033[0m";

            return $this;
        }

        foreach ($this->additionProvider() as $consignmentTest) {

            $myParcelCollection = new MyParcelCollection();

            $consignment = (ConsignmentFactory::createByCarrierId($consignmentTest['carrier_id']))
                ->setApiKey($consignmentTest['api_key'])
                ->setCountry($consignmentTest['cc'])
                ->setPerson($consignmentTest['person'])
                ->setCompany($consignmentTest['company'])
                ->setFullStreet($consignmentTest['full_street_input'])
                ->setPostalCode($consignmentTest['postal_code'])
                ->setCity($consignmentTest['city'])
                ->setEmail('your_email@test.nl')
                ->setPhone($consignmentTest['phone']);

            $myParcelCollection->addMultiCollo($consignment, $consignmentTest['multi_collo_amount']);

            $this->assertCount($consignmentTest['multi_collo_amount'], $myParcelCollection);

            /**
             * Get label
             */
            $myParcelCollection->setLinkOfLabels();

            $this->assertEquals(true, preg_match("#^https://api.myparcel.nl/pdfs#", $myParcelCollection->getLinkOfLabels()), 'Can\'t get link of PDF');

            /** @var AbstractConsignment[] $consignments */
            $consignments = $myParcelCollection->getConsignments();

            $this->assertNotEquals($consignments[0]->getConsignmentId(), $consignments[1]->getConsignmentId());
            $this->assertNotEquals($consignments[0]->getBarcode(), $consignments[1]->getBarcode());
            $this->assertTrue($consignments[0]->isPartOfMultiCollo());
            $this->assertTrue($consignments[1]->isPartOfMultiCollo());
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
                'api_key'            => getenv('API_KEY'),
                'carrier_id'         => PostNLConsignment::CARRIER_ID,
                'cc'                 => 'NL',
                'person'             => 'Reindert',
                'company'            => 'Big Sale BV',
                'full_street_input'  => 'Plein 1940-45 3b',
                'full_street'        => 'Plein 1940-45 3 b',
                'street'             => 'Plein 1940-45',
                'number'             => 3,
                'number_suffix'      => 'b',
                'postal_code'        => '2231JE',
                'city'               => 'Rijnsburg',
                'phone'              => '123456',
                'multi_collo_amount' => 3,
            ],
        ];
    }
}
