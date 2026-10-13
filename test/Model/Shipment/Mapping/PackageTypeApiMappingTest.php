<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Shipment\Mapping;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefShipmentPackageType;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefShipmentPackageTypeV2;
use MyParcelNL\Sdk\Model\Shipment\Mapping\PackageTypeApiMapping;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use ReflectionClass;

/**
 * Preserves the public conversion, validation and exception contracts of PackageTypeApiMapping.
 */
class PackageTypeApiMappingTest extends TestCase
{
    private PackageTypeApiMapping $mapping;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mapping = new PackageTypeApiMapping();
    }

    public function testEnumToIdReturnsCorrectId(): void
    {
        $this->assertSame(RefShipmentPackageType::PACKAGE, $this->mapping->enumToId(RefShipmentPackageTypeV2::PACKAGE));
        $this->assertSame(RefShipmentPackageType::UNFRANKED, $this->mapping->enumToId(RefShipmentPackageTypeV2::UNFRANKED));
        $this->assertSame(RefShipmentPackageType::SMALL_PACKAGE, $this->mapping->enumToId(RefShipmentPackageTypeV2::SMALL_PACKAGE));
    }

    public function testIdToEnumReturnsCorrectEnum(): void
    {
        $this->assertSame(RefShipmentPackageTypeV2::PACKAGE, $this->mapping->idToEnum(RefShipmentPackageType::PACKAGE));
        $this->assertSame(RefShipmentPackageTypeV2::ENVELOPE, $this->mapping->idToEnum(RefShipmentPackageType::ENVELOPE));
    }

    public function testRoundTripHoldsForEveryPackageType(): void
    {
        foreach ($this->mapping->all() as $enum => $id) {
            $this->assertSame($id, $this->mapping->enumToId($enum));
            $this->assertSame($enum, $this->mapping->idToEnum($id));
        }
    }

    public function testEnumToIdThrowsWithExactMessageForUnknownValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Unknown package type 'NONEXISTENT'");
        $this->mapping->enumToId('NONEXISTENT');
    }

    public function testIdToEnumThrowsWithExactMessageForUnknownId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Unknown package type id '999'");
        $this->mapping->idToEnum(999);
    }

    public function testAllIncludesEverySharedPackageType(): void
    {
        $this->assertCount(
            count(array_intersect_key(
                (new ReflectionClass(RefShipmentPackageType::class))->getConstants(),
                (new ReflectionClass(RefShipmentPackageTypeV2::class))->getConstants()
            )),
            $this->mapping->all()
        );
    }

    public function testAllIsKeyedByV2NameAndValuedByV1Id(): void
    {
        $all = $this->mapping->all();

        $this->assertArrayHasKey(RefShipmentPackageTypeV2::MAILBOX, $all);
        $this->assertSame(RefShipmentPackageType::MAILBOX, $all[RefShipmentPackageTypeV2::MAILBOX]);
    }

    public function testIsValidReturnsTrueForKnownValue(): void
    {
        $this->assertTrue($this->mapping->isValid(RefShipmentPackageTypeV2::DIGITAL_STAMP));
    }

    public function testIsValidReturnsFalseForUnknownValue(): void
    {
        $this->assertFalse($this->mapping->isValid('NONEXISTENT'));
    }

    /**
     * The load-bearing assertion of this file.
     *
     * These are the delivery-options spellings of the same concepts. They are NOT valid v2
     * values, and INT-1441's tier 3 must stay narrow enough that they stay invalid — otherwise
     * ShipmentOptions::setPackageType() silently starts accepting input it rejects today.
     */
    public function testIsValidRejectsDeliveryOptionsSpellings(): void
    {
        $this->assertFalse($this->mapping->isValid('package'));
        $this->assertFalse($this->mapping->isValid('small_package'));
        $this->assertFalse($this->mapping->isValid('digital_stamp'));
        $this->assertFalse($this->mapping->isValid('letter'));
        $this->assertFalse($this->mapping->isValid('package_small'));
    }
}
