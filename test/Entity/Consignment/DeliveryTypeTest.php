<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Entity\Consignment;

use InvalidArgumentException;
use MyParcelNL\Sdk\src\Entity\Consignment\DeliveryType;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use PHPUnit\Framework\TestCase;

class DeliveryTypeTest extends TestCase
{
    public function provideConstructData(): array
    {
        return [
            ['1', AbstractConsignment::DELIVERY_TYPE_MORNING_NAME],
            ['2', AbstractConsignment::DELIVERY_TYPE_STANDARD_NAME],
            ['3', AbstractConsignment::DELIVERY_TYPE_EVENING_NAME],
            ['4', AbstractConsignment::DELIVERY_TYPE_PICKUP_NAME],
            ['5', AbstractConsignment::DELIVERY_TYPE_PICKUP_EXPRESS_NAME],
            [AbstractConsignment::DELIVERY_TYPE_MORNING, AbstractConsignment::DELIVERY_TYPE_MORNING_NAME],
            [AbstractConsignment::DELIVERY_TYPE_STANDARD, AbstractConsignment::DELIVERY_TYPE_STANDARD_NAME],
            [AbstractConsignment::DELIVERY_TYPE_EVENING, AbstractConsignment::DELIVERY_TYPE_EVENING_NAME],
            [AbstractConsignment::DELIVERY_TYPE_PICKUP, AbstractConsignment::DELIVERY_TYPE_PICKUP_NAME],
            [AbstractConsignment::DELIVERY_TYPE_PICKUP_EXPRESS, AbstractConsignment::DELIVERY_TYPE_PICKUP_EXPRESS_NAME],
            [AbstractConsignment::DELIVERY_TYPE_MORNING_NAME],
            [AbstractConsignment::DELIVERY_TYPE_STANDARD_NAME],
            [AbstractConsignment::DELIVERY_TYPE_EVENING_NAME],
            [AbstractConsignment::DELIVERY_TYPE_PICKUP_NAME],
            [AbstractConsignment::DELIVERY_TYPE_PICKUP_EXPRESS_NAME],
        ];
    }

    public function provideInvalidConstructData(): array
    {
        return [
            ['1234'],
            ['drone'],
            [new DeliveryType(1)],
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
        $deliveryType = new DeliveryType($input);
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
        new DeliveryType($input);
    }
}
