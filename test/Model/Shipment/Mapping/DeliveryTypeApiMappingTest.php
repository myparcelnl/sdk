<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Shipment\Mapping;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefTypesDeliveryType;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefTypesDeliveryTypeV2;
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
        $this->assertSame(RefTypesDeliveryType::MORNING, $this->mapping->enumToId(RefTypesDeliveryTypeV2::MORNING));
        $this->assertSame(RefTypesDeliveryType::STANDARD, $this->mapping->enumToId(RefTypesDeliveryTypeV2::STANDARD));
        $this->assertSame(RefTypesDeliveryType::EVENING, $this->mapping->enumToId(RefTypesDeliveryTypeV2::EVENING));
        $this->assertSame(RefTypesDeliveryType::PICKUP, $this->mapping->enumToId(RefTypesDeliveryTypeV2::PICKUP));
        $this->assertSame(RefTypesDeliveryType::SAME_DAY, $this->mapping->enumToId(RefTypesDeliveryTypeV2::SAME_DAY));
        $this->assertSame(RefTypesDeliveryType::EXPRESS, $this->mapping->enumToId(RefTypesDeliveryTypeV2::EXPRESS));
        $this->assertSame(RefTypesDeliveryType::EARLY_MORNING, $this->mapping->enumToId(RefTypesDeliveryTypeV2::EARLY_MORNING));
    }

    public function testEnumToIdThrowsForUnknownValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->mapping->enumToId('NONEXISTENT');
    }

    public function testIdToEnumReturnsCorrectEnum(): void
    {
        $this->assertSame(RefTypesDeliveryTypeV2::MORNING, $this->mapping->idToEnum(RefTypesDeliveryType::MORNING));
        $this->assertSame(RefTypesDeliveryTypeV2::STANDARD, $this->mapping->idToEnum(RefTypesDeliveryType::STANDARD));
        $this->assertSame(RefTypesDeliveryTypeV2::PICKUP, $this->mapping->idToEnum(RefTypesDeliveryType::PICKUP));
    }

    public function testIdToEnumThrowsForUnknownId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->mapping->idToEnum(999);
    }

    public function testAllReturnsFullMap(): void
    {
        $all = $this->mapping->all();

        $this->assertCount(7, $all);
        $this->assertArrayHasKey(RefTypesDeliveryTypeV2::MORNING, $all);
        $this->assertArrayHasKey(RefTypesDeliveryTypeV2::EARLY_MORNING, $all);
    }

    public function testIsValidReturnsTrueForKnownValue(): void
    {
        $this->assertTrue($this->mapping->isValid(RefTypesDeliveryTypeV2::STANDARD));
    }

    public function testIsValidReturnsFalseForUnknownValue(): void
    {
        $this->assertFalse($this->mapping->isValid('NONEXISTENT'));
    }
}
