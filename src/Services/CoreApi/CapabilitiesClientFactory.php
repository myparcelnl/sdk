<?php

namespace MyParcelNL\Sdk\Services\CoreApi;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use MyParcel\CoreApi\Generated\Capabilities\Api\ShipmentApi;
use MyParcel\CoreApi\Generated\Capabilities\Configuration;
use MyParcelNL\Sdk\Model\MyParcelRequest;

/**
 * Factory to create the generated ShipmentApi client with proper configuration.
 *
 * - API key can be passed explicitly or falls back to ENV vars.
 * - Accept header is forced to v2 for the capabilities endpoint.
 */
final class CapabilitiesClientFactory
{
    public const DEFAULT_BASE_URI  = 'https://api.myparcel.nl';
    public const CAPABILITIES_PATH = '/shipments/capabilities';
    public const ACCEPT_V2         = 'application/json;charset=utf-8;version=2.0';

    public static function make($apiKey = null, $baseUri = null)
    {
        // 1) Resolve API key: param > ENV > empty string
        if (null === $apiKey || '' === $apiKey) {
            $apiKey = getenv('API_KEY_NL') ?: getenv('API_KEY_BE') ?: '';
        }

        // 2) Encode API key via MyParcelRequest
        $req = new MyParcelRequest();
        $req->setApiKey($apiKey);
        $encoded = $req->getEncodedApiKey();

        $host = $baseUri ?: self::DEFAULT_BASE_URI;

        // 3) Configure generated client
        $config = new Configuration();
        $config->setHost($host);
        $config->setAccessToken($encoded);

        // 4) Add middleware to force Accept header for capabilities endpoint
        $stack = HandlerStack::create();
        $stack->push(Middleware::mapRequest(function (\Psr\Http\Message\RequestInterface $request) {
            $path = (string) $request->getUri()->getPath();
            if (false !== strpos($path, self::CAPABILITIES_PATH)) {
                return $request->withHeader('Accept', self::ACCEPT_V2);
            }
            return $request;
        }));

        $http = new GuzzleClient([
            'base_uri' => $host,
            'timeout'  => 10,
            'handler'  => $stack,
            'debug'    => false,
        ]);

        return new ShipmentApi($http, $config);
    }
}
