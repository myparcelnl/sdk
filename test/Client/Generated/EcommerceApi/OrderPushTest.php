<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Client\Generated\EcommerceApi;

use DateTime;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use MyParcelNL\Sdk\Client\Generated\EcommerceApi\Api\DefaultApi;
use MyParcelNL\Sdk\Client\Generated\EcommerceApi\Model\Order;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

/**
 * POST /webhook/orders refuses an order without a status. These tests check the generated client
 * sends it.
 */
final class OrderPushTest extends TestCase
{
    public function testAnOrderPushSendsTheOrderStatus(): void
    {
        $answers = new MockHandler([
            new Response(202, ['Content-Type' => 'application/json'], '[{"status":202}]'),
        ]);

        $api = new DefaultApi(new GuzzleClient(['handler' => HandlerStack::create($answers)]));

        $api->webhookOrdersPost([
            new Order([
                'ordered_at' => new DateTime('2026-11-02T10:00:00+01:00'),
                'source_id'  => 'order-1',
                'status'     => Order::STATUS_OPEN,
            ]),
        ]);

        $body = json_decode((string) $answers->getLastRequest()->getBody(), true);
        self::assertSame('OPEN', $body[0]['status']);
    }

    public function testAnOrderWithoutAStatusIsInvalid(): void
    {
        // The model knows the field is required, so an integrator who calls listInvalidProperties()
        // finds the missing status before the API refuses the order.
        $order = new Order([
            'ordered_at' => new DateTime('2026-11-02T10:00:00+01:00'),
            'source_id'  => 'order-1',
        ]);

        self::assertContains("'status' can't be null", $order->listInvalidProperties());
    }
}
