<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Client;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\EcommerceApi\Model\OrderShipping;
use MyParcelNL\Sdk\Client\Generated\EcommerceApi\ObjectSerializer;
use MyParcelNL\Sdk\Support\EnumFallback;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

/**
 * Locks the asymmetric enum behavior of the openapi-generator templates: the response (read) path
 * passes unknown enum values through untouched, while the request (write) path stays strict.
 *
 * @todo INT-2034: remove this test with the openapi-generator e-commerce client.
 */
class UnknownEnumPassthroughTest extends TestCase
{
    private const SHIPPING = OrderShipping::class;

    protected function tearDown(): void
    {
        EnumFallback::setListener(null);
        parent::tearDown();
    }

    public function testUnknownCarrierPassesThroughInsteadOfThrowing(): void
    {
        $shipping = ObjectSerializer::deserialize('{"carrier":"FOOCARRIER"}', self::SHIPPING);

        self::assertSame('FOOCARRIER', $shipping->getCarrier());
    }

    public function testKnownEnumValueStillDeserializes(): void
    {
        $shipping = ObjectSerializer::deserialize('{"carrier":"POSTNL"}', self::SHIPPING);

        self::assertSame('POSTNL', $shipping->getCarrier());
    }

    public function testListenerObservesTheUnknownValue(): void
    {
        $seen = [];
        EnumFallback::setListener(static function (string $enumClass, $value) use (&$seen): void {
            $seen[] = $value;
        });

        ObjectSerializer::deserialize('{"carrier":"FOOCARRIER"}', self::SHIPPING);

        self::assertContains('FOOCARRIER', $seen);
    }

    /**
     * Serialization applies to sending a request to the API.
     * Here we can safely validate against the spec and should prevent developers making errors.
     * @return void
     */
    public function testRequestSerializationStillRejectsUnknownEnum(): void
    {
        $shipping = new OrderShipping();
        $shipping->setCarrier('FOOCARRIER');

        $this->expectException(InvalidArgumentException::class);
        ObjectSerializer::sanitizeForSerialization($shipping);
    }
}
