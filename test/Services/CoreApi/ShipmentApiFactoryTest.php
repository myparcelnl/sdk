<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\CoreApi;

use GuzzleHttp\ClientInterface;
use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Api\ShipmentApi;
use MyParcelNL\Sdk\Services\CoreApi\ShipmentApiFactory;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use ReflectionProperty;

class ShipmentApiFactoryTest extends TestCase
{
    public function testMakeReturnsShipmentApi(): void
    {
        $this->assertInstanceOf(ShipmentApi::class, ShipmentApiFactory::make('test-key'));
    }

    public function testMakeThrowsWhenApiKeyIsEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);

        ShipmentApiFactory::make('');
    }

    public function testMakeSendsTheGivenHeadersOnEveryRequest(): void
    {
        $api = ShipmentApiFactory::make('test-key', null, null, ['x-dmp-no-tracking' => 'true']);

        $this->assertSame('true', $this->httpClientOf($api)->getConfig('headers')['x-dmp-no-tracking'] ?? null);
    }

    public function testMakeWithoutHeadersKeepsGuzzleDefaults(): void
    {
        $headers = $this->httpClientOf(ShipmentApiFactory::make('test-key'))->getConfig('headers');

        $this->assertArrayNotHasKey('x-dmp-no-tracking', $headers);
        $this->assertArrayHasKey('User-Agent', $headers);
    }

    private function httpClientOf(ShipmentApi $api): ClientInterface
    {
        $property = new ReflectionProperty(ShipmentApi::class, 'client');
        $property->setAccessible(true);

        return $property->getValue($api);
    }
}
