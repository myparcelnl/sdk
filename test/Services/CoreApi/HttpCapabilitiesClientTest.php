<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\CoreApi;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Api\ShipmentApi;
use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesRequest;
use MyParcelNL\Sdk\Services\CoreApi\HttpCapabilitiesClient;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

final class HttpCapabilitiesClientTest extends TestCase
{
    /**
     * @var array<int, array<string, mixed>> The requests that the client sent.
     */
    private $history = [];

    /**
     * The request model goes out as the body, and the User-Agent as the `User-Agent` header.
     */
    public function testGetCapabilitiesSendsTheRequestAsTheBody(): void
    {
        $this->createClient('{"results":[]}')->getCapabilities(CapabilitiesRequest::forCountry('NL'));

        $request = $this->history[0]['request'];

        self::assertSame('POST', $request->getMethod());
        self::assertJsonStringEqualsJsonString('{"recipient":{"countryCode":"NL"}}', (string) $request->getBody());
        self::assertStringStartsWith('MyParcelSDK/', $request->getHeaderLine('User-Agent'));
    }

    /**
     * The options of a result are a generated model. The response gives their API names.
     */
    public function testGetCapabilitiesReturnsTheApiNamesOfTheOptions(): void
    {
        $response = $this->createClient(json_encode([
            'results' => [
                [
                    'carrier'      => 'POSTNL',
                    'packageTypes' => ['PACKAGE'],
                    'options'      => [
                        'requiresSignature'     => ['isRequired' => false, 'isSelectedByDefault' => false, 'requires' => [], 'excludes' => []],
                        'recipientOnlyDelivery' => ['isRequired' => false, 'isSelectedByDefault' => false, 'requires' => [], 'excludes' => []],
                    ],
                ],
            ],
        ]))->getCapabilities(CapabilitiesRequest::forCountry('NL'));

        self::assertSame(['recipientOnlyDelivery', 'requiresSignature'], $response->getShipmentOptions());
        self::assertSame(['PACKAGE'], $response->getPackageTypes());
    }

    private function createClient(string $body): HttpCapabilitiesClient
    {
        $stack = HandlerStack::create(new MockHandler([
            new Response(200, ['Content-Type' => 'application/json;version=2.0'], $body),
        ]));
        $stack->push(Middleware::history($this->history));

        return new HttpCapabilitiesClient(null, new ShipmentApi(new Client(['handler' => $stack])));
    }
}
