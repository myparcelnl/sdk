<?php
/**
 * Test to check international shipment
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

namespace myparcelnl\sdk\Tests\SendConsignments;


use MyParcelNL\Sdk\src\Helper\MyParcelCollection;
use MyParcelNL\Sdk\src\Model\Repository\MyParcelConsignmentRepository;

/**
 * Class SendInternationalShipmentTest
 * @package MyParcelNL\Sdk\tests\SendInternationalShipmentTest
 */
class SendInternationalShipmentTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test one shipment with createConcepts()
     */
    public function testSendOneConsignment()
    {
        foreach ($this->additionProvider() as $consignmentTest) {

            $myParcelCollection = new MyParcelCollection();

            $consignment = (new MyParcelConsignmentRepository())
                ->setApiKey($consignmentTest['api_key'])
                ->setCountry($consignmentTest['cc'])
                ->setPerson($consignmentTest['person'])
                ->setCompany($consignmentTest['company'])
                ->setFullStreet($consignmentTest['full_street'])
                ->setPostalCode($consignmentTest['postal_code'])
                ->setCity($consignmentTest['city'])
                ->setEmail('reindert@myparcel.nl')
                ->setPhone($consignmentTest['phone']);

            if (key_exists('label_description', $consignmentTest)) {
                            $consignment->setLabelDescription($consignmentTest['label_description']);
            }

            $myParcelCollection->addConsignment($consignment);

            /**
             * Create concept
             */
            $myParcelCollection->createConcepts();

            $this->assertEquals(true, $consignment->getMyParcelConsignmentId() > 1, 'No id found');
            $this->assertEquals($consignmentTest['api_key'], $consignment->getApiKey(), 'getApiKey()');
            $this->assertEquals($consignmentTest['cc'], $consignment->getCountry(), 'getCountry()');
            $this->assertEquals($consignmentTest['person'], $consignment->getPerson(), 'getPerson()');
            $this->assertEquals($consignmentTest['company'], $consignment->getCompany(), 'getCompany()');
            $this->assertEquals($consignmentTest['full_street'], $consignment->getFullStreet(), 'getFullStreet()');
            $this->assertEquals($consignmentTest['postal_code'], $consignment->getPostalCode(), 'getPostalCode()');
            $this->assertEquals($consignmentTest['city'], $consignment->getCity(), 'getCity()');
            $this->assertEquals($consignmentTest['phone'], $consignment->getPhone(), 'getPhone()');

            if (key_exists('label_description', $consignmentTest)) {
                            $this->assertEquals($consignmentTest['label_description'], $consignment->getLabelDescription(), 'getLabelDescription()');
            }
        }
    }

    /**
     * Data for the test
     * @return array
     */
    public function additionProvider()
    {
        return [
            [
                'api_key' => 'MYSNIzQWqNrYaDeFxJtVrujS9YEuF9kiykBxf8Sj',
                'cc' => 'NR',
                'person' => 'Reindert',
                'company' => 'Big Sale BV',
                'full_street' => 'Plein 1940-45 3 b',
                'postal_code' => '2231JE',
                'city' => 'Rijnsburg',
                'phone' => '123456',
            ]
        ];
    }
}