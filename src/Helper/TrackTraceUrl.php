<?php declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Helper;

class TrackTraceUrl
{
    const CONSUMER_PORTAL_BASE_URL = "https://myparcel.me/track-trace/";

    /**
     * Creates a Track & Trace URL to myparcel.me from given barcode, postalCode and optional countryCode. Trims
     * spaces from postal code and only adds country code to the url if it's added.
     *
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
        $postalCode = str_replace(' ', '', $postalCode);

        $url = self::CONSUMER_PORTAL_BASE_URL . "$barcode/$postalCode";

        if ($countryCode) {
            $url .= "/$countryCode";
        }

        return $url;
    }
}
