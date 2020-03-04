<?php declare(strict_types=1);

namespace MyparcelNL\Sdk\src\Helper;

class TrackTraceUrl
{
    const CONSUMER_PORTAL_BASE_URL = "https://myparcel.me/track-trace/";

    /**
     * @param string      $barcode
     * @param string      $postalCode
     * @param string|null $countryCode
     *
     * @return string
     */
    public static function create(
        string $barcode,
        string $postalCode,
        ?string $countryCode = null
    ): string {
        $url = self::CONSUMER_PORTAL_BASE_URL . "$barcode/$postalCode";

        if ($countryCode) {
            $url .= "/$countryCode";
        }

        return $url;
    }
}
