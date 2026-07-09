<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Client;

use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentDefsShipmentSender;
use MyParcelNL\Sdk\Client\Generated\CoreApi\ObjectSerializer;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

/**
 * openapi-generator collapses spec fields shaped like
 * `anyOf(<string> | enum[null, ""])` (e.g. email fields) into a pseudo-enum whose
 * only allowable value is "". These are not real enums and must not be enforced on
 * write, either inline or via a referenced enum class (scalar or array).
 */
class PseudoEnumSerializationTest extends TestCase
{
    private const REAL_EMAIL = 'real@example.com';

    public function testInlinePseudoEnumSerializesRealValue(): void
    {
        $sender = (new ShipmentDefsShipmentSender())->setEmail(self::REAL_EMAIL);

        $serialized = ObjectSerializer::sanitizeForSerialization($sender);

        self::assertSame(self::REAL_EMAIL, $serialized->email);
    }

    public function testInlinePseudoEnumIsNotReportedInvalid(): void
    {
        // valid() may still be false for unrelated required fields; assert only that
        // the pseudo-enum email value is not itself reported as an invalid property.
        $sender = (new ShipmentDefsShipmentSender())->setEmail(self::REAL_EMAIL);

        $emailErrors = array_filter(
            $sender->listInvalidProperties(),
            static fn(string $message): bool => false !== strpos($message, "for 'email'")
        );

        self::assertSame([], array_values($emailErrors));
    }

    public function testReferencedScalarPseudoEnumSerializesRealValue(): void
    {
        $sender = (new ShipmentDefsShipmentSender())->setBillingEmail(self::REAL_EMAIL);

        $serialized = ObjectSerializer::sanitizeForSerialization($sender);

        self::assertSame(self::REAL_EMAIL, $serialized->billing_email);
    }

    public function testReferencedArrayPseudoEnumSerializesRealValue(): void
    {
        $sender = (new ShipmentDefsShipmentSender())->setSecondaryEmails([self::REAL_EMAIL]);

        $serialized = ObjectSerializer::sanitizeForSerialization($sender);

        self::assertSame([self::REAL_EMAIL], $serialized->secondary_emails);
    }
}
