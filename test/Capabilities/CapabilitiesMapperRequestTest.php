<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Capabilities;

use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesMapper;
use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesRequest;
use MyParcelNL\Sdk\Model\Capabilities\Enum\Carrier as CarrierEnum;
use MyParcelNL\Sdk\Model\Capabilities\Enum\DeliveryType as DeliveryEnum;
use MyParcelNL\Sdk\Model\Capabilities\Enum\Direction as DirectionEnum;
use MyParcelNL\Sdk\Model\Capabilities\Enum\PackageType as PackageEnum;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

final class CapabilitiesMapperRequestTest extends TestCase
{
    public function testMapToCoreApiSetsAllProvidedFields(): void
    {
        $req = CapabilitiesRequest::forCountry('NL')
            ->withShopId(42)
            ->withDeliveryType(DeliveryEnum::STANDARD_DELIVERY)
            ->withCarrier(CarrierEnum::POSTNL)
            ->withPackageType(PackageEnum::PACKAGE)
            ->withDirection(DirectionEnum::OUTBOUND);

        $mapper  = new CapabilitiesMapper();
        $coreReq = $mapper->mapToCoreApi($req);

        $this->assertSame('NL', $coreReq->getRecipient()->getCountryCode());
        $this->assertSame(42, $coreReq->getShopId());
        $this->assertSame(DeliveryEnum::STANDARD_DELIVERY, $coreReq->getDeliveryType());
        $this->assertSame(CarrierEnum::POSTNL, $coreReq->getCarrier());
        $this->assertSame(PackageEnum::PACKAGE, $coreReq->getPackageType());
        $this->assertSame(DirectionEnum::OUTBOUND, $coreReq->getDirection());
    }

    public function testMapToCoreApiAllowsMinimalPayload(): void
    {
        $req = CapabilitiesRequest::forCountry('BE');

        $mapper  = new CapabilitiesMapper();
        $coreReq = $mapper->mapToCoreApi($req);

        $this->assertSame('BE', $coreReq->getRecipient()->getCountryCode());
        $this->assertNull($coreReq->getShopId());
        $this->assertNull($coreReq->getDeliveryType());
        $this->assertNull($coreReq->getCarrier());
        $this->assertNull($coreReq->getPackageType());
        $this->assertNull($coreReq->getDirection());
    }

    public function testFriendlyInputsAreNormalized(): void
    {
        $req = CapabilitiesRequest::forCountry('NL')
            ->withDeliveryType('standard')
            ->withPackageType('package')
            ->withCarrier('post-nl')
            ->withDirection('outbound');

        $mapper  = new CapabilitiesMapper();
        $coreReq = $mapper->mapToCoreApi($req);

        $this->assertSame('NL', $coreReq->getRecipient()->getCountryCode());
        $this->assertSame(DeliveryEnum::STANDARD_DELIVERY, $coreReq->getDeliveryType());
        $this->assertSame(PackageEnum::PACKAGE, $coreReq->getPackageType());
        $this->assertSame(CarrierEnum::POSTNL, $coreReq->getCarrier());
        $this->assertSame(DirectionEnum::OUTBOUND, $coreReq->getDirection());
    }
}
