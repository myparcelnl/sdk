<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Helper;

use MyParcelNL\Sdk\Helper\SplitStreet;
use MyParcelNL\Sdk\Model\FullStreet;
use MyParcelNL\Sdk\Services\CountryCodes;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class SplitStreetTest extends TestCase
{
    public function testSplitStreetNl(): void
    {
        $result = SplitStreet::splitStreet('Antareslaan 31', CountryCodes::CC_NL, CountryCodes::CC_NL);

        $this->assertInstanceOf(FullStreet::class, $result);
        $this->assertSame('Antareslaan', $result->getStreet());
        $this->assertSame(31, $result->getNumber());
    }

    public function testSplitStreetNlWithSuffix(): void
    {
        $result = SplitStreet::splitStreet('Keizersgracht 100 A', CountryCodes::CC_NL, CountryCodes::CC_NL);

        $this->assertInstanceOf(FullStreet::class, $result);
        $this->assertSame('Keizersgracht', $result->getStreet());
        $this->assertSame(100, $result->getNumber());
        $this->assertSame('A', $result->getNumberSuffix());
    }

    public function testSplitStreetBe(): void
    {
        $result = SplitStreet::splitStreet('Rue de Rivoli 10', CountryCodes::CC_BE, CountryCodes::CC_BE);

        $this->assertInstanceOf(FullStreet::class, $result);
    }

    public function testSplitStreetOtherCountryReturnsFull(): void
    {
        $result = SplitStreet::splitStreet('Hauptstraße 1', 'DE', 'DE');

        $this->assertInstanceOf(FullStreet::class, $result);
        $this->assertSame('Hauptstraße 1', $result->getStreet());
        $this->assertNull($result->getNumber());
    }
}
