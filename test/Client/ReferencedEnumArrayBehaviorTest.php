<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Client;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\OrderApi\Model\Carrier;
use MyParcelNL\Sdk\Client\Generated\OrderApi\Model\OrdersGetFilterParameter;
use MyParcelNL\Sdk\Client\Generated\OrderApi\ObjectSerializer;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

/**
 * Properties typed as an array of a referenced enum (e.g. Carrier[]) must be
 * validated element-wise on write, just like scalar referenced enums. Without
 * setter-side validation, the serialization path is the only enforcement point.
 */
class ReferencedEnumArrayBehaviorTest extends TestCase
{
    public function testKnownReferencedEnumArrayValueSerializesWithoutError(): void
    {
        $filter = (new OrdersGetFilterParameter())->setCarrier([Carrier::POSTNL]);

        $serialized = ObjectSerializer::sanitizeForSerialization($filter);

        self::assertSame([Carrier::POSTNL], $serialized->carrier);
    }

    public function testUnknownReferencedEnumArrayValueThrowsWhenBuildingARequest(): void
    {
        $filter = (new OrdersGetFilterParameter())->setCarrier(['NOT_A_CARRIER']);

        $this->expectException(InvalidArgumentException::class);
        ObjectSerializer::sanitizeForSerialization($filter);
    }
}
