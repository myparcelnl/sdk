<?php declare(strict_types=1);

/**
 * If you want to add improvements, please create a fork in our GitHub:
 * https://github.com/myparcelnl
 *
 * @author      Richard Perdaan <richard@myparcel.nl>
 * @copyright   2010-2017 MyParcel
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US  CC BY-NC-ND 3.0 NL
 * @link        https://github.com/myparcelnl/sdk
 * @since       File available since Release v0.1.0
 */

namespace MyParcelNL\Sdk\tests\GetConsignments\FindManyConsignmentTest;

use MyParcelNL\Sdk\src\Helper\MyParcelCollection;
use MyParcelNL\Sdk\src\Model\MyParcelConsignment;

class FindManyConsignmentTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @return void
     * @throws \Exception
     */
    public function testFindManyConsignment(): void
    {
        $apiKey = getenv('API_KEY');
        if ($apiKey == null) {
            echo "\033[31m Set MyParcel API-key in 'Environment variables' before running UnitTest. Example: API_KEY=f8912fb260639db3b1ceaef2730a4b0643ff0c31. PhpStorm example: http://take.ms/sgpgU5\n\033[0m";

            return;
        }

        $consignmentIdsRaw = getenv('CONSIGNMENT_IDS');
        if ($consignmentIdsRaw == null) {
            echo "\033[31m Set consignment_id in 'Environment variables' before running UnitTest. Example: CONSIGNMENT_IDS=47964049,47964050,47964051. PhpStorm example: http://take.ms/sgpgU5\n\033[0m";

            return;
        }
        $consignmentIds = explode(",", $consignmentIdsRaw);

        $collection = MyParcelCollection::findMany($consignmentIds, getenv('API_KEY'));
        $this->checkCollection($collection, $consignmentIds);

    }

    /**
     * @param \MyParcelNL\Sdk\src\Helper\MyParcelCollection|MyParcelConsignment[] $collection
     * @param int[] $consignmentIds
     */
    public function checkCollection(MyParcelCollection $collection, array $consignmentIds): void
    {
        $this->assertCount(count($consignmentIds), $collection);
        $this->assertNotEmpty($collection, 'The returned collection is not the same as the given CONSIGNMENT_IDS');

        foreach ($collection as $consignment) {
            $this->assertInternalType('string', $consignment->getStreet());
        }
    }
}