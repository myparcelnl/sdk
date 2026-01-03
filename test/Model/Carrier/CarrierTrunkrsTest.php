<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Carrier;

use MyParcelNL\Sdk\Model\Carrier\CarrierFactory;
use MyParcelNL\Sdk\Model\Carrier\CarrierTrunkrs;
use MyParcelNL\Sdk\Model\Consignment\TrunkrsConsignment;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class CarrierTrunkrsTest extends TestCase
{
    /**
     * Test that Trunkrs carrier can be created from ID
     *
     * @throws \Exception
     */
    public function testCreateFromId(): void
    {
        $carrier = CarrierFactory::createFromId(CarrierTrunkrs::ID);
        
        $this->assertInstanceOf(CarrierTrunkrs::class, $carrier);
        $this->assertEquals(16, $carrier->getId());
        $this->assertEquals('trunkrs', $carrier->getName());
        $this->assertEquals('Trunkrs', $carrier->getHuman());
    }
    
    /**
     * Test that Trunkrs carrier can be created from name
     *
     * @throws \Exception
     */
    public function testCreateFromName(): void
    {
        $carrier = CarrierFactory::createFromName('trunkrs');
        
        $this->assertInstanceOf(CarrierTrunkrs::class, $carrier);
        $this->assertEquals(16, $carrier->getId());
    }
    
    /**
     * Test that Trunkrs carrier has correct consignment class
     *
     * @throws \Exception
     */
    public function testConsignmentClass(): void
    {
        $carrier = CarrierFactory::createFromId(CarrierTrunkrs::ID);
        
        $this->assertEquals(TrunkrsConsignment::class, $carrier->getConsignmentClass());
    }
}
