<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Entity\Consignment;

use InvalidArgumentException;
use MyParcelNL\Sdk\src\Entity\Consignment\PackageType;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use PHPUnit\Framework\TestCase;

class PackageTypeTest extends TestCase
{
    public function provideConstructData(): array
    {
        return [
            ['1', AbstractConsignment::PACKAGE_TYPE_PACKAGE_NAME],
            ['2', AbstractConsignment::PACKAGE_TYPE_MAILBOX_NAME],
            ['3', AbstractConsignment::PACKAGE_TYPE_LETTER_NAME],
            ['4', AbstractConsignment::PACKAGE_TYPE_DIGITAL_STAMP_NAME],
            [AbstractConsignment::PACKAGE_TYPE_PACKAGE, AbstractConsignment::PACKAGE_TYPE_PACKAGE_NAME],
            [AbstractConsignment::PACKAGE_TYPE_MAILBOX, AbstractConsignment::PACKAGE_TYPE_MAILBOX_NAME],
            [AbstractConsignment::PACKAGE_TYPE_LETTER, AbstractConsignment::PACKAGE_TYPE_LETTER_NAME],
            [AbstractConsignment::PACKAGE_TYPE_DIGITAL_STAMP, AbstractConsignment::PACKAGE_TYPE_DIGITAL_STAMP_NAME],
            [AbstractConsignment::PACKAGE_TYPE_PACKAGE_NAME],
            [AbstractConsignment::PACKAGE_TYPE_MAILBOX_NAME],
            [AbstractConsignment::PACKAGE_TYPE_LETTER_NAME],
            [AbstractConsignment::PACKAGE_TYPE_DIGITAL_STAMP_NAME],
        ];
    }

    public function provideInvalidConstructData(): array
    {
        return [
            ['5678'],
            ['glass'],
            [new PackageType(2)],
        ];
    }

    /**
     * @param  string|int $input
     * @param  null       $output
     *
     * @throws \Exception
     * @dataProvider provideConstructData
     */
    public function testConstruct($input, $output = null): void
    {
        $deliveryType = new PackageType($input);
        self::assertEquals($output ?? $input, $deliveryType->getName());
    }

    /**
     * @param  mixed $input
     *
     * @throws \Exception
     * @dataProvider provideInvalidConstructData
     */
    public function testInvalidConstruct($input): void
    {
        $this->expectException(InvalidArgumentException::class);
        new PackageType($input);
    }
}
