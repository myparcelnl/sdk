<?php declare(strict_types=1);
/**
 * For Dutch consignments the street should be divided into name, number and addition. For shipments to other
 * the address countries should be on one line. For this it is required first fill out a country. This code tests
 * whether the street has remained the same after the request.
 *
 * If you want to add improvements, please create a fork in our GitHub:
 * https://github.com/myparcelnl
 *
 * @author      Reindert Vetter <reindert@myparcel.nl>
 * @copyright   2010-2020 MyParcel
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US  CC BY-NC-ND 3.0 NL
 * @link        https://github.com/myparcelnl/sdk
 * @since       File available since Release v0.1.0
 */

namespace MyParcelNL\Sdk\src\tests\CreateConsignments\InternationalFullStreetTest;

use MyParcelNL\Sdk\src\Factory\ConsignmentFactory;
use MyParcelNL\Sdk\src\Model\Consignment\PostNLConsignment;


/**
 * Class InternationalFullStreetTest
 */
class InternationalFullStreetTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @dataProvider additionProvider()
     *
     * @param $carrierId
     * @param $cc
     * @param $fullStreet
     *
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    public function testSplitStreet($carrierId, $cc, $fullStreet)
    {
        $consignment = (ConsignmentFactory::createByCarrierId($carrierId))
            ->setCountry($cc)
            ->setFullStreet($fullStreet);

        $this->assertEquals($fullStreet, $consignment->getFullStreet(), 'Full street: ' . $fullStreet);
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
                'carrier_id'  => PostNLConsignment::CARRIER_ID,
                'cc'          => 'FR',
                'full_street' => 'No. 7 street',
            ],
        ];
    }
}
