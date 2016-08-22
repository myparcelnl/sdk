<?php

/**
 * Test for split addresses from full street
 *
 * LICENSE: This source file is subject to the Creative Commons License.
 * It is available through the world-wide-web at this URL:
 * http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US
 *
 * If you want to add improvements, please create a fork in our GitHub:
 * https://github.com/myparcelnl
 *
 * @author      Reindert Vetter <reindert@myparcel.nl>
 * @copyright   2010-2016 MyParcel
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US  CC BY-NC-ND 3.0 NL
 * @link        https://github.com/myparcelnl/sdk
 * @since       File available since Release 0.1.0
 */
namespace MyParcel\sdk\tests\SendConsignments\
SendOneConsignmentTest;

use MyParcel\sdk\Helper\MyParcelAPI;
use MyParcel\sdk\Model\Repository\MyParcelConsignmentRepository;


/**
 * Class SendOneConsignmentTest
 * @package MyParcel\sdk\tests\SendOneConsignmentTest
 */
class SendOneConsignmentTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test one send()
     */
    public function testSendOneConsignment()
    {
        $myParcelAPI = new MyParcelAPI();

        foreach ($this->additionProvider() as $consignmentTest) {


            $consignment = new MyParcelConsignmentRepository();
            $consignment->setApiKey('NL');
            $consignment->setCc('NL');
            $consignment->setFullStreet($consignmentTest['full_street_test']);

            $myParcelAPI->addConsignment($consignment);
        }
        $myParcelAPI->registerConcept();
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
                'cc' => 'NL',
                'full_street_test' => 'Plein 1940-45 3b',
                'full_street' => 'Plein 1940-45 3 b',
                'street' => 'Plein 1940-45',
                'number' => 3,
                'number_suffix' => 'b',
            ]
        ];
    }
}