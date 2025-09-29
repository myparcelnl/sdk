<?php

namespace MyParcelNL\Sdk\Services\CoreApi;

use GuzzleHttp\Client as GuzzleClient;
use MyParcel\CoreApi\Generated\Capabilities\Api\DefaultApi;
use MyParcel\CoreApi\Generated\Capabilities\Configuration;
use MyParcelNL\Sdk\Model\MyParcelRequest;

final class CapabilitiesClientFactory
{
    /**
     * Maakt een DefaultApi met juiste host en Authorization header
     * $baseUri kun je leeg laten; dan gebruiken we productie.
     */
    public static function make($baseUri = null)
    {

        $req = new MyParcelRequest();
        $req->setApiKey(getenv('API_KEY_NL') ?: getenv('API_KEY_BE') ?: '');
        $encoded = $req->getEncodedApiKey();

        // 2) Configureer de generated client
        $config = new Configuration();

        // Host bepalen — gebruik je eigen SDK default of expliciet meegegeven
        $host = $baseUri ?: 'https://api.myparcel.nl';
        $config->setHost($host);

        // OpenAPI generator zet apiKey-headers via setApiKey('HeaderName', 'value')
        $config->setApiKey('Authorization', 'Bearer ' . $encoded);

        // 3) Guzzle client meegeven
        $http = new GuzzleClient([
            'base_uri' => $host,
            'timeout'  => 10,
        ]);

        return new DefaultApi($http, $config);
    }
}
