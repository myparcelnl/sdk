<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Connect;

use MyParcelNL\Sdk\Client\Generated\EcommerceApi\Model\ConnectStartConfig;

/**
 * The e-commerce platforms this SDK can connect.
 *
 * Pass one of these to ConnectConfig. It picks both the e-commerce service the SDK calls and the
 * platform MyParcel records for the shop, so there is one value to get right instead of two.
 *
 * The values come from the generated ConnectStartConfig, which is the published list. MyParcel also
 * runs CSCART and LIGHTSPEED services. They are missing here until a PHP plugin targets one, so a
 * platform without a service fails in the constructor instead of at the first call.
 */
class ConnectPlatform
{
    public const GENERIC = ConnectStartConfig::PLATFORM_GENERIC;

    public const MAGENTO = ConnectStartConfig::PLATFORM_MAGENTO;

    /**
     * PrestaShop.
     */
    public const PRESTA = ConnectStartConfig::PLATFORM_PRESTA;

    public const SHOPIFY = ConnectStartConfig::PLATFORM_SHOPIFY;

    /**
     * WooCommerce.
     */
    public const WOOCOMMERCE = ConnectStartConfig::PLATFORM_WOOCOMMERCE;

    /**
     * Platform to the first label of the e-commerce host: SHOPIFY gives
     * https://shopify.ecommerce.api.myparcel.nl.
     *
     * A fixed list, because the label is not the lowercased platform name. WOOCOMMERCE is 'woo'.
     */
    private const SERVICE_PREFIXES = [
        // self::GENERIC     => 'generic',
        // self::MAGENTO     => 'magento',
        // self::PRESTA      => 'presta',
        self::SHOPIFY     => 'shopify',
        // self::WOOCOMMERCE => 'woo',
    ];

    /**
     * @return string[]
     */
    public static function getAllowableEnumValues(): array
    {
        return array_keys(self::SERVICE_PREFIXES);
    }

    /**
     * The host label for a platform, or null when this SDK has no service for it.
     */
    public static function toServicePrefix(string $platform): ?string
    {
        return self::SERVICE_PREFIXES[$platform] ?? null;
    }
}
