<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Shipment\Mapping;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesCarrier;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedCarrierV2;
use MyParcelNL\Sdk\Model\Shipment\Mapping\CarrierApiMapping;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

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
        $this->assertSame(RefTypesCarrier::Postnl->value, $this->mapping->enumToId(RefCapabilitiesSharedCarrierV2::Postnl->value));
        $this->assertSame(RefTypesCarrier::DhlForYou->value, $this->mapping->enumToId(RefCapabilitiesSharedCarrierV2::DhlForYou->value));
        $this->assertSame(RefTypesCarrier::PosteItaliane->value, $this->mapping->enumToId(RefCapabilitiesSharedCarrierV2::PosteItaliane->value));
    }

    public function testIdToEnumReturnsCorrectEnum(): void
    {
        $this->assertSame(RefCapabilitiesSharedCarrierV2::Postnl->value, $this->mapping->idToEnum(RefTypesCarrier::Postnl->value));
        $this->assertSame(RefCapabilitiesSharedCarrierV2::DhlForYou->value, $this->mapping->idToEnum(RefTypesCarrier::DhlForYou->value));
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
                array_flip(array_column(RefTypesCarrier::cases(), 'name')),
                array_flip(array_column(RefCapabilitiesSharedCarrierV2::cases(), 'name'))
            )),
            $this->mapping->all()
        );
    }

    public function testAllIsKeyedByV2NameAndValuedByV1Id(): void
    {
        $all = $this->mapping->all();

        $this->assertArrayHasKey(RefCapabilitiesSharedCarrierV2::Postnl->value, $all);
        $this->assertSame(RefTypesCarrier::Postnl->value, $all[RefCapabilitiesSharedCarrierV2::Postnl->value]);
    }

    public function testIsValidReturnsTrueForKnownValue(): void
    {
        $this->assertTrue($this->mapping->isValid(RefCapabilitiesSharedCarrierV2::Dpd->value));
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
        $this->mapping->idToEnum(RefTypesCarrier::Bol->value);
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
