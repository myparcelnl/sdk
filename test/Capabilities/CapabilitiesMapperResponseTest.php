<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Capabilities;

use MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\CapabilitiesResponsesCapabilitiesV2;
use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesMapper;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use Mockery;

final class CapabilitiesMapperResponseTest extends TestCase
{
    public function testMapFromCoreApiMergesAndDedupes(): void
    {
        $fake1 = Mockery::mock();
        $fake1->shouldReceive('getPackageTypes')->andReturn(['package', 'mailbox']);
        $fake1->shouldReceive('getDeliveryTypes')->andReturn(['standard', 'evening']);
        $fake1->shouldReceive('getOptions')->andReturn((object) ['signature' => true, 'only_recipient' => false]);
        $fake1->shouldReceive('getCarrier')->andReturn('POSTNL');
        $fake1->shouldReceive('getTransactionTypes')->andReturn(['B2C']);
        $collo1 = Mockery::mock(\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\RefCapabilitiesResponseCollo::class);
        $collo1->shouldReceive('getMax')->andReturn(3);
        $fake1->shouldReceive('getCollo')->andReturn($collo1);

        $fake2 = Mockery::mock();
        $fake2->shouldReceive('getPackageTypes')->andReturn(['package']);
        $fake2->shouldReceive('getDeliveryTypes')->andReturn(['standard', 'morning']);
        $fake2->shouldReceive('getOptions')->andReturn((object) ['only_recipient' => true, 'receipt_code' => true]);
        $fake2->shouldReceive('getCarrier')->andReturn('POSTNL');
        $fake2->shouldReceive('getTransactionTypes')->andReturn(['B2B']);
        $collo2 = Mockery::mock(\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\RefCapabilitiesResponseCollo::class);
        $collo2->shouldReceive('getMax')->andReturn(5);
        $fake2->shouldReceive('getCollo')->andReturn($collo2);

        $core = Mockery::mock(CapabilitiesResponsesCapabilitiesV2::class);
        $core->shouldReceive('getResults')->andReturn([$fake1, $fake2]);

        $mapper = new CapabilitiesMapper();
        $res    = $mapper->mapFromCoreApi($core);

        $this->assertSame(['package', 'mailbox'], $res->getPackageTypes());
        $this->assertEqualsCanonicalizing(['standard', 'evening', 'morning'], $res->getDeliveryTypes());
        $this->assertEqualsCanonicalizing(['signature', 'only_recipient', 'receipt_code'], $res->getShipmentOptions());
        $this->assertSame('POSTNL', $res->getCarrier());
        $this->assertEqualsCanonicalizing(['B2C', 'B2B'], $res->getTransactionTypes());
        $this->assertSame(5, $res->getColloMax());
    }

    public function testMapFromCoreApiSetsCarrierNullWhenInconsistent(): void
    {
        $fake1 = Mockery::mock();
        $fake1->shouldReceive('getPackageTypes')->andReturn([]);
        $fake1->shouldReceive('getDeliveryTypes')->andReturn([]);
        $fake1->shouldReceive('getOptions')->andReturn((object) []);
        $fake1->shouldReceive('getCarrier')->andReturn('POSTNL');
        $fake1->shouldReceive('getTransactionTypes')->andReturn([]);
        $fake1->shouldReceive('getCollo')->andReturnNull();

        $fake2 = Mockery::mock();
        $fake2->shouldReceive('getPackageTypes')->andReturn([]);
        $fake2->shouldReceive('getDeliveryTypes')->andReturn([]);
        $fake2->shouldReceive('getOptions')->andReturn((object) []);
        $fake2->shouldReceive('getCarrier')->andReturn('DHL');
        $fake2->shouldReceive('getTransactionTypes')->andReturn([]);
        $fake2->shouldReceive('getCollo')->andReturnNull();

        $core = Mockery::mock(CapabilitiesResponsesCapabilitiesV2::class);
        $core->shouldReceive('getResults')->andReturn([$fake1, $fake2]);

        $mapper = new CapabilitiesMapper();
        $res    = $mapper->mapFromCoreApi($core);

        $this->assertNull($res->getCarrier());
    }

