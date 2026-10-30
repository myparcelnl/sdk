<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItem;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemOptions;
use MyParcelNL\Sdk\Model\Shipment\Shipment;
use MyParcelNL\Sdk\Model\Shipment\ShipmentOptions;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use ReflectionClass;
use ReflectionMethod;
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
     * The test sets all fields first, so a setter that writes another field also fails.
     *
     * @dataProvider provideWriteModels
     *
     * @param class-string $sdkModel
     * @param class-string $janeModel
     */
    public function testEveryGetterReturnsWhatItsSetterSet(string $sdkModel, string $janeModel): void
    {
        $model    = new $sdkModel();
        $expected = [];

        foreach ((new ReflectionClass($janeModel))->getProperties(ReflectionProperty::IS_PUBLIC) as $seed => $property) {
            $name      = ucfirst($property->getName());
            $parameter = (new ReflectionMethod($sdkModel, "set{$name}"))->getParameters()[0]->getType();
            $value     = AccessorValues::acceptedByBoth($parameter, (new ReflectionMethod($sdkModel, "get{$name}"))->getReturnType(), $seed);

            $model->{"set{$name}"}($value);
            $expected["get{$name}"] = $value;
        }

        foreach ($expected as $getter => $value) {
            self::assertSame($value, $model->{$getter}(), "{$sdkModel}::{$getter}()");
        }
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
