<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Capabilities;

use MyParcel\CoreApi\Generated\Shipments\Model\CapabilitiesResponsesCapabilitiesV2;
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
        $collo1 = Mockery::mock(\MyParcel\CoreApi\Generated\Shipments\Model\RefCapabilitiesResponseCollo::class);
        $collo1->shouldReceive('getMax')->andReturn(3);
        $fake1->shouldReceive('getCollo')->andReturn($collo1);

        $fake2 = Mockery::mock();
        $fake2->shouldReceive('getPackageTypes')->andReturn(['package']);
        $fake2->shouldReceive('getDeliveryTypes')->andReturn(['standard', 'morning']);
        $fake2->shouldReceive('getOptions')->andReturn((object) ['only_recipient' => true, 'receipt_code' => true]);
        $fake2->shouldReceive('getCarrier')->andReturn('POSTNL');
        $fake2->shouldReceive('getTransactionTypes')->andReturn(['B2B']);
        $collo2 = Mockery::mock(\MyParcel\CoreApi\Generated\Shipments\Model\RefCapabilitiesResponseCollo::class);
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
        // Result 1: POSTNL
        $fake1 = Mockery::mock();
        $fake1->shouldReceive('getPackageTypes')->andReturn([]);
        $fake1->shouldReceive('getDeliveryTypes')->andReturn([]);
        $fake1->shouldReceive('getOptions')->andReturn((object) []);
        $fake1->shouldReceive('getCarrier')->andReturn('POSTNL');
        $fake1->shouldReceive('getTransactionTypes')->andReturn([]);
        $fake1->shouldReceive('getCollo')->andReturnNull();

        // Result 2: DHL (different from POSTNL, so carrier becomes null)
        $fake2 = Mockery::mock();
        $fake2->shouldReceive('getPackageTypes')->andReturn([]);
        $fake2->shouldReceive('getDeliveryTypes')->andReturn([]);
        $fake2->shouldReceive('getOptions')->andReturn((object) []);
        $fake2->shouldReceive('getCarrier')->andReturn('DHL');
        $fake2->shouldReceive('getTransactionTypes')->andReturn([]);
        $fake2->shouldReceive('getCollo')->andReturnNull();

        // Result 3: UPS (should NOT override null, bug scenario)
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

        // Carrier should be null, NOT 'UPS' (the bug Joeri found)
        $this->assertNull($res->getCarrier(), 'Carrier should stay null after inconsistency, not be overwritten by subsequent results');
    }

    public function testMapFromCoreApiEmptyResults(): void
    {
        $core = Mockery::mock(CapabilitiesResponsesCapabilitiesV2::class);
        $core->shouldReceive('getResults')->andReturn([]);

        $mapper = new CapabilitiesMapper();
        $res    = $mapper->mapFromCoreApi($core);

        $this->assertSame([], $res->getPackageTypes());
        $this->assertSame([], $res->getDeliveryTypes());
        $this->assertSame([], $res->getShipmentOptions());
        $this->assertSame([], $res->getTransactionTypes());
        $this->assertNull($res->getCarrier());
        $this->assertNull($res->getColloMax());
    }
}
