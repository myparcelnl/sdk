<?php declare(strict_types=1);

/**
 * If you want to add improvements, please create a fork in our GitHub:
 * https://github.com/myparcelnl
 *
 * @author      Richard Perdaan <richard@myparcel.nl>
 * @copyright   2010-2020 MyParcel
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US  CC BY-NC-ND 3.0 NL
 * @link        https://github.com/myparcelnl/sdk
 * @since       File available since Release v0.1.0
 */

namespace MyParcelNL\Sdk\tests\GetConsignments\FindConsignmentTest;

use MyParcelNL\Sdk\src\Helper\MyParcelCollection;

class FindConsignmentTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @return void
     * @throws \Exception
     */
    public function testFindConsignment(): void
    {
        $apiKey = getenv('API_KEY');
        if ($apiKey == null) {
            echo "\033[31m Set MyParcel API-key in 'Environment variables' before running UnitTest. Example: API_KEY=f8912fb260639db3b1ceaef2730a4b0643ff0c31. PhpStorm example: http://take.ms/sgpgU5\n\033[0m";

            return;
        }

        $consignmentId = getenv('CONSIGNMENT_ID');
        if ($consignmentId == null) {
            echo "\033[31m Set consignment_id in 'Environment variables' before running UnitTest. Example: CONSIGNMENT_ID=1734535. PhpStorm example: http://take.ms/sgpgU5\n\033[0m";

            return;
        }

        $collection = MyParcelCollection::find((int) $consignmentId, getenv('API_KEY'));

        $this->assertCount(1, $collection);
        $this->assertInternalType('string', $collection->getOneConsignment()->getStreet());
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testFindConsignmentsByQueryArray(): void
    {
        $apiKey = getenv('API_KEY');
        if ($apiKey == null) {
            echo "\033[31m Set MyParcel API-key in 'Environment variables' before running UnitTest. Example: API_KEY=f8912fb260639db3b1ceaef2730a4b0643ff0c31. PhpStorm example: http://take.ms/sgpgU5\n\033[0m";

            return;
        }

        $collection = MyParcelCollection::query(
            $apiKey,
            [
                'size' => 1
            ]
        );

        $this->assertEquals(1, $collection->count());
    }
}
