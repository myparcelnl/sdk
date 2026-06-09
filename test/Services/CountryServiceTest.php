<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services;

use MyParcelNL\Sdk\Services\CountryService;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class CountryServiceTest extends TestCase
{
    public function testGetAllReturnsArray(): void
    {
        $service = new CountryService();
        $all = $service->getAll();

        $this->assertIsArray($all);
        $this->assertNotEmpty($all);
    }

    public function testIsEuReturnsTrueForEuCountry(): void
    {
        $this->assertTrue((new CountryService())->isEu('DE'));
    }

    public function testIsEuReturnsFalseForUniqueCountry(): void
    {
        // NL is a "unique" country, not grouped under EU zone
        $this->assertFalse((new CountryService())->isEu('NL'));
    }

    public function testIsEuReturnsFalseForUs(): void
    {
        $this->assertFalse((new CountryService())->isEu('US'));
    }

    public function testIsRowReturnsTrueForUs(): void
    {
        $this->assertTrue((new CountryService())->isRow('US'));
    }

    public function testIsRowReturnsFalseForEu(): void
    {
        $this->assertFalse((new CountryService())->isRow('DE'));
    }

    public function testGetShippingZoneReturnsUniqueForNl(): void
    {
        $this->assertSame('NL', (new CountryService())->getShippingZone('NL'));
    }

    public function testGetShippingZoneReturnsEuForDe(): void
    {
        $service = new CountryService();
        $this->assertSame('EU', $service->getShippingZone('DE'));
    }

    public function testGetShippingZoneReturnsRowForUs(): void
    {
        $this->assertSame('ROW', (new CountryService())->getShippingZone('US'));
    }
}
