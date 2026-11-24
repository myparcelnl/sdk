<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Client;

use DateTime;
use InvalidArgumentException;
use MyParcelNL\Sdk\Client\CoreApiModels;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefCapabilitiesResponseCapabilityV2;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentDefsShipment;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentResponsesDeliveryOptionsDeliveryOptionV1;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentResponsesDeliveryOptionsDeliveryTime;
use MyParcelNL\Sdk\Client\Generated\CoreApi\ObjectSerializer;
use MyParcelNL\Sdk\Client\Generated\IamApi\Model\PrincipalCommon;
use MyParcelNL\Sdk\Model\Shipment\Shipment;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use stdClass;

class CoreApiModelsTest extends TestCase
{
    public function testToArrayKeepsAnUnknownCarrier(): void
    {
        $capability = ObjectSerializer::deserialize(
            '{"carrier":"FOOCARRIER","packageTypes":["PACKAGE"]}',
            RefCapabilitiesResponseCapabilityV2::class
        );

        $array = CoreApiModels::toArray($capability);

        self::assertSame('FOOCARRIER', $array['carrier']);
    }

    public function testToArrayKeepsAnUnknownValueInAList(): void
    {
        $capability = ObjectSerializer::deserialize(
            '{"carrier":"POSTNL","packageTypes":["PACKAGE","FOOTYPE"],"deliveryTypes":["STANDARD_DELIVERY","FOO_DELIVERY"]}',
            RefCapabilitiesResponseCapabilityV2::class
        );

        $array = CoreApiModels::toArray($capability);

        self::assertSame(['PACKAGE', 'FOOTYPE'], $array['packageTypes']);
        self::assertSame(['STANDARD_DELIVERY', 'FOO_DELIVERY'], $array['deliveryTypes']);
    }

    public function testDeserializeReadsBackTheArrayOfAModelWithAnUnknownCarrier(): void
    {
        $capability = ObjectSerializer::deserialize(
            '{"carrier":"FOOCARRIER","packageTypes":["PACKAGE"],"options":{"requiresSignature":{"isRequired":false}}}',
            RefCapabilitiesResponseCapabilityV2::class
        );

        $stored = json_encode(CoreApiModels::toArray($capability));

        self::assertEquals(
            $capability,
            ObjectSerializer::deserialize(json_decode($stored), RefCapabilitiesResponseCapabilityV2::class)
        );
    }

    public function testToArrayGivesTheSameOutputAsTheSerializerForKnownValues(): void
    {
        $time = new ShipmentResponsesDeliveryOptionsDeliveryTime();
        $time->setStart('09:00:00');
        $time->setType(2);

        $deliveryOption = new ShipmentResponsesDeliveryOptionsDeliveryOptionV1();
        $deliveryOption->setDate(new DateTime('2026-11-24 10:00:00'));
        $deliveryOption->setTime([$time]);

        $shipment = new ShipmentDefsShipment();
        $shipment->setReferenceIdentifier(null);

        foreach ([$deliveryOption, $shipment] as $model) {
            self::assertSame(
                json_decode(json_encode(ObjectSerializer::sanitizeForSerialization($model)), true),
                CoreApiModels::toArray($model)
            );
        }
    }

    public function testToArrayKeepsAFieldThatIsSetToNull(): void
    {
        $shipment = new ShipmentDefsShipment();
        $shipment->setReferenceIdentifier(null);

        $array = CoreApiModels::toArray($shipment);

        self::assertArrayHasKey('reference_identifier', $array);
        self::assertNull($array['reference_identifier']);
    }

    public function testToArrayGivesANestedModelAsAnArray(): void
    {
        $deliveryOption = new ShipmentResponsesDeliveryOptionsDeliveryOptionV1();
        $deliveryOption->setDate(new DateTime('2026-11-24 10:00:00'));
        $deliveryOption->setTime([(new ShipmentResponsesDeliveryOptionsDeliveryTime())->setStart('09:00:00')]);

        self::assertSame(
            ['date' => '2026-11-24', 'time' => [['start' => '09:00:00']]],
            CoreApiModels::toArray($deliveryOption)
        );
    }

    public function testToArrayEncodesAHandWrittenSubclassOfAGeneratedModel(): void
    {
        $shipment = new Shipment();
        $shipment->setReferenceIdentifier('order-1');

        self::assertSame('order-1', CoreApiModels::toArray($shipment)['reference_identifier']);
    }

    public function testToArrayEncodesAModelOfAnotherClient(): void
    {
        $principal = new PrincipalCommon();
        $principal->setAccountId('1');
        $principal->setPlatform('FOO_PLATFORM');

        self::assertSame(
            [PrincipalCommon::attributeMap()['account_id'] => '1', PrincipalCommon::attributeMap()['platform'] => 'FOO_PLATFORM'],
            CoreApiModels::toArray($principal)
        );
    }

    public function testToArrayThrowsOnAnObjectThatIsNotAGeneratedModel(): void
    {
        $this->expectException(InvalidArgumentException::class);

        CoreApiModels::toArray(new stdClass());
    }
}
