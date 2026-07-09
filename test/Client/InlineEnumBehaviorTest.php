<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Client;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\OrderApi\Model\ContactMultiEmailBusiness;
use MyParcelNL\Sdk\Client\Generated\OrderApi\ObjectSerializer;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

/**
 * Inline enums (property typed as scalar, validation historically in the setter)
 * must behave like referenced enums: raw passthrough on read, strict on write.
 */
class InlineEnumBehaviorTest extends TestCase
{
    private const CONTACT = ContactMultiEmailBusiness::class;

    public function testUnknownInlineEnumValuePassesThroughAsRawStringOnRead(): void
    {
        $contact = ObjectSerializer::deserialize('{"type":"FOOTYPE"}', self::CONTACT);

        // The exact value from the response — not the sentinel, not coerced.
        self::assertSame('FOOTYPE', $contact->getType());
    }

    public function testKnownInlineEnumValueStillReadsCorrectly(): void
    {
        $contact = ObjectSerializer::deserialize('{"type":"BUSINESS"}', self::CONTACT);

        self::assertSame('BUSINESS', $contact->getType());
    }

    public function testUnknownInlineEnumValueThrowsWhenBuildingARequest(): void
    {
        $contact = new ContactMultiEmailBusiness();
        $contact->setType('FOOTYPE');

        $this->expectException(InvalidArgumentException::class);
        ObjectSerializer::sanitizeForSerialization($contact);
    }
}
