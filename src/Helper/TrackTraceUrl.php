<?php
/**
 * Created by PhpStorm.
 * User: richardperdaan
 * Date: 2019-03-19
 * Time: 13:13
 */

namespace MyparcelNL\Sdk\src\Helper;

use MyParcelNL\Sdk\src\Model\MyParcelConsignment;

class TrackTraceUrl
{

    const CONSUMER_PORTAL_BASE_URL = "https://myparcel.me/track-trace/";

    /**
     * @param string $barcode
     * @param string $postalCode
     * @param string $countryCode
     *
     * @return string
     */
    public static function create($barcode, $postalCode, $countryCode) {
        return self::CONSUMER_PORTAL_BASE_URL . "$barcode/$postalCode/$countryCode";
            // in documentatie aan passen en is slack chan
    }
}