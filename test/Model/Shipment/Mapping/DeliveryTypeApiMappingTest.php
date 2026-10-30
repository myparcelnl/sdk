<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Shipment\Mapping;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesDeliveryType;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesDeliveryTypeV2;
use MyParcelNL\Sdk\Model\Shipment\Mapping\DeliveryTypeApiMapping;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class DeliveryTypeApiMappingTest extends TestCase
{
    private DeliveryTypeApiMapping $mapping;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mapping = new DeliveryTypeApiMapping();
    }

    public function testEnumToIdReturnsCorrectId(): void
    {
        $this->assertSame(RefTypesDeliveryType::Morning->value, $this->mapping->enumToId(RefTypesDeliveryTypeV2::Morning->value));
        $this->assertSame(RefTypesDeliveryType::Standard->value, $this->mapping->enumToId(RefTypesDeliveryTypeV2::Standard->value));
        $this->assertSame(RefTypesDeliveryType::Evening->value, $this->mapping->enumToId(RefTypesDeliveryTypeV2::Evening->value));
        $this->assertSame(RefTypesDeliveryType::Pickup->value, $this->mapping->enumToId(RefTypesDeliveryTypeV2::Pickup->value));
        $this->assertSame(RefTypesDeliveryType::SameDay->value, $this->mapping->enumToId(RefTypesDeliveryTypeV2::SameDay->value));
        $this->assertSame(RefTypesDeliveryType::Express->value, $this->mapping->enumToId(RefTypesDeliveryTypeV2::Express->value));
        $this->assertSame(RefTypesDeliveryType::EarlyMorning->value, $this->mapping->enumToId(RefTypesDeliveryTypeV2::EarlyMorning->value));
    }

    public function testEnumToIdThrowsForUnknownValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->mapping->enumToId('NONEXISTENT');
    }

    public function testIdToEnumReturnsCorrectEnum(): void
    {
        $this->assertSame(RefTypesDeliveryTypeV2::Morning->value, $this->mapping->idToEnum(RefTypesDeliveryType::Morning->value));
        $this->assertSame(RefTypesDeliveryTypeV2::Standard->value, $this->mapping->idToEnum(RefTypesDeliveryType::Standard->value));
        $this->assertSame(RefTypesDeliveryTypeV2::Pickup->value, $this->mapping->idToEnum(RefTypesDeliveryType::Pickup->value));
    }

    public function testIdToEnumThrowsForUnknownId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->mapping->idToEnum(999);
    }

    public function testAllReturnsFullMap(): void
    {
        $all = $this->mapping->all();

        $this->assertCount(
            count(array_intersect_key(
                array_flip(array_column(RefTypesDeliveryType::cases(), 'name')),
                array_flip(array_column(RefTypesDeliveryTypeV2::cases(), 'name'))
            )),
            $all
        );
        $this->assertArrayHasKey(RefTypesDeliveryTypeV2::Morning->value, $all);
        $this->assertArrayHasKey(RefTypesDeliveryTypeV2::EarlyMorning->value, $all);
    }

    public function testIsValidReturnsTrueForKnownValue(): void
    {
        $this->assertTrue($this->mapping->isValid(RefTypesDeliveryTypeV2::Standard->value));
    }

    public function testIsValidReturnsFalseForUnknownValue(): void
    {
        $this->assertFalse($this->mapping->isValid('NONEXISTENT'));
    }

    public function testRoundTripHoldsForEveryDeliveryType(): void
    {
        foreach ($this->mapping->all() as $enum => $id) {
            $this->assertSame($id, $this->mapping->enumToId($enum));
            $this->assertSame($enum, $this->mapping->idToEnum($id));
        }
    }

    public function testEnumToIdThrowsWithExactMessage(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Unknown delivery type 'NONEXISTENT'");
        $this->mapping->enumToId('NONEXISTENT');
    }

    public function testIdToEnumThrowsWithExactMessage(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Unknown delivery type id '999'");
        $this->mapping->idToEnum(999);
    }

    public function testAllIsKeyedByV2NameAndValuedByV1Id(): void
    {
        $all = $this->mapping->all();

        $this->assertSame(RefTypesDeliveryType::Evening->value, $all[RefTypesDeliveryTypeV2::Evening->value]);
    }

    /**
     * The delivery-options spellings are not valid v2 values today.
     * INT-1441's tier 3 must stay narrow enough that this remains true.
     */
    public function testIsValidRejectsDeliveryOptionsSpellings(): void
    {
        $this->assertFalse($this->mapping->isValid('morning'));
        $this->assertFalse($this->mapping->isValid('same_day'));
        $this->assertFalse($this->mapping->isValid('MORNING'));
    }

    /**
     * There is no delivery type with id 5. Recorded so a future spec change that fills the
     * gap shows up here rather than silently.
     */
    public function testIdFiveDoesNotExist(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->mapping->idToEnum(5);
    }
}
