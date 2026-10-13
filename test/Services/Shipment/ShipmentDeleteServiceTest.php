<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Shipment;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Api\ShipmentApi;
use MyParcelNL\Sdk\Services\Shipment\ShipmentDeleteService;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

final class ShipmentDeleteServiceTest extends TestCase
{
    public function testDeleteManyReturnsEarlyOnEmptyIds(): void
    {
        $api = $this->createMock(ShipmentApi::class);
        $api->expects(self::never())
            ->method('deleteShipments');

        $service = new ShipmentDeleteService($this->getApiKey(), $api);
        $service->deleteMany([]);
    }

    public function testDeleteManyCallsGeneratedDeleteEndpoint(): void
    {
        $api = $this->createMock(ShipmentApi::class);
        $api->expects(self::once())
            ->method('deleteShipments')
            ->with(
                self::identicalTo('10;20'),
                self::isType('string')
            );

        $service = new ShipmentDeleteService($this->getApiKey(), $api);
        $service->deleteMany([10, 20]);
    }
}

