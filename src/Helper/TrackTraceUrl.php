<?php declare(strict_types=1);

namespace MyparcelNL\Sdk\src\Helper;

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
    public static function create(string $barcode, string $postalCode, string $countryCode): string
    {
        return self::CONSUMER_PORTAL_BASE_URL . "$barcode/$postalCode/$countryCode";
    }
}