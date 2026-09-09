<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Ecommerce;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\HandlerStack;
use MyParcelNL\Sdk\Client\Generated\EcommerceApi\Api\DefaultApi;
use MyParcelNL\Sdk\Client\Generated\EcommerceApi\Configuration;
use MyParcelNL\Sdk\Services\Connect\ConnectService;
use MyParcelNL\Sdk\Services\Connect\DpopMiddleware;

/**
 * Builds the generated e-commerce API client for a connected shop.
 *
 * Use this rather than constructing DefaultApi yourself. The generated client offers no way to put
 * DpopMiddleware on the Guzzle client it sends with, and without that middleware every call goes
 * out unsigned and comes back 401.
 *
 * Every call it makes carries the shop's access token and a proof signed with the shop's key. There
 * is no API key version: the only secured operation in the spec, POST /webhook/orders, accepts
 * nothing but DPoP.
 *
 *     $connect = new ConnectService(
 *         new ConnectConfig(ConnectPlatform::WOOCOMMERCE, $encryptionKey),
 *         new YourConnectStorage()
 *     );
 *
 *     // Before the client is built, so its calls carry your plugin's name.
 *     $connect->setUserAgentForProposition('MyParcelNL-WooCommerce', '5.1.0');
 *
 *     $api = EcommerceApiFactory::make($connect);
 *
 *     // Throws ConnectException when the shop is not connected, or when MyParcel refuses the call.
 *     $api->webhookOrdersPost([$order]);
 */
final class EcommerceApiFactory
{
    /**
     * Default HTTP client timeout in seconds.
     *
     * Shared with generated-client based services to ensure consistent behaviour.
     */
    public const DEFAULT_HTTP_TIMEOUT = 10;

    /**
     * Create a client that signs every call with the connected shop's key.
     *
     * Register your plugin with ConnectService::setUserAgentForProposition() before calling this:
     * the user agent is fixed when the client is built.
     *
     * @param ConnectService $connect   The connected shop. Its ConnectConfig supplies the host.
     * @param string|null    $host      Overrides that host for resource calls only, so a tunnel or
     *                                  a local mock stays possible. The connect calls keep going to
     *                                  the host ConnectConfig builds.
     * @param string|null    $userAgent Overrides the SDK's own user agent.
     */
    public static function make(
        ConnectService $connect,
        ?string $host = null,
        ?string $userAgent = null
    ): DefaultApi {
        // Both arguments read an empty string as not provided, as the other API factories do: a
        // consumer's unset setting arrives as '' rather than null. An empty host would resolve
        // every URL against nothing, and an empty user agent would send no User-Agent at all,
        // because the generated client only writes the header when it has a value.
        //
        // The spec carries no servers entry, so the generated default is http://localhost. The host
        // is always set here.
        $resolvedHost = '' !== (string) $host ? $host : $connect->getConfig()->getHost();

        $config = new Configuration();
        $config->setHost($resolvedHost);
        $config->setUserAgent('' !== (string) $userAgent ? $userAgent : $connect->getUserAgentHeader());

        return new DefaultApi(
            new GuzzleClient([
                'timeout'  => self::DEFAULT_HTTP_TIMEOUT,
                'handler'  => self::createHandlerStack($connect),
                'base_uri' => $resolvedHost,
                'debug'    => false,
            ]),
            $config
        );
    }

    private static function createHandlerStack(ConnectService $connect): HandlerStack
    {
        $stack = HandlerStack::create();

        // Pushed after create(), which makes it the innermost middleware. It therefore sees a raw
        // 401 with its headers, before http_errors turns one into an exception.
        $stack->push(new DpopMiddleware($connect));

        return $stack;
    }
}
