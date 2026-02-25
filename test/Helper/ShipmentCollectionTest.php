<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Helper;

use InvalidArgumentException;
use MyParcelNL\Sdk\Collection\ShipmentCollection;
use MyParcelNL\Sdk\Model\Shipment\Shipment;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

final class ShipmentCollectionTest extends TestCase
{
    public function testConstructorValidatesItems(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('All items must be instances of ' . Shipment::class);

        new ShipmentCollection([new Shipment(), new \stdClass()]);
    }

    public function testPushSingleAndMultipleItems(): void
    {
        $shipmentOne = new Shipment();
        $shipmentTwo = new Shipment();
        $shipmentThree = new Shipment();

        $collection = new ShipmentCollection();
        $collection->push($shipmentOne)->push($shipmentTwo, $shipmentThree);

        self::assertSame(3, $collection->count());
        self::assertSame([$shipmentOne, $shipmentTwo, $shipmentThree], $collection->all());
    }

    public function testPushThrowsForNonShipmentValues(): void
    {
        $collection = new ShipmentCollection();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('All items must be instances of ' . Shipment::class);

        $collection->push(new Shipment(), new \stdClass());
    }

    public function testValuesReturnsReindexedCollection(): void
    {
        $shipmentOne = new Shipment();
        $shipmentTwo = new Shipment();

        $collection = new ShipmentCollection([10 => $shipmentOne, 20 => $shipmentTwo]);

        self::assertSame([$shipmentOne, $shipmentTwo], $collection->values()->all());
    }

    public function testFirstAndLast(): void
    {
        $shipmentOne = new Shipment();
        $shipmentTwo = new Shipment();
        $shipmentThree = new Shipment();

        $collection = new ShipmentCollection([$shipmentOne, $shipmentTwo, $shipmentThree]);

        self::assertSame($shipmentOne, $collection->first());
        self::assertSame($shipmentThree, $collection->last());
    }

    public function testFirstAndLastReturnNullForEmptyCollection(): void
    {
        $collection = new ShipmentCollection();

        self::assertNull($collection->first());
        self::assertNull($collection->last());
    }

    public function testWhereReferenceIdentifier(): void
    {
        $shipmentOne = (new Shipment())->setReferenceIdentifier('order-100');
        $shipmentTwo = (new Shipment())->setReferenceIdentifier('order-200');
        $shipmentThree = (new Shipment())->setReferenceIdentifier('order-100');

        $collection = new ShipmentCollection([$shipmentOne, $shipmentTwo, $shipmentThree]);
        $matches = $collection->whereReferenceIdentifier('order-100');

        self::assertInstanceOf(ShipmentCollection::class, $matches);
        self::assertCount(2, $matches);
        self::assertSame([$shipmentOne, $shipmentThree], $matches->all());
    }

    public function testWhereReferenceIdentifierPrefix(): void
    {
        $shipmentOne = (new Shipment())->setReferenceIdentifier('group-A-001');
        $shipmentTwo = (new Shipment())->setReferenceIdentifier('group-A-002');
        $shipmentThree = (new Shipment())->setReferenceIdentifier('group-B-001');
        $shipmentFour = new Shipment(); // null reference

        $collection = new ShipmentCollection([$shipmentOne, $shipmentTwo, $shipmentThree, $shipmentFour]);
        $matches = $collection->whereReferenceIdentifierPrefix('group-A-');

        self::assertInstanceOf(ShipmentCollection::class, $matches);
        self::assertCount(2, $matches);
        self::assertSame([$shipmentOne, $shipmentTwo], $matches->all());
    }

    public function testSpliceCanClearCollection(): void
    {
        $collection = new ShipmentCollection([new Shipment(), new Shipment()]);

        self::assertSame(2, $collection->count());

        $collection->splice(0);

        self::assertSame(0, $collection->count());
        self::assertSame([], $collection->all());
    }
}
