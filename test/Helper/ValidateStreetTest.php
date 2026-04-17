<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Helper;

use MyParcelNL\Sdk\Helper\ValidateStreet;
use MyParcelNL\Sdk\Services\CountryCodes;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class ValidateStreetTest extends TestCase
{
    public function testGetStreetRegexByCountryReturnsNlRegex(): void
    {
        $regex = ValidateStreet::getStreetRegexByCountry(CountryCodes::CC_NL, CountryCodes::CC_NL);
        $this->assertNotNull($regex);
        $this->assertSame(ValidateStreet::SPLIT_STREET_REGEX_NL, $regex);
    }

    public function testGetStreetRegexByCountryReturnsBeRegex(): void
    {
        $regex = ValidateStreet::getStreetRegexByCountry(CountryCodes::CC_BE, CountryCodes::CC_BE);
        $this->assertNotNull($regex);
        $this->assertSame(ValidateStreet::SPLIT_STREET_REGEX_BE, $regex);
    }

    public function testGetStreetRegexByCountryReturnsNullForOtherCountries(): void
    {
        $this->assertNull(ValidateStreet::getStreetRegexByCountry('DE', 'DE'));
    }

    public function testValidateReturnsTrueForValidNlStreet(): void
    {
        $this->assertTrue(ValidateStreet::validate('Antareslaan 31', CountryCodes::CC_NL, CountryCodes::CC_NL));
    }

    public function testValidateReturnsTrueWhenDestinationIsNull(): void
    {
        $this->assertTrue(ValidateStreet::validate('Any street', CountryCodes::CC_NL, null));
    }

    public function testValidateReturnsTrueForNonNlBe(): void
    {
        $this->assertTrue(ValidateStreet::validate('Hauptstraße 1', 'DE', 'DE'));
    }
}
