<?php
/**
 * A test to send a return email
 *
 * LICENSE: This source file is subject to the Creative Commons License.
 * It is available through the world-wide-web at this URL:
 * http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US
 *
 * If you want to add improvements, please create a fork in our GitHub:
 * https://github.com/myparcelnl/magento
 *
 * @author      Reindert Vetter <reindert@myparcel.nl>
 * @copyright   2010-2017 MyParcel
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US  CC BY-NC-ND 3.0 NL
 * @link        https://github.com/myparcelnl/magento
 * @since       File available since Release 2.0.0
 */

namespace MyParcelNL\Sdk\Tests\ReturnLabelTest;

use MyParcelNL\Sdk\src\Helper\MyParcelCollection;
use MyParcelNL\Sdk\src\Model\Repository\MyParcelConsignmentRepository;

class SendReturnLabelMailTest extends \PHPUnit_Framework_TestCase
{
    public function testSendReturnLabelMail()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        if (getenv('API_KEY') == null) {
            echo "\033[31m Set MyParcel API-key in 'Environment variables' before running UnitTest. Example: API_KEY=f8912fb260639db3b1ceaef2730a4b0643ff0c31. PhpStorm example: http://take.ms/sgpgU5\n\033[0m";
            return $this;
        }

        $parentConsignment = $this->getParentConsignment();
        $parentConsignment->sendReturnLabelMail();

        $this->assertNotNull($parentConsignment);
    }

    /**
     * @return MyParcelConsignmentRepository
     */
    private function getParentConsignment()
    {
        $consignmentTest = $this->additionProviderNewConsignment();

        $myParcelCollection = new MyParcelCollection();

        $consignment = (new MyParcelConsignmentRepository())
            ->setApiKey($consignmentTest['api_key'])
            ->setCountry($consignmentTest['cc'])
            ->setPerson($consignmentTest['person'])
            ->setCompany($consignmentTest['company'])
            ->setFullStreet($consignmentTest['full_street_test'])
            ->setPostalCode($consignmentTest['postal_code'])
            ->setCity($consignmentTest['city'])
            ->setEmail($consignmentTest['email'])
            ->setPhone($consignmentTest['phone']);

        $myParcelCollection->addConsignment($consignment)->createConcepts();

        $consignment = $myParcelCollection->getOneConsignment();

        return $consignment;
    }

    /**
     * Data for the test
     *
     * @return array
     */
    private function additionProviderNewConsignment()
    {
        return [
            'api_key' => getenv('API_KEY'),
            'cc' => 'NL',
            'person' => 'Piet',
            'email' => 'reindert@myparcel.nl',
            'company' => 'Mega Store',
            'full_street_test' => 'Koestraat 55',
            'number_suffix' => '',
            'postal_code' => '2231JE',
            'city' => 'Katwijk',
            'phone' => '123-45-235-435',
            'label_description' => 'Label description',

        ];
    }
}