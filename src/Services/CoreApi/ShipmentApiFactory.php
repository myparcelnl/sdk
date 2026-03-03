<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\CoreApi;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Utils;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Api\ShipmentApi;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration;
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

        $stack->push(self::normalizeTrackTraceResponseMiddleware(), 'normalize_tracktrace_response');

        return $stack;
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

            if (isset($trackTrace['carrier'])) {
                $carrier = $trackTrace['carrier'];

                if (is_string($carrier) && ctype_digit($carrier)) {
                    $trackTrace['carrier'] = (int) $carrier;
                }
            }
        }
        unset($trackTrace);

        return $payload;
    }
}
