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

    public function testSetShipmentsReplacesItems(): void
    {
        $collection = new ShipmentCollection([new Shipment()]);
        $replacement = [new Shipment(), new Shipment()];

        $collection->setShipments($replacement);

        self::assertSame(2, $collection->count());
        self::assertSame($replacement, $collection->getShipments());
    }

    public function testAddAndAddMany(): void
    {
        $shipmentOne = new Shipment();
        $shipmentTwo = new Shipment();
        $shipmentThree = new Shipment();

        $collection = new ShipmentCollection();
        $collection->add($shipmentOne)->addMany([$shipmentTwo, $shipmentThree]);

        self::assertSame(3, $collection->count());
        self::assertSame([$shipmentOne, $shipmentTwo, $shipmentThree], $collection->getShipments());
    }

    public function testAddManyThrowsForNonShipmentValues(): void
    {
        $collection = new ShipmentCollection();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('All items must be instances of ' . Shipment::class);

        $collection->addMany([new Shipment(), new \stdClass()]);
    }

    public function testGetShipmentsWithoutKeysReturnsReindexedArray(): void
    {
        $shipmentOne = new Shipment();
        $shipmentTwo = new Shipment();

        $collection = new ShipmentCollection();
        $collection->addMany([$shipmentOne, $shipmentTwo]);

        self::assertSame([$shipmentOne, $shipmentTwo], $collection->getShipments(false));
    }

    public function testFirstAndLastShipment(): void
    {
        $shipmentOne = new Shipment();
        $shipmentTwo = new Shipment();
        $shipmentThree = new Shipment();

        $collection = new ShipmentCollection([$shipmentOne, $shipmentTwo, $shipmentThree]);

        self::assertSame($shipmentOne, $collection->firstShipment());
        self::assertSame($shipmentThree, $collection->lastShipment());
    }

    public function testFirstAndLastShipmentReturnNullForEmptyCollection(): void
    {
        $collection = new ShipmentCollection();

        self::assertNull($collection->firstShipment());
        self::assertNull($collection->lastShipment());
    }

    public function testFilterByReferenceId(): void
    {
        $shipmentOne = (new Shipment())->setReferenceIdentifier('order-100');
        $shipmentTwo = (new Shipment())->setReferenceIdentifier('order-200');
        $shipmentThree = (new Shipment())->setReferenceIdentifier('order-100');

        $collection = new ShipmentCollection([$shipmentOne, $shipmentTwo, $shipmentThree]);
        $matches = $collection->filterByReferenceId('order-100');

        self::assertInstanceOf(ShipmentCollection::class, $matches);
        self::assertCount(2, $matches);
        self::assertSame([$shipmentOne, $shipmentThree], $matches->getShipments(false));
    }

    public function testFilterByReferenceIdPrefix(): void
    {
        $shipmentOne = (new Shipment())->setReferenceIdentifier('group-A-001');
        $shipmentTwo = (new Shipment())->setReferenceIdentifier('group-A-002');
        $shipmentThree = (new Shipment())->setReferenceIdentifier('group-B-001');
        $shipmentFour = new Shipment(); // null reference

        $collection = new ShipmentCollection([$shipmentOne, $shipmentTwo, $shipmentThree, $shipmentFour]);
        $matches = $collection->filterByReferenceIdPrefix('group-A-');

        self::assertInstanceOf(ShipmentCollection::class, $matches);
        self::assertCount(2, $matches);
        self::assertSame([$shipmentOne, $shipmentTwo], $matches->getShipments(false));
    }

    public function testClear(): void
    {
        $collection = new ShipmentCollection([new Shipment(), new Shipment()]);

        self::assertSame(2, $collection->count());

        $collection->clear();

        self::assertSame(0, $collection->count());
        self::assertSame([], $collection->getShipments());
    }
}
