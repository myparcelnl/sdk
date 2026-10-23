<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Client;

use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentResponsesPostShipmentsV12;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentResponsesPutShipmentsV12;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentResponsesShipments;
use MyParcelNL\Sdk\Client\Generated\CoreApi\ObjectSerializer;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

/**
 * The spec defines the shipment region as `oneOf(pattern ^[A-Z]{2}$ | enum ["Wereld"])`.
 * A shipment read from the API must encode to JSON with every region that the spec allows.
 */
class ShipmentRegionSerializationTest extends TestCase
{
    /**
     * @return array<string, array{class-string, string}>
     */
    public function provideResponsesAndRegions(): array
    {
        $responses = [
            ShipmentResponsesShipments::class,
            ShipmentResponsesPostShipmentsV12::class,
            ShipmentResponsesPutShipmentsV12::class,
        ];
        $cases = [];

        foreach ($responses as $response) {
            foreach (['EU', 'NL', 'BE', 'Wereld'] as $region) {
                $cases[substr(strrchr($response, '\\'), 1) . " $region"] = [$response, $region];
            }
        }

        return $cases;
    }

    /**
     * @dataProvider provideResponsesAndRegions
     *
     * @param class-string $responseClass
     */
    public function testFetchedShipmentEncodesRegionToJson(string $responseClass, string $region): void
    {
        $body = (object) ['data' => (object) ['shipments' => [(object) ['id' => 1, 'region' => $region]]]];

        $response = ObjectSerializer::deserialize($body, $responseClass);
        $shipment = $response->getData()->getShipments()[0];

        $json = json_decode(json_encode($shipment, JSON_THROW_ON_ERROR), true, 512, JSON_THROW_ON_ERROR);

        self::assertSame($region, $shipment->getRegion());
        self::assertSame($region, $json['region']);
    }
}
