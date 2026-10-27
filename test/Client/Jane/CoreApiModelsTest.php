<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Client\Jane;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentCustomsDeclaration;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipment;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItem;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemRecipient;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\JaneObjectNormalizer;
use MyParcelNL\Sdk\Client\Jane\CoreApiModels;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use ReflectionProperty;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Serializer;

final class CoreApiModelsTest extends TestCase
{
    public function testToArrayGivesTheApiFieldNames(): void
    {
        $shipment = $this->shipment(['id' => 1, 'recipient' => ['cc' => 'NL', 'postal_code' => '2132JE']]);

        $array = CoreApiModels::toArray($shipment);

        self::assertSame(1, $array['id']);
        self::assertIsArray($array['recipient']);
        self::assertSame('NL', $array['recipient']['cc']);
        self::assertSame('2132JE', $array['recipient']['postal_code']);
        self::assertArrayNotHasKey('postalCode', $array['recipient']);
    }

    public function testToArrayLeavesOutAFieldThatTheApiDidNotSend(): void
    {
        self::assertArrayNotHasKey('barcode', CoreApiModels::toArray($this->shipment(['id' => 1])));
    }

    public function testToArrayKeepsAListThatTheApiSentAsNull(): void
    {
        $shipment = $this->shipment(['id' => 1, 'shipped_items' => null]);

        self::assertNull($shipment->shippedItems);
        self::assertArrayHasKey('shipped_items', CoreApiModels::toArray($shipment));
        self::assertNull(CoreApiModels::toArray($shipment)['shipped_items']);
    }

    /**
     * @param array<string, mixed> $data The shipment as the API sends it.
     */
    private function shipment(array $data): ShipmentDefsShipment
    {
        return $this->serializer()->denormalize($data, ShipmentDefsShipment::class, 'json', ['skip_validation' => true]);
    }

    private function serializer(): Serializer
    {
        return new Serializer([new ArrayDenormalizer(), new JaneObjectNormalizer()]);
    }

    public function testToModelTurnsAnArrayWithApiNamesIntoTheModel(): void
    {
        $recipient = CoreApiModels::toModel(['cc' => 'NL', 'postal_code' => '2132JE'], ShipmentPostShipmentsRequestV11DataShipmentsItemRecipient::class);

        self::assertSame('NL', $recipient->cc);
        self::assertSame('2132JE', $recipient->postalCode);
    }

    public function testToModelKeepsTheModelAndNull(): void
    {
        $recipient = new ShipmentPostShipmentsRequestV11DataShipmentsItemRecipient();

        self::assertSame($recipient, CoreApiModels::toModel($recipient, ShipmentPostShipmentsRequestV11DataShipmentsItemRecipient::class));
        self::assertNull(CoreApiModels::toModel(null, ShipmentPostShipmentsRequestV11DataShipmentsItemRecipient::class));
    }

    public function testToModelThrowsOnAnotherModel(): void
    {
        $this->expectException(InvalidArgumentException::class);

        CoreApiModels::toModel(new RefShipmentCustomsDeclaration(), ShipmentPostShipmentsRequestV11DataShipmentsItemRecipient::class);
    }

    public function testWithoutNullUntypedPropertiesRemovesTheUnsetEmailOfANestedModel(): void
    {
        $shipment            = new ShipmentPostShipmentsRequestV11DataShipmentsItem();
        $shipment->recipient = CoreApiModels::toModel(['cc' => 'NL'], ShipmentPostShipmentsRequestV11DataShipmentsItemRecipient::class);

        self::assertSame(['cc' => 'NL', 'email' => null], CoreApiModels::toArray($shipment->recipient));

        CoreApiModels::withoutNullUntypedProperties($shipment);

        self::assertSame(['cc' => 'NL'], CoreApiModels::toArray($shipment->recipient));
    }

    /**
     * CoreApiModels::withoutNullUntypedProperties() exists because Jane generates `email` without a type.
     * When this test fails, Jane gives it a type: check whether that method can go.
     */
    public function testJaneStillGeneratesTheRecipientEmailWithoutAType(): void
    {
        self::assertNull(
            (new ReflectionProperty(ShipmentPostShipmentsRequestV11DataShipmentsItemRecipient::class, 'email'))->getType(),
            'Jane now gives the recipient email a type. Check whether CoreApiModels::withoutNullUntypedProperties() can go.'
        );
    }
}
