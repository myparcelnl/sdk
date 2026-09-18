<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Shipment\Mapping;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefTypesCarrier;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefCapabilitiesSharedCarrierV2;
use MyParcelNL\Sdk\Model\Shipment\Mapping\CarrierApiMapping;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use ReflectionClass;

/**
 * Preserves the public conversion, validation and exception contracts of CarrierApiMapping.
 */
class CarrierApiMappingTest extends TestCase
{
    private CarrierApiMapping $mapping;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mapping = new CarrierApiMapping();
    }

    public function testEnumToIdReturnsCorrectId(): void
    {
        $this->assertSame(RefTypesCarrier::POSTNL, $this->mapping->enumToId(RefCapabilitiesSharedCarrierV2::POSTNL));
        $this->assertSame(RefTypesCarrier::DHL_FOR_YOU, $this->mapping->enumToId(RefCapabilitiesSharedCarrierV2::DHL_FOR_YOU));
        $this->assertSame(RefTypesCarrier::POSTE_ITALIANE, $this->mapping->enumToId(RefCapabilitiesSharedCarrierV2::POSTE_ITALIANE));
    }

    public function testIdToEnumReturnsCorrectEnum(): void
    {
        $this->assertSame(RefCapabilitiesSharedCarrierV2::POSTNL, $this->mapping->idToEnum(RefTypesCarrier::POSTNL));
        $this->assertSame(RefCapabilitiesSharedCarrierV2::DHL_FOR_YOU, $this->mapping->idToEnum(RefTypesCarrier::DHL_FOR_YOU));
    }

    public function testRoundTripHoldsForEveryMappedCarrier(): void
    {
        foreach ($this->mapping->all() as $enum => $id) {
            $this->assertSame($id, $this->mapping->enumToId($enum));
            $this->assertSame($enum, $this->mapping->idToEnum($id));
        }
    }

    public function testEnumToIdThrowsWithExactMessageForUnknownValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Unknown carrier 'NONEXISTENT'");
        $this->mapping->enumToId('NONEXISTENT');
    }

    public function testIdToEnumThrowsWithExactMessageForUnknownId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Unknown carrier id '999'");
        $this->mapping->idToEnum(999);
    }

    /**
     * New shared constants must appear without updating a fixed carrier count.
     */
    public function testAllIncludesEverySharedCarrier(): void
    {
        $this->assertCount(
            count(array_intersect_key(
                (new ReflectionClass(RefTypesCarrier::class))->getConstants(),
                (new ReflectionClass(RefCapabilitiesSharedCarrierV2::class))->getConstants()
            )),
            $this->mapping->all()
        );
    }

    public function testAllIsKeyedByV2NameAndValuedByV1Id(): void
    {
        $all = $this->mapping->all();

        $this->assertArrayHasKey(RefCapabilitiesSharedCarrierV2::POSTNL, $all);
        $this->assertSame(RefTypesCarrier::POSTNL, $all[RefCapabilitiesSharedCarrierV2::POSTNL]);
    }

    public function testIsValidReturnsTrueForKnownValue(): void
    {
        $this->assertTrue($this->mapping->isValid(RefCapabilitiesSharedCarrierV2::DPD));
    }

    public function testIsValidReturnsFalseForUnknownValue(): void
    {
        $this->assertFalse($this->mapping->isValid('NONEXISTENT'));
    }

    /**
     * The behaviour change of INT-1441, stated explicitly.
     *
     * These three have a v1 id AND a capabilities v2 value, but the frozen map did not know
     * them. They are now recognised — which is why this ticket needs a release note.
     */
    public function testPreviouslyUnrecognisedCarriersAreNowValid(): void
    {
        $this->assertTrue($this->mapping->isValid('SPRING'));
        $this->assertTrue($this->mapping->isValid('VIA_TIM'));
        $this->assertTrue($this->mapping->isValid('DHL_FREIGHT'));

        $this->assertSame(19, $this->mapping->enumToId('SPRING'));
        $this->assertSame(21, $this->mapping->enumToId('DHL_FREIGHT'));
    }

    /**
     * Not every v1 carrier gains a v2 name. These four have an id but no capabilities value, so
     * they stay invalid rather than being given an invented spelling the endpoint would reject.
     */
    public function testCarriersWithoutACapabilitiesValueStayInvalid(): void
    {
        foreach (['BOL', 'INSTABOX', 'UPS', 'DHL_CHEAP_CARGO'] as $carrier) {
            $this->assertFalse($this->mapping->isValid($carrier), $carrier);
        }

        $this->expectException(InvalidArgumentException::class);
        $this->mapping->idToEnum(RefTypesCarrier::BOL);
    }

    /**
     * Lowercase is not a valid v2 value today. INT-1441 must not loosen this.
     */
    public function testIsValidRejectsLowercaseInput(): void
    {
        $this->assertFalse($this->mapping->isValid('postnl'));
        $this->assertFalse($this->mapping->isValid('dhlforyou'));
    }
}