    public function testMapFromCoreApiCarrierStaysNullAfterInconsistency(): void
    {
        $fake1 = Mockery::mock();
        $fake1->shouldReceive('getPackageTypes')->andReturn([]);
        $fake1->shouldReceive('getDeliveryTypes')->andReturn([]);
        $fake1->shouldReceive('getOptions')->andReturn((object) []);
        $fake1->shouldReceive('getCarrier')->andReturn('POSTNL');
        $fake1->shouldReceive('getTransactionTypes')->andReturn([]);
        $fake1->shouldReceive('getCollo')->andReturnNull();

        $fake2 = Mockery::mock();
        $fake2->shouldReceive('getPackageTypes')->andReturn([]);
        $fake2->shouldReceive('getDeliveryTypes')->andReturn([]);
        $fake2->shouldReceive('getOptions')->andReturn((object) []);
        $fake2->shouldReceive('getCarrier')->andReturn('DHL');
        $fake2->shouldReceive('getTransactionTypes')->andReturn([]);
        $fake2->shouldReceive('getCollo')->andReturnNull();

        $fake3 = Mockery::mock();
        $fake3->shouldReceive('getPackageTypes')->andReturn([]);
        $fake3->shouldReceive('getDeliveryTypes')->andReturn([]);
        $fake3->shouldReceive('getOptions')->andReturn((object) []);
        $fake3->shouldReceive('getCarrier')->andReturn('UPS');
        $fake3->shouldReceive('getTransactionTypes')->andReturn([]);
        $fake3->shouldReceive('getCollo')->andReturnNull();

        $core = Mockery::mock(CapabilitiesResponsesCapabilitiesV2::class);
        $core->shouldReceive('getResults')->andReturn([$fake1, $fake2, $fake3]);

        $mapper = new CapabilitiesMapper();
        $res    = $mapper->mapFromCoreApi($core);

        $this->assertNull($res->getCarrier());
    }

    public function testMapFromCoreApiHandlesNullOptions(): void
    {
        $fake = Mockery::mock();
        $fake->shouldReceive('getPackageTypes')->andReturn(['package']);
        $fake->shouldReceive('getDeliveryTypes')->andReturn(['standard']);
        $fake->shouldReceive('getOptions')->andReturnNull();
        $fake->shouldReceive('getCarrier')->andReturn('POSTNL');
        $fake->shouldReceive('getTransactionTypes')->andReturn(['B2C']);
        $fake->shouldReceive('getCollo')->andReturnNull();

        $core = Mockery::mock(CapabilitiesResponsesCapabilitiesV2::class);
        $core->shouldReceive('getResults')->andReturn([$fake]);

        $mapper = new CapabilitiesMapper();
        $res    = $mapper->mapFromCoreApi($core);

        $this->assertSame(['package'], $res->getPackageTypes());
        $this->assertSame(['standard'], $res->getDeliveryTypes());
        $this->assertSame([], $res->getShipmentOptions());
    }

    public function testMapFromCoreApiHandlesNullLists(): void
    {
        $fake = Mockery::mock();
        $fake->shouldReceive('getPackageTypes')->andReturnNull();
        $fake->shouldReceive('getDeliveryTypes')->andReturnNull();
        $fake->shouldReceive('getOptions')->andReturn((object) []);
        $fake->shouldReceive('getCarrier')->andReturn('POSTNL');
        $fake->shouldReceive('getTransactionTypes')->andReturnNull();
        $fake->shouldReceive('getCollo')->andReturnNull();

        $core = Mockery::mock(CapabilitiesResponsesCapabilitiesV2::class);
        $core->shouldReceive('getResults')->andReturn([$fake]);

        $mapper = new CapabilitiesMapper();
        $res    = $mapper->mapFromCoreApi($core);

        $this->assertSame([], $res->getPackageTypes());
        $this->assertSame([], $res->getDeliveryTypes());
        $this->assertSame([], $res->getTransactionTypes());
    }

    public function testMapFromCoreApiEmptyOrNullResults(): void
    {
        $core1 = Mockery::mock(CapabilitiesResponsesCapabilitiesV2::class);
        $core1->shouldReceive('getResults')->andReturn([]);

        $mapper = new CapabilitiesMapper();
        $res1   = $mapper->mapFromCoreApi($core1);

        $this->assertSame([], $res1->getPackageTypes());
        $this->assertSame([], $res1->getDeliveryTypes());
        $this->assertSame([], $res1->getShipmentOptions());
        $this->assertSame([], $res1->getTransactionTypes());
        $this->assertNull($res1->getCarrier());
        $this->assertNull($res1->getColloMax());

        $core2 = Mockery::mock(CapabilitiesResponsesCapabilitiesV2::class);
        $core2->shouldReceive('getResults')->andReturnNull();

        $res2 = $mapper->mapFromCoreApi($core2);

        $this->assertSame([], $res2->getPackageTypes());
        $this->assertSame([], $res2->getDeliveryTypes());
        $this->assertSame([], $res2->getShipmentOptions());
        $this->assertSame([], $res2->getTransactionTypes());
        $this->assertNull($res2->getCarrier());
        $this->assertNull($res2->getColloMax());
    }
}
