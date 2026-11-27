<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model;

class ConnectStartConfig
{
    /**
     * Defaults to the platform of this deployment.
     *
     * @var ConnectStartConfigPlatform|string
     */
    public ConnectStartConfigPlatform|string $platform;
    /**
     * Display name of the shop.
     *
     * @var string
     */
    public string $shopName;
    /**
     * HTTPS base URL of the shop. The browser returns to <shopUrl>/myparcel-connect/callback.
     *
     * @var string
     */
    public string $shopUrl;
    /**
     * Semver version of the plugin. Defaults to 0.0.1.
     *
     * @var string
     */
    public string $version = '0.0.1';
    /**
     * Optional absolute HTTPS URL that receives every MyParcel command for this connection. A reconnect replaces it; without it the plugin gets no commands. Must be canonical (lowercase host, no default port, path starts with /, no user name or password); other forms are rejected.
     *
     * @var string
     */
    public string $commandEndpoint;
}