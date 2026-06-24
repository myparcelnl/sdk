<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Client\Generated\CoreApi;

use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\FixedShipmentGeneralSettingsTracktrace;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefShipmentGeneralSettings;
use MyParcelNL\Sdk\Client\Generated\CoreApi\ObjectSerializer;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

final class FixedShipmentGeneralSettingsTracktraceTest extends TestCase
{
    private const SENDER_EMAIL = 'sender@example.test';

    public function testFromAddressEmailAcceptsRealAccountEmail(): void
    {
        $tracktrace = new FixedShipmentGeneralSettingsTracktrace();
        $tracktrace->setFromAddressEmail(self::SENDER_EMAIL);

        self::assertSame(self::SENDER_EMAIL, $tracktrace->getFromAddressEmail());
        self::assertSame([], $tracktrace->listInvalidProperties());
    }

    public function testGeneralSettingsDeserializesTracktraceIntoFixedModel(): void
    {
        $settings = ObjectSerializer::deserialize(
            json_encode([
                'tracktrace' => [
                    'from_address_email' => self::SENDER_EMAIL,
                ],
            ], JSON_THROW_ON_ERROR),
            RefShipmentGeneralSettings::class,
            []
        );

        self::assertInstanceOf(RefShipmentGeneralSettings::class, $settings);
        self::assertInstanceOf(FixedShipmentGeneralSettingsTracktrace::class, $settings->getTracktrace());
        self::assertSame(self::SENDER_EMAIL, $settings->getTracktrace()->getFromAddressEmail());
    }
}
