<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Concerns;

use MyParcelNL\Sdk\Concerns\HasCountry;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class HasCountryTest extends TestCase
{
    public function testIsToEuCountryReturnsTrueForNl(): void
    {
        $obj = $this->makeHasCountryObject();
        $obj->setCountry('NL');

        $this->assertTrue($obj->isToEuCountry());
    }

    public function testIsToRowCountryReturnsTrueForUs(): void
    {
        $obj = $this->makeHasCountryObject();
        $obj->setCountry('US');

        $this->assertTrue($obj->isToRowCountry());
    }

    private function makeHasCountryObject(): object
    {
        return new class {
            use HasCountry;
        };
    }
}
