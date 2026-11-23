<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Client\Jane;

use DateTimeImmutable;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\Order;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\OrderStatus;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\JaneObjectNormalizer;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\Normalizer\ValidationException;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use Symfony\Component\Serializer\Serializer;

/**
 * The e-commerce models check an order against the spec when it goes out.
 */
final class EcommerceApiModelsTest extends TestCase
{
    public function testAValidOrderGoesOutWithTheApiFieldNames(): void
    {
        self::assertSame(
            ['orderedAt' => '2026-11-23T10:00:00+01:00', 'sourceId' => 'order-1', 'status' => 'OPEN'],
            $this->normalize($this->order())
        );
    }

    public function testAnOrderWithoutARequiredFieldThrowsAValidationException(): void
    {
        $order = $this->order();
        unset($order->sourceId);

        try {
            $this->normalize($order);
            self::fail('The order went out without a sourceId.');
        } catch (ValidationException $exception) {
            self::assertSame('[sourceId]', $exception->getViolationList()[0]->getPropertyPath());
        }
    }

    public function testAnOrderWithAStatusThatTheSpecDoesNotHaveThrowsAValidationException(): void
    {
        $order = $this->order();
        $order->status = 'UNEXISTING-STATUS';

        try {
            $this->normalize($order);
            self::fail('The order went out with an unknown status.');
        } catch (ValidationException $exception) {
            self::assertSame('[status]', $exception->getViolationList()[0]->getPropertyPath());
        }
    }

    /**
     * An order with only the fields that the spec requires.
     */
    private function order(): Order
    {
        $order            = new Order();
        $order->sourceId  = 'order-1';
        $order->orderedAt = new DateTimeImmutable('2026-11-23T10:00:00+01:00');
        $order->status    = OrderStatus::Open;

        return $order;
    }

    /**
     * Turn the model into the array that the client sends, as the generated client does.
     *
     * @return array<string, mixed>
     */
    private function normalize(Order $order): array
    {
        return (new Serializer([new JaneObjectNormalizer()]))->normalize($order, 'json');
    }
}
