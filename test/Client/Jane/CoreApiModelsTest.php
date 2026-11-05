<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Client\Jane;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipment;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\JaneObjectNormalizer;
use MyParcelNL\Sdk\Client\Jane\CoreApiModels;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
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

    public function testToArrayLeavesOutAListThatTheApiSentAsNull(): void
    {
        $shipment = $this->shipment(['id' => 1, 'shipped_items' => null]);

        self::assertNull($shipment->shippedItems);
        self::assertArrayNotHasKey('shipped_items', CoreApiModels::toArray($shipment));
    }

    /**
     * CoreApiModels::withoutNullLists() exists because the generated normalizer iterates over a nullable
     * list that is null. When this test fails, Jane skips such a list: remove that method.
     */
    public function testJaneStillIteratesOverANullList(): void
    {
        $model               = new ShipmentDefsShipment();
        $model->shippedItems = null;
        $warning             = null;
        set_error_handler(static function (int $level, string $message) use (&$warning): bool {
            $warning = $message;

            return true;
        });

        try {
            $this->serializer()->normalize($model, 'json', ['skip_validation' => true]);
        } finally {
            restore_error_handler();
        }

        self::assertStringContainsString(
            'foreach() argument must be of type array|object, null given',
            (string) $warning,
            'Jane now skips a null list. Remove CoreApiModels::withoutNullLists().'
        );
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
}
