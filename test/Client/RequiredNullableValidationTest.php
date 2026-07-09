<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Client;

use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\AccountDefsContact;
use MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Model\RefShippingRulesImplications;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

/**
 * A required+nullable property set to null is a legitimately valid state (see the
 * isNullableSetToNull handling). listInvalidProperties() must therefore skip the
 * numeric/length/pattern checks for such null values instead of coercing null to 0/""
 * (which produced false invalids and PHP 8.1+ deprecation notices).
 */
class RequiredNullableValidationTest extends TestCase
{
    public function testRequiredNullableNumericFieldIsNotReportedInvalidWhenNull(): void
    {
        $model = (new RefShippingRulesImplications())->setShippingRuleId(null);

        $errors = array_filter(
            $model->listInvalidProperties(),
            static fn(string $message): bool => false !== strpos($message, 'shipping_rule_id')
        );

        self::assertSame([], array_values($errors));
    }

    public function testRequiredNullableFieldDoesNotEmitDeprecationWhenNull(): void
    {
        $deprecations = [];
        set_error_handler(static function (int $errno, string $message) use (&$deprecations): bool {
            $deprecations[] = $message;
            return true;
        }, E_DEPRECATED);

        try {
            (new AccountDefsContact())->setLastName(null)->listInvalidProperties();
        } finally {
            restore_error_handler();
        }

        self::assertSame([], $deprecations);
    }
}
