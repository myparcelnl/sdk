<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Carrier;

use MyParcelNL\Sdk\Model\Carrier\CarrierBRT;
use MyParcelNL\Sdk\Model\Carrier\CarrierFactory;
use MyParcelNL\Sdk\Model\Carrier\CarrierInPost;
use MyParcelNL\Sdk\Model\Carrier\CarrierPosteItaliane;
use MyParcelNL\Sdk\Model\Consignment\BRTConsignment;
use MyParcelNL\Sdk\Model\Consignment\InPostConsignment;
use MyParcelNL\Sdk\Model\Consignment\PosteItalianeConsignment;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class CarrierFactoryTest extends TestCase
{
    /**
     * @dataProvider provideAddedCarriers
     *
     * @param class-string<\MyParcelNL\Sdk\Model\Carrier\AbstractCarrier> $carrierClass
     * @param class-string<\MyParcelNL\Sdk\Model\Consignment\AbstractConsignment> $consignmentClass
     *
     * @throws \Exception
     */
    public function testCreatesAddedCarrierFromIdAndName(
        string $carrierClass,
        string $consignmentClass,
        int $id,
        string $name,
        string $human
    ): void {
        self::assertTrue(CarrierFactory::canCreateFromId($id));

        $carrier = CarrierFactory::createFromId($id);

        self::assertInstanceOf($carrierClass, $carrier);
        self::assertSame($id, $carrier->getId());
        self::assertSame($name, $carrier->getName());
        self::assertSame($human, $carrier->getHuman());
        self::assertSame($consignmentClass, $carrier->getConsignmentClass());

        self::assertInstanceOf($carrierClass, CarrierFactory::createFromName($name));
    }

    /**
     * @return \Generator<string, array{class-string, class-string, int, string, string}>
     */
    public function provideAddedCarriers(): \Generator
    {
        yield 'brt' => [
            CarrierBRT::class,
            BRTConsignment::class,
            15,
            'brt',
            'BRT',
        ];

        yield 'inpost' => [
            CarrierInPost::class,
            InPostConsignment::class,
            17,
            'inpost',
            'InPost',
        ];

        yield 'poste italiane' => [
            CarrierPosteItaliane::class,
            PosteItalianeConsignment::class,
            18,
            'posteitaliane',
            'Poste Italiane',
        ];
    }
}
