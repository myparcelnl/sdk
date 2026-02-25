<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\CoreApi;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Utils;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Api\ShipmentApi;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Factory for creating the generated ShipmentApi client.
 */
final class ShipmentApiFactory
{
    /**
     * Create a configured ShipmentApi client.
     *
     * @param string|null $apiKey Optional API key.
     * @param string|null $host Optional API host override.
     * @param string|null $userAgent Optional custom User-Agent.
     */
    public static function make(
        ?string $apiKey = null,
        ?string $host = null,
        ?string $userAgent = null
    ): ShipmentApi {
        $resolvedKey = self::resolveApiKey($apiKey);

        if ('' === $resolvedKey) {
            throw new \InvalidArgumentException('API key cannot be empty');
        }

        $encoded = base64_encode($resolvedKey);

        $config = new Configuration();
        $config->setAccessToken($encoded);

        if ($host) {
            $config->setHost($host);
        }

        if ($userAgent) {
            $config->setUserAgent($userAgent);
        }

        $stack = self::createHandlerStack();

        $httpOptions = [
            'timeout'  => 10,
            'handler'  => $stack,
            'debug'    => false,
        ];

        if ($host) {
            $httpOptions['base_uri'] = $host;
        }

        $http = new GuzzleClient($httpOptions);

        return new ShipmentApi($http, $config);
    }

    private static function createHandlerStack(): HandlerStack
    {
        $stack = HandlerStack::create();

        $stack->push(self::normalizeShipmentRequestMiddleware(), 'normalize_shipment_enums');
        $stack->push(self::normalizeTrackTraceResponseMiddleware(), 'normalize_tracktrace_response');

        return $stack;
    }

    private static function normalizeShipmentRequestMiddleware(): callable
    {
        return Middleware::mapRequest(static function (RequestInterface $request): RequestInterface {
            $contentType = $request->getHeaderLine('Content-Type');

            if (false === strpos($contentType, 'shipment+json')) {
                return $request;
            }

            $body = (string) $request->getBody();

            if ('' === $body) {
                return $request;
            }

            $decoded = json_decode($body, true);

            if (! is_array($decoded)) {
                return $request;
            }

            $normalized = self::normalizeShipmentRequestPayload($decoded);
            if ($normalized === $decoded) {
                return $request;
            }

            return $request->withBody(Utils::streamFor(json_encode($normalized)));
        });
    }

    private static function normalizeTrackTraceResponseMiddleware(): callable
    {
        return Middleware::mapResponse(static function (ResponseInterface $response): ResponseInterface {
            $body = (string) $response->getBody();

            if ('' === $body) {
                return $response;
            }

            $decoded = json_decode($body, true);

            if (! is_array($decoded)) {
                return $response;
            }

            $normalized = self::normalizeTrackTraceResponsePayload($decoded);
            if ($normalized === $decoded) {
                return $response;
            }

            return $response->withBody(Utils::streamFor(json_encode($normalized)));
        });
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

    /**
     * @todo remove after CoreAPI spec/codegen fix for numeric enum serialization.
     *
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    private static function normalizeShipmentRequestPayload(array $payload): array
    {
        if (! isset($payload['data']['shipments']) || ! is_array($payload['data']['shipments'])) {
            return $payload;
        }

        foreach ($payload['data']['shipments'] as &$shipment) {
            if (! is_array($shipment)) {
                continue;
            }

            if (isset($shipment['carrier']) && is_string($shipment['carrier']) && is_numeric($shipment['carrier'])) {
                $shipment['carrier'] = (int) $shipment['carrier'];
            }

            if (isset($shipment['options']['package_type']) && is_string($shipment['options']['package_type']) && is_numeric($shipment['options']['package_type'])) {
                $shipment['options']['package_type'] = (int) $shipment['options']['package_type'];
            }
        }
        unset($shipment);

        return $payload;
    }

    /**
     * @todo remove after CoreAPI spec/codegen fix for TrackTrace deserialization.
     *
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    private static function normalizeTrackTraceResponsePayload(array $payload): array
    {
        if (! isset($payload['data']['tracktraces']) || ! is_array($payload['data']['tracktraces'])) {
            return $payload;
        }

        $allowedTrackTraceKeys = [
            'shipment_id',
            'carrier',
            'code',
            'description',
            'time',
            'link_consumer_portal',
            'link_tracktrace',
            'delayed',
            'returnable',
        ];

        foreach ($payload['data']['tracktraces'] as &$trackTrace) {
            if (! is_array($trackTrace)) {
                continue;
            }

            $trackTrace = array_intersect_key($trackTrace, array_flip($allowedTrackTraceKeys));

            if (isset($trackTrace['carrier']) && is_int($trackTrace['carrier'])) {
                $trackTrace['carrier'] = (string) $trackTrace['carrier'];
            }
        }
        unset($trackTrace);

        return $payload;
    }
}
