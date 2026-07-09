<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Client;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefCapabilitiesResponseCapabilityV2;
use MyParcelNL\Sdk\Client\Generated\CoreApi\ObjectSerializer;
use MyParcelNL\Sdk\Support\EnumFallback;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

/**
 * Locks the asymmetric enum behavior: the response (read) path passes unknown
 * enum values through untouched, while the request (write) path stays strict.
 */
class UnknownEnumPassthroughTest extends TestCase
{
    private const CAPABILITY = RefCapabilitiesResponseCapabilityV2::class;

    protected function tearDown(): void
    {
        EnumFallback::setListener(null);
        parent::tearDown();
    }

    public function testUnknownCarrierOnRequiredFieldPassesThroughInsteadOfThrowing(): void
    {
        $capability = ObjectSerializer::deserialize(
            '{"carrier":"FOOCARRIER","packageTypes":["PACKAGE"]}',
            self::CAPABILITY
        );

        self::assertSame('FOOCARRIER', $capability->getCarrier());
    }

    public function testUnknownEnumElementInsideAListPassesThroughAndKeepsTheList(): void
    {
        $capability = ObjectSerializer::deserialize(
            '{"carrier":"DHL_FOR_YOU","packageTypes":["PACKAGE","FOOTYPE"]}',
            self::CAPABILITY
        );

        self::assertSame(['PACKAGE', 'FOOTYPE'], $capability->getPackageTypes());
    }

    public function testKnownEnumValueStillDeserializes(): void
    {
        $capability = ObjectSerializer::deserialize(
            '{"carrier":"DHL_FOR_YOU","packageTypes":["PACKAGE"]}',
            self::CAPABILITY
        );

        self::assertSame('DHL_FOR_YOU', $capability->getCarrier());
    }

    public function testListenerObservesTheUnknownValue(): void
    {
        $seen = [];
        EnumFallback::setListener(static function (string $enumClass, $value) use (&$seen): void {
            $seen[] = $value;
        });

        ObjectSerializer::deserialize(
            '{"carrier":"FOOCARRIER","packageTypes":["PACKAGE"]}',
            self::CAPABILITY
        );

        self::assertContains('FOOCARRIER', $seen);
    }

    /**
     * Serialization applies to sending a request to the API.
     * Here we can safely validate against the spec and should prevent developers making errors.
     * @return void
     */
    public function testRequestSerializationStillRejectsUnknownEnum(): void
    {
        $capability = new RefCapabilitiesResponseCapabilityV2();
        $capability->setCarrier('FOOCARRIER');

        $this->expectException(InvalidArgumentException::class);
        ObjectSerializer::sanitizeForSerialization($capability);
    }
}
