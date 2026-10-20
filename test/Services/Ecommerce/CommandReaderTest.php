<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Ecommerce;

use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\OrderShipmentsCommand;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\OrderShipmentsOrderStatus;
use MyParcelNL\Sdk\Exception\InvalidCommandException;
use MyParcelNL\Sdk\Exception\UnknownCommandException;
use MyParcelNL\Sdk\Services\Ecommerce\CommandReader;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

final class CommandReaderTest extends TestCase
{
    public function testAnOrderShipmentsCommandReadsAsItsOwnModel(): void
    {
        $command = (new CommandReader())->read(self::body());

        self::assertInstanceOf(OrderShipmentsCommand::class, $command);
        self::assertSame('6f1c2d3e-4a5b-4c6d-8e7f-0a1b2c3d4e5f', $command->commandId);
        self::assertSame('9c9e0d2c-2c0a-4bfa-97cf-8a4f42a0f001', $command->connectionId);
        self::assertSame('1001', $command->order->sourceId);
        self::assertSame(OrderShipmentsOrderStatus::PartiallyShipped, $command->order->status);
        self::assertSame('a3f1c0de-0000-4000-8000-000000000001', $command->shipment->shipmentId);
        self::assertSame('5001', $command->shipment->orderLines[0]->sourceId);
        self::assertSame(2, $command->shipment->orderLines[0]->quantity);
    }

    public function testAnUnknownEnumValueReadsAsTheRawValue(): void
    {
        // MyParcel can add a status before the SDK knows it. Reading must not fail on it.
        $command = (new CommandReader())->read(self::body(['order' => ['sourceId' => '1001', 'status' => 'RETURNED']]));

        self::assertInstanceOf(OrderShipmentsCommand::class, $command);
        self::assertSame('RETURNED', $command->order->status);
    }

    public function testAnUnknownTypeThrowsAndNamesTheType(): void
    {
        try {
            (new CommandReader())->read(self::body(['type' => 'ORDER_CANCELLED']));
            self::fail('expected an UnknownCommandException');
        } catch (UnknownCommandException $exception) {
            self::assertSame('ORDER_CANCELLED', $exception->getType());
        }
    }

    /**
     * @dataProvider provideInvalidBodies
     */
    public function testABodyThatIsNotACommandThrows(string $body): void
    {
        $this->expectException(InvalidCommandException::class);

        (new CommandReader())->read($body);
    }

    public function provideInvalidBodies(): array
    {
        return $this->createProviderDataset([
            'not json'                 => ['not json'],
            'a json string'            => ['"ORDER_SHIPMENTS"'],
            'a json list'              => ['[1, 2]'],
            'no type'                  => [self::body(['type' => null])],
            'a type that is no string' => [self::body(['type' => 42])],
            'a quantity that is no integer' => [
                self::body(['shipment' => ['shipmentId' => 's', 'orderLines' => [['sourceId' => '5001', 'quantity' => 'two']]]]),
            ],
        ]);
    }

    /**
     * The test body from the plugin contract, with one shipment. A null in $override removes the field.
     *
     * @param array<string, mixed> $override
     */
    private static function body(array $override = []): string
    {
        $command = array_merge([
            'type'         => 'ORDER_SHIPMENTS',
            'order'        => ['sourceId' => '1001', 'status' => 'PARTIALLY_SHIPPED'],
            'shipment'     => [
                'shipmentId' => 'a3f1c0de-0000-4000-8000-000000000001',
                'orderLines' => [['sourceId' => '5001', 'quantity' => 2]],
            ],
            'commandId'    => '6f1c2d3e-4a5b-4c6d-8e7f-0a1b2c3d4e5f',
            'connectionId' => '9c9e0d2c-2c0a-4bfa-97cf-8a4f42a0f001',
        ], $override);

        return (string) json_encode(array_filter($command, static fn ($value): bool => null !== $value));
    }
}
