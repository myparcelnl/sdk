<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Client\Generated\EcommerceApi;

use MyParcelNL\Sdk\Client\Generated\EcommerceApi\Model\FixedWebhookOrderResult;
use MyParcelNL\Sdk\Client\Generated\EcommerceApi\Model\WebhookOrdersPost202ResponseInner;
use MyParcelNL\Sdk\Client\Generated\EcommerceApi\ObjectSerializer;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

/**
 * POST /webhook/orders answers one of three branches per order. The generator flattens them into
 * one model that no accepted item can satisfy, so this override restores them.
 */
final class FixedWebhookOrderResultTest extends TestCase
{
    public function testAnAcceptedOrderIsJudgedOnItsStatusAlone(): void
    {
        // The flattened parent demands type, title, detail, instance and errors, which this branch
        // does not carry, so it would refuse every accepted order.
        $result = self::deserialize(['status' => 202]);

        self::assertInstanceOf(WebhookOrdersPost202ResponseInner::class, $result);
        self::assertSame(202, $result->getStatus());
        self::assertSame([], $result->listInvalidProperties());
        self::assertTrue($result->valid());
    }

    public function testAClientProblemStillValidates(): void
    {
        $result = self::deserialize([
            'status'   => 400,
            'type'     => 'urn:problem:client',
            'title'    => 'Bad Request',
            'detail'   => 'order 3 is not shippable',
            'instance' => 'urn:trace-id:abc',
            'errors'   => [['detail' => 'missing', 'pointer' => '/orders/3/shipping']],
        ]);

        self::assertSame(400, $result->getStatus());
        self::assertTrue($result->valid());
    }

    public function testAServerProblemStillValidates(): void
    {
        // The parent's status enum kept only this branch's 500, which is why the other two failed.
        $result = self::deserialize([
            'status'   => 500,
            'type'     => 'urn:problem:server',
            'title'    => 'Server Error',
            'detail'   => 'something broke',
            'instance' => 'urn:trace-id:def',
            'errors'   => [['detail' => 'boom']],
        ]);

        self::assertSame(500, $result->getStatus());
        self::assertTrue($result->valid());
    }

    public function testTheStatusEnumCoversEveryBranch(): void
    {
        self::assertSame([202, 400, 500], (new FixedWebhookOrderResult())->getStatusAllowableValues());
    }

    public function testAStatusNoBranchDeclaresIsRefused(): void
    {
        $result = self::deserialize(['status' => 418]);

        self::assertFalse($result->valid());
        self::assertStringContainsString(
            "must be one of '202', '400', '500'",
            $result->listInvalidProperties()[0]
        );
    }

    public function testAnItemWithoutAStatusIsRefused(): void
    {
        $result = self::deserialize([]);

        self::assertFalse($result->valid());
        self::assertContains("'status' can't be null", $result->listInvalidProperties());
    }

    public function testTheStatusIsAnIntegerNotAFloat(): void
    {
        // type: number in the spec generated a float, so 202 !== 202.0 broke strict comparison.
        $result = self::deserialize(['status' => 202]);

        self::assertIsInt($result->getStatus());
        self::assertSame(FixedWebhookOrderResult::STATUS_ACCEPTED, $result->getStatus());
    }

    /**
     * Through the real serializer, which is how these arrive.
     *
     * @param array<string, mixed> $body
     */
    private static function deserialize(array $body): FixedWebhookOrderResult
    {
        return ObjectSerializer::deserialize(
            (object) json_decode((string) json_encode($body)),
            FixedWebhookOrderResult::class,
            []
        );
    }
}
