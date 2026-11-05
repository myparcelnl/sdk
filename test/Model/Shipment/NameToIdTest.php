<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Shipment;

use InvalidArgumentException;
use MyParcelNL\Sdk\Model\Shipment\Carrier;
use MyParcelNL\Sdk\Model\Shipment\PackageType;
use PHPUnit\Framework\TestCase;

final class NameToIdTest extends TestCase
{
    public function testCarrierToIdThrowsOnUnknown(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Unknown carrier 'FOO'");

        Carrier::toId('FOO');
    }

    public function testPackageTypeToIdThrowsOnUnknown(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Unknown package type 'FOO'");

        PackageType::toId('FOO');
    }
}
