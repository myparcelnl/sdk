<?php declare(strict_types=1);

/**
 * If you want to add improvements, please create a fork in our GitHub:
 * https://github.com/myparcelnl
 *
 * @author      Reindert Vetter <reindert@myparcel.nl>
 * @copyright   2010-2017 MyParcel
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US  CC BY-NC-ND 3.0 NL
 * @link        https://github.com/myparcelnl/sdk
 * @since       File available since Release v0.1.0
 */

namespace MyParcelNL\Sdk\tests\SendConsignments\SendMultiReferenceIdentifierConsignmentTest;

use MyParcelNL\Sdk\src\Helper\MyParcelCollection;
use MyParcelNL\Sdk\src\Factory\ConsignmentFactory;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\src\Model\Consignment\PostNLConsignment;

class SendEqualReferenceIdentifierTest extends \PHPUnit\Framework\TestCase
{

    private $timestamp;

    public function setUp()
    {
        $this->timestamp = (new \DateTime())->getTimestamp();
    }

    /**
     * Test one shipment with createConcepts()
     *
     * @param array $consignmentTest
     *
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @throws \Exception
     *
     * @dataProvider additionProvider()
     */
    public function testSendOneConsignment(array $consignmentTest): void
    {
        if (getenv('API_KEY') == null) {
            echo "\033[31m Set MyParcel API-key in 'Environment variables' before running UnitTest. Example: API_KEY=f8912fb260639db3b1ceaef2730a4b0643ff0c31. PhpStorm example: http://take.ms/sgpgU5\n\033[0m";

            return;
        }

        $myParcelCollection = new MyParcelCollection();

        $consignment = (ConsignmentFactory::createByCarrierId($consignmentTest['carrier_id']))
            ->setApiKey($consignmentTest['api_key'])
            ->setReferenceId($consignmentTest['reference_identifier'])
            ->setCountry($consignmentTest['cc'])
            ->setPerson($consignmentTest['person'])
            ->setCompany($consignmentTest['company'])
            ->setFullStreet($consignmentTest['full_street'])
            ->setPostalCode($consignmentTest['postal_code'])
            ->setCity($consignmentTest['city'])
            ->setEmail('your_email@test.nl')
            ->setPhone($consignmentTest['phone']);

        $myParcelCollection->addConsignment($consignment);

        /**
         * Create concept
         */
        $myParcelCollection->createConcepts()->setLatestData()->first();

        $savedCollection = MyParcelCollection::findByReferenceId($consignmentTest['reference_identifier'], $consignmentTest['api_key']);

        $savedCollection->setLatestData();

        $consignmentTest   = $this->additionProvider()[0];
        $savedConsignments = $savedCollection->getConsignmentsByReferenceId($consignmentTest['reference_identifier']);
        $this->assertCount(2, $savedConsignments);
    }

    /**
     * Data for the test
     *
     * @return array
     */
    public function additionProvider()
    {
        return [
            'normal_consignment' => [
                [
                    'api_key'              => getenv('API_KEY'),
                    'carrier_id'           => PostNLConsignment::CARRIER_ID,
                    'reference_identifier' => (string) $this->timestamp . '_input',
                    'cc'                   => 'NL',
                    'person'               => 'Reindert',
                    'company'              => 'Big Sale BV',
                    'full_street_input'    => 'Plein 1940-45 3b',
                    'full_street'          => 'Plein 1940-45 3 b',
                    'street'               => 'Plein 1940-45',
                    'number'               => 3,
                    'number_suffix'        => 'b',
                    'postal_code'          => '2231JE',
                    'city'                 => 'Rijnsburg',
                    'phone'                => '123456',
                ],
            ],
        ];
    }
}