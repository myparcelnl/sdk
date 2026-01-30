<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\CoreApi;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use MyParcelNL\Sdk\CoreApi\Generated\Shipments\Api\ShipmentApi;
use MyParcelNL\Sdk\CoreApi\Generated\Shipments\Configuration;
use Psr\Http\Message\RequestInterface;

/**
 * Factory for creating the generated ShipmentApi client.
 *
 * Default behaviour:
 * - The SDK itself uses Capabilities API v2
 * - External consumers can disable or override the Accept header if needed
 */
final class CapabilitiesClientFactory
{

    public const DEFAULT_BASE_URI = 'https://api.myparcel.nl';

    public const CAPABILITIES_PATH = '/shipments/capabilities';

    public const ACCEPT_V2 = 'application/json;charset=utf-8;version=2.0';

    /**
     * Create a configured ShipmentApi client.
     *
     * @param string|null $apiKey Optional API key.
     * @param string|null $baseUri Optional base URI.
     * @param string|null $userAgent Optional custom User-Agent.
     * @param string|null $capabilitiesAcceptHeader Accept header for the capabilities endpoint;
     *                                              pass null to not force an Accept header.
     */
    public static function make(
        ?string $apiKey = null,
        ?string $baseUri = null,
        ?string $userAgent = null,
        ?string $capabilitiesAcceptHeader = self::ACCEPT_V2
    ): ShipmentApi {
        // Resolve API key from argument or environment variables
        $resolvedKey = self::resolveApiKey($apiKey);

        if ('' === $resolvedKey) {
            throw new \InvalidArgumentException('API key cannot be empty');
        }

        // Encode API key as required by the generated client
        $encoded = base64_encode($resolvedKey);

        // Determine API host
        $host = $baseUri ?: self::DEFAULT_BASE_URI;

        // Configure the generated client
        $config = new Configuration();
        $config->setHost($host);
        $config->setAccessToken($encoded);

        if ($userAgent) {
            $config->setUserAgent($userAgent);
        }

        // Build a Guzzle handler stack
        $stack = HandlerStack::create();

        /**
         * Optionally force the Accept header for the capabilities endpoint.
         *
         * This ensures:
         * - The SDK itself always talks to Capabilities v2
         * - Other endpoints remain untouched
         * - External consumers can opt out by passing null
         */
        if (null !== $capabilitiesAcceptHeader) {
            $stack->push(Middleware::mapRequest(
                static function (RequestInterface $request) use ($capabilitiesAcceptHeader): RequestInterface {
                    $path = (string) $request->getUri()->getPath();

                    if (false !== strpos($path, self::CAPABILITIES_PATH)) {
                        return $request->withHeader('Accept', $capabilitiesAcceptHeader);
                    }

                    return $request;
                }
            ));
        }

        // Create the HTTP client
        $http = new GuzzleClient([
            'base_uri' => $host,
            'timeout'  => 10,
            'handler'  => $stack,
            'debug'    => false,
        ]);

        // Return the generated API client
        return new ShipmentApi($http, $config);
    }

    /**
     * Resolve the API key from arguments or environment variables.
     *
     * Resolution order:
     * 1. Explicit argument
     * 2. Unified API_KEY
     * 3. Legacy API_KEY_NL / API_KEY_BE (backward compatibility)
     */
    private static function resolveApiKey(?string $apiKey): string
    {
        if (null !== $apiKey && '' !== $apiKey) {
            return $apiKey;
        }

        $unifiedKey = getenv('API_KEY');
        if ($unifiedKey && '' !== $unifiedKey) {
            return $unifiedKey;
        }

        $legacyKey = getenv('API_KEY_NL') ?: getenv('API_KEY_BE');
        if ($legacyKey && '' !== $legacyKey) {
            return $legacyKey;
        }

        return '';
    }
}
