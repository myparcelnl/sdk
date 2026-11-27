<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Ecommerce;

use Jane\Component\OpenApiRuntime\Client\Plugin\AuthenticationRegistry;
use Jane\Component\OpenApiRuntime\Client\Plugin\ServerUrlHttpClient;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Client;
use MyParcelNL\Sdk\Services\Connect\ConnectService;
use MyParcelNL\Sdk\Services\Connect\DpopAuthentication;
use MyParcelNL\Sdk\Services\Connect\TokenRefreshHttpClient;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Builds the generated e-commerce API client for a connected shop.
 *
 * Use this and do not build the generated client yourself. A client that you build sends its
 * calls without a signature, and MyParcel answers 401.
 *
 * Every call to an operation that the spec secures carries the access token of the shop and a
 * proof signed with the key of the shop. There is no API key version: the order push and the
 * product push accept nothing but DPoP.
 *
 *     $connect = new ConnectService(
 *         new ConnectConfig(ConnectPlatform::WOOCOMMERCE, $encryptionKey),
 *         new YourConnectStorage()
 *     );
 *
 *     // Before the client is built, so its calls carry the name of your plugin.
 *     $connect->setUserAgentForProposition('MyParcelNL-WooCommerce', '5.1.0');
 *
 *     $api = EcommerceApiFactory::make($connect);
 *
 *     // Throws `ConnectException` when the shop is not connected, and a generated exception such as
 *     // `PostWebhookOrderBadRequestException` when MyParcel refuses the whole call.
 *     $results = (new PushResultReader())->read($api->postWebhookOrder(requestBody: [$order]));
 */
final class EcommerceApiFactory
{
    /**
     * Idle timeout in seconds of the HTTP client that this factory creates: a request stops when no
     * data arrives for this time.
     */
    public const DEFAULT_HTTP_TIMEOUT = 10;

    /**
     * Create a client that signs every secured call with the key of the connected shop.
     *
     * Register your plugin with `ConnectService::setUserAgentForProposition()` before you call this:
     * the user agent is fixed when the client is built.
     *
     * @param ConnectService           $connect    The connected shop. Its `ConnectConfig` supplies the host.
     * @param string|null              $host       Overrides that host for resource calls only, so a tunnel or
     *                                             a local mock stays possible. The connect calls keep going to
     *                                             the host that `ConnectConfig` builds.
     * @param string|null              $userAgent  Overrides the user agent of the SDK.
     * @param HttpClientInterface|null $httpClient Sends the requests, with its own options such as a timeout or a proxy.
     */
    public static function make(
        ConnectService $connect,
        ?string $host = null,
        ?string $userAgent = null,
        ?HttpClientInterface $httpClient = null
    ): Client {
        // Both arguments read an empty string as not provided, as the other API factories do: an
        // unset setting of a consumer arrives as '' and not as null. The spec has no servers entry,
        // so the host is always set here.
        $resolvedHost      = '' !== (string) $host ? $host : $connect->getConfig()->getHost();
        $resolvedUserAgent = '' !== (string) $userAgent ? $userAgent : $connect->getUserAgentHeader();

        // The timeout applies only to the client that this factory creates. A client that the caller
        // passes keeps its own options.
        $httpClient = ($httpClient ?? HttpClient::create(['timeout' => self::DEFAULT_HTTP_TIMEOUT]))
            ->withOptions([
                'headers'       => ['User-Agent' => $resolvedUserAgent],
                // A redirect would send the order body to the URL in the Location header. A 3xx
                // answer therefore comes back as it is, and the generated client throws for it.
                'max_redirects' => 0,
            ]);

        // From the inside to the outside. The order matters:
        // 1. `AuthenticationRegistry` signs a request. It needs the full URL, so it is inside
        //    `ServerUrlHttpClient`.
        // 2. `TokenRefreshHttpClient` is outside `AuthenticationRegistry`, so a second attempt is
        //    signed again.
        // 3. `ServerUrlHttpClient` puts the host in front of the path of the generated endpoint.
        $httpClient = (new AuthenticationRegistry([new DpopAuthentication($connect)]))($httpClient);
        $httpClient = new TokenRefreshHttpClient($httpClient, $connect);
        $httpClient = (new ServerUrlHttpClient($resolvedHost))($httpClient);

        return Client::create(httpClient: $httpClient);
    }
}
