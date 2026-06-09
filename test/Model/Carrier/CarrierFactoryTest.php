<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Carrier;

use MyParcelNL\Sdk\Model\Carrier\AbstractCarrier;
use MyParcelNL\Sdk\Model\Carrier\CarrierFactory;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class CarrierFactoryTest extends TestCase
{
    /**
     * @dataProvider provideConfiguredCarriers
     *
     * @param class-string<AbstractCarrier> $carrierClass
     *
     * @throws \Exception
     */
    public function testCreatesConfiguredCarrierFromIdAndName(string $carrierClass): void
    {
        $id    = $carrierClass::ID;
        $name  = $carrierClass::NAME;
        $human = $carrierClass::HUMAN;

        self::assertTrue(CarrierFactory::canCreateFromId($id));

        $carrier = CarrierFactory::createFromId($id);

        self::assertInstanceOf($carrierClass, $carrier);
        self::assertSame($id, $carrier->getId());
        self::assertSame($name, $carrier->getName());
        self::assertSame($human, $carrier->getHuman());

        self::assertInstanceOf($carrierClass, CarrierFactory::createFromName($name));
    }

    /**
     * @return \Generator<string, array{class-string<AbstractCarrier>}>
     */
    public function provideConfiguredCarriers(): \Generator
    {
        foreach (CarrierFactory::CARRIER_CLASSES as $carrierClass) {
            /** @var class-string<AbstractCarrier> $carrierClass */
            yield basename(str_replace('\\', '/', $carrierClass)) => [$carrierClass];
        }
    }
}
