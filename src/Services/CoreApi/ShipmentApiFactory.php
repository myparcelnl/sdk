<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\CoreApi;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Utils;
use MyParcelNL\Sdk\CoreApi\Generated\Shipments\Api\ShipmentApi;
use MyParcelNL\Sdk\CoreApi\Generated\Shipments\Configuration;
use Psr\Http\Message\RequestInterface;

/**
 * Factory for creating the generated ShipmentApi client.
 */
final class ShipmentApiFactory
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
        $resolvedKey = self::resolveApiKey($apiKey);

        if ('' === $resolvedKey) {
            throw new \InvalidArgumentException('API key cannot be empty');
        }

        $encoded = base64_encode($resolvedKey);
        $host = $baseUri ?: self::DEFAULT_BASE_URI;

        $config = new Configuration();
        $config->setHost($host);
        $config->setAccessToken($encoded);

        if ($userAgent) {
            $config->setUserAgent($userAgent);
        }

        $stack = HandlerStack::create();

        // TEMP WORKAROUND (remove after CoreAPI spec/codegen fix):
        // The generated OpenAPI enums (RefTypesCarrier, RefShipmentPackageType)
        // serialize carrier/package_type as strings (e.g. "1") while the API
        // validates these fields as integers for POST /shipments.
        //
        // This middleware normalizes only the known shipment enum fields in the
        // outgoing JSON body so we can keep using the generated ShipmentApi client.
        //
        // Removal criteria:
        // - generated PHP client serializes carrier/package_type as numeric values
        // - live smoke for ShipmentCollection::createConcepts() passes without casts
        $stack->push(Middleware::mapRequest(
            static function (RequestInterface $request): RequestInterface {
                $contentType = $request->getHeaderLine('Content-Type');

                if (false === strpos($contentType, 'shipment+json')) {
                    return $request;
                }

                $body = (string) $request->getBody();

                if ('' === $body) {
                    return $request;
                }

                $decoded = json_decode($body, true);

                if (! is_array($decoded) || ! isset($decoded['data']['shipments'])) {
                    return $request;
                }

                $changed = false;

                foreach ($decoded['data']['shipments'] as &$shipment) {
                    if (isset($shipment['carrier']) && is_string($shipment['carrier']) && is_numeric($shipment['carrier'])) {
                        $shipment['carrier'] = (int) $shipment['carrier'];
                        $changed = true;
                    }

                    if (isset($shipment['options']['package_type']) && is_string($shipment['options']['package_type']) && is_numeric($shipment['options']['package_type'])) {
                        $shipment['options']['package_type'] = (int) $shipment['options']['package_type'];
                        $changed = true;
                    }
                }
                unset($shipment);

                if (! $changed) {
                    return $request;
                }

                return $request->withBody(Utils::streamFor(json_encode($decoded)));
            }
        ), 'normalize_shipment_enums');

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

        $http = new GuzzleClient([
            'base_uri' => $host,
            'timeout'  => 10,
            'handler'  => $stack,
            'debug'    => false,
        ]);

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
