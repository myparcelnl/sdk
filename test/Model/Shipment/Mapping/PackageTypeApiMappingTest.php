<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Shipment\Mapping;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentPackageType;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentPackageTypeV2;
use MyParcelNL\Sdk\Model\Shipment\Mapping\PackageTypeApiMapping;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

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
        $this->assertSame(RefShipmentPackageType::Package->value, $this->mapping->enumToId(RefShipmentPackageTypeV2::Package->value));
        $this->assertSame(RefShipmentPackageType::Unfranked->value, $this->mapping->enumToId(RefShipmentPackageTypeV2::Unfranked->value));
        $this->assertSame(RefShipmentPackageType::SmallPackage->value, $this->mapping->enumToId(RefShipmentPackageTypeV2::SmallPackage->value));
    }

    public function testIdToEnumReturnsCorrectEnum(): void
    {
        $this->assertSame(RefShipmentPackageTypeV2::Package->value, $this->mapping->idToEnum(RefShipmentPackageType::Package->value));
        $this->assertSame(RefShipmentPackageTypeV2::Envelope->value, $this->mapping->idToEnum(RefShipmentPackageType::Envelope->value));
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
                array_flip(array_column(RefShipmentPackageType::cases(), 'name')),
                array_flip(array_column(RefShipmentPackageTypeV2::cases(), 'name'))
            )),
            $this->mapping->all()
        );
    }

    public function testAllIsKeyedByV2NameAndValuedByV1Id(): void
    {
        $all = $this->mapping->all();

        $this->assertArrayHasKey(RefShipmentPackageTypeV2::Mailbox->value, $all);
        $this->assertSame(RefShipmentPackageType::Mailbox->value, $all[RefShipmentPackageTypeV2::Mailbox->value]);
    }

    public function testIsValidReturnsTrueForKnownValue(): void
    {
        $this->assertTrue($this->mapping->isValid(RefShipmentPackageTypeV2::DigitalStamp->value));
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
