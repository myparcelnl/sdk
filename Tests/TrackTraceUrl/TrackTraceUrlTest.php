<?php

/**
 * For Dutch consignments the street should be divided into name, number and addition. This code tests whether the
 * street is split properly.
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

namespace MyParcelNL\Sdk\src\tests\CreateConsignments\TrackTraceUrlTest;

use MyParcelNL\Sdk\src\Helper\TrackTraceUrl;

/**
  * Class SplitStreetTest
 * @package MyParcelNL\Sdk\src\tests\TrackTraceUrlTest
 */
class TrackTraceUrlTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @covers       \MyParcelNL\Sdk\src\Model\Repository\MyParcelConsignmentRepository::TrackTraceUrl
     * @dataProvider additionProvider()
     *
     * @param string $barcode
     * @param string $countryCode
     * @param string $postalCode
     */
    public function testTrackTrace($barcode, $postalCode, $countryCode)
    {
        $trackTrace = (new TrackTraceUrl())
            ->create($barcode, $postalCode, $countryCode);

        $this->assertSame("https://myparcel.me/track-trace/$barcode/$postalCode/$countryCode", $trackTrace, 'The track-trace url is not the same as the result.');
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
                'barcode'     => '1234567890',
                'postal_code' => '2131BC',
                'cc'          => 'NL'
            ]
        ];
    }
}