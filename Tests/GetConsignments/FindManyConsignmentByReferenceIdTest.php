<?php

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

namespace MyParcelNL\Sdk\tests\GetConsignments\FindManyConsignmentByReferenceIdTest;

use MyParcelNL\Sdk\src\Helper\MyParcelCollection;
use MyParcelNL\Sdk\src\Model\MyParcelConsignment;

class FindManyConsignmentByReferenceIdTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @return void
     */
    public function testFindManyConsignmentByReferenceId(): void
    {
        $apiKey = getenv('API_KEY');
        if ($apiKey == null) {
            echo "\033[31m Set MyParcel API-key in 'Environment variables' before running UnitTest. Example: API_KEY=f8912fb260639db3b1ceaef2730a4b0643ff0c31. PhpStorm example: http://take.ms/sgpgU5\n\033[0m";

            return;
        }

        $referenceId = getenv('REFERENCE_ID');
        if ($referenceId == null) {
            echo "\033[31m Set reference_id in 'Environment variables' before running UnitTest. Example: REFERENCE_ID=[10952019-05-16,1077]. PhpStorm example: http://take.ms/sgpgU5\n\033[0m";

            return;
        }

        $collection = MyParcelCollection::findManyByReferenceId($referenceId, getenv('API_KEY'));
        $this->checkCollection($collection, $referenceId);
    }

    /**
     * @param \MyParcelNL\Sdk\src\Helper\MyParcelCollection|MyParcelConsignment[] $collection
     * @param int[] $referenceId
     */
    public function checkCollection(MyParcelCollection $collection, array $referenceId): void
    {
        $this->assertCount(count($referenceId), $collection);
        $this->assertNotEmpty($collection, 'The returned collection is not the same as the given REFERENCE_IDS');

        foreach ($collection as $consignment) {
            $this->assertInternalType('string', $consignment->getStreet());
        }
    }
}