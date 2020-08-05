<?php declare(strict_types=1);

/**
 * If you want to add improvements, please create a fork in our GitHub:
 * https://github.com/myparcelnl
 *
 * @author      Richard Perdaan <richard@myparcel.nl>
 * @copyright   2010-2020 MyParcel
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US  CC BY-NC-ND 3.0 NL
 * @link        https://github.com/myparcelnl/sdk
 * @since       File available since Release v3.0.0
 */

namespace MyParcelNL\Sdk\tests\GetConsignments\FindConsignmentByReferenceIdTest;

use MyParcelNL\Sdk\src\Helper\MyParcelCollection;

class FindConsignmentByReferenceIdTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @return void
     * @throws \Exception
     */
    public function testFindConsignmentByReferenceId(): void
    {
        $apiKey = getenv('API_KEY');
        if ($apiKey == null) {
            echo "\033[31m Set MyParcel API-key in 'Environment variables' before running UnitTest. Example: API_KEY=f8912fb260639db3b1ceaef2730a4b0643ff0c31. PhpStorm example: http://take.ms/sgpgU5\n\033[0m";

            return;
        }

        $referenceId = getenv('REFERENCE_ID');
        if ($referenceId == null) {
            echo "\033[31m Set reference_id in 'Environment variables' before running UnitTest. Example: REFERENCE_ID=order 12. PhpStorm example: http://take.ms/sgpgU5\n\033[0m";

            return;
        }

        $collection = MyParcelCollection::findByReferenceId($referenceId, getenv('API_KEY'));

        $this->assertCount(1, $collection);
        $this->assertInternalType('string', $collection->getOneConsignment()->getStreet());
    }
}
