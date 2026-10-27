<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Client\Jane;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentCustomsDeclaration;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItem;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemRecipient;
use MyParcelNL\Sdk\Client\Jane\CoreApiModels;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use ReflectionProperty;

final class CoreApiModelsTest extends TestCase
{
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
