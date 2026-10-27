<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItem;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemOptions;
use MyParcelNL\Sdk\Model\Shipment\Shipment;
use MyParcelNL\Sdk\Model\Shipment\ShipmentOptions;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use ReflectionClass;
use ReflectionProperty;

/**
 * A regeneration can add a property to a Jane model. The SDK model then needs a getter and a setter for it.
 */
final class WriteModelAccessorsTest extends TestCase
{
    /**
     * @dataProvider provideWriteModels
     *
     * @param class-string $sdkModel
     * @param class-string $janeModel
     */
    public function testEveryPropertyOfTheJaneModelHasAGetterAndASetter(string $sdkModel, string $janeModel): void
    {
        $missing = [];

        foreach ((new ReflectionClass($janeModel))->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            foreach (['get', 'set'] as $prefix) {
                $method = $prefix . ucfirst($property->getName());

                if (! method_exists($sdkModel, $method)) {
                    $missing[] = $method;
                }
            }
        }

        self::assertSame([], $missing, sprintf('Add these methods to %s: %s', $sdkModel, implode(', ', $missing)));
    }

    /**
     * @return array<string, array{class-string, class-string}>
     */
    public static function provideWriteModels(): array
    {
        return [
            'Shipment'        => [Shipment::class, ShipmentPostShipmentsRequestV11DataShipmentsItem::class],
            'ShipmentOptions' => [ShipmentOptions::class, ShipmentPostShipmentsRequestV11DataShipmentsItemOptions::class],
        ];
    }
}
