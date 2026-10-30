<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Client\Jane;

use Jane\Component\OpenApiRuntime\Client\AuthenticationPlugin;
use Jane\Component\OpenApiRuntime\Client\Plugin\AuthenticationRegistry;
use Jane\Component\OpenApiRuntime\Client\Plugin\ServerUrlHttpClient;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Authentication\BearerAuthentication as CoreApiAuthentication;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Client as CoreApiClient;
use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Authentication\JwtAuthentication as IamApiAuthentication;
use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Client as IamApiClient;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Builds the generated Jane clients with the API key, the user agent and extra headers.
 *
 * @internal Use the API factories, such as ShipmentApiFactory.
 */
final class JaneClientFactory
{
    /**
     * The Core API host from the spec.
     */
    public const CORE_API_HOST = 'https://api.myparcel.nl';

    /**
     * The IAM API host from the spec.
     */
    public const IAM_API_HOST = 'https://iam.api.myparcel.nl';

    /**
     * Idle timeout in seconds of the HTTP client that this factory creates: a request stops when no
     * data arrives for this time.
     */
    public const DEFAULT_HTTP_TIMEOUT = 10;

    /**
     * Create a Core API client.
     *
     * @param string                   $apiKey     The API key as the Backoffice shows it. This method encodes it.
     * @param string|null              $host       Overrides the Core API host. An empty string uses the default.
     * @param string|null              $userAgent  Sent as the User-Agent on every request.
     * @param array<string, string>    $headers    Sent on every request, such as `x-dmp-*` API feature flags.
     * @param HttpClientInterface|null $httpClient Sends the requests, with its own options such as the timeout.
     */
    public static function coreApi(
        string $apiKey,
        ?string $host = null,
        ?string $userAgent = null,
        array $headers = [],
        ?HttpClientInterface $httpClient = null
    ): CoreApiClient {
        return CoreApiClient::create(
            httpClient: self::createHttpClient(
                $host ?: self::CORE_API_HOST,
                new CoreApiAuthentication(base64_encode($apiKey)),
                $userAgent,
                $headers,
                $httpClient
            ),
            applyServerPlugins: false
        );
    }

    /**
     * Create an IAM API client.
     *
     * IAM accepts the base64-encoded API key as the bearer token of its `jwt` scheme.
     *
     * @param string                   $apiKey     The API key as the Backoffice shows it. This method encodes it.
     * @param string|null              $host       Overrides the IAM API host. An empty string uses the default.
     * @param string|null              $userAgent  Sent as the User-Agent on every request.
     * @param array<string, string>    $headers    Sent on every request.
     * @param HttpClientInterface|null $httpClient Sends the requests, with its own options such as the timeout.
     */
    public static function iamApi(
        string $apiKey,
        ?string $host = null,
        ?string $userAgent = null,
        array $headers = [],
        ?HttpClientInterface $httpClient = null
    ): IamApiClient {
        return IamApiClient::create(
            httpClient: self::createHttpClient(
                $host ?: self::IAM_API_HOST,
                new IamApiAuthentication(base64_encode($apiKey)),
                $userAgent,
                $headers,
                $httpClient
            ),
            applyServerPlugins: false
        );
    }

    /**
     * Add the timeout, the headers, the host and the authentication to the HTTP client. The client
     * gets the host here, so `Client::create()` must not add the host of the spec.
     *
     * @param array<string, string> $headers
     */
    private static function createHttpClient(
        string $host,
        AuthenticationPlugin $authentication,
        ?string $userAgent,
        array $headers,
        ?HttpClientInterface $httpClient
    ): HttpClientInterface {
        if ($userAgent) {
            $headers['User-Agent'] = $userAgent;
        }

        // The timeout applies only to the client that this factory creates. A client that the caller
        // passes keeps its own options.
        $httpClient = ($httpClient ?? HttpClient::create(['timeout' => self::DEFAULT_HTTP_TIMEOUT]))
            ->withOptions(['headers' => $headers]);

        // The same order as the generated `Client::create()` with the `AuthenticationRegistry` in
        // `$additionalPlugins`. The registry adds the API key only to operations that the spec secures.
        $httpClient = (new ServerUrlHttpClient($host))($httpClient);

        return (new AuthenticationRegistry([$authentication]))($httpClient);
    }
}
