<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Client\Generated\CoreApi;

use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\FixedShipmentGeneralSettingsTracktrace;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefShipmentGeneralSettings;
use MyParcelNL\Sdk\Client\Generated\CoreApi\ObjectSerializer;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

final class FixedShipmentGeneralSettingsTracktraceTest extends TestCase
{
    public function testFromAddressEmailAcceptsRealAccountEmail(): void
    {
        $tracktrace = new FixedShipmentGeneralSettingsTracktrace();
        $tracktrace->setFromAddressEmail('freek.vanrijt+test@myparcel.nl');

        self::assertSame('freek.vanrijt+test@myparcel.nl', $tracktrace->getFromAddressEmail());
        self::assertSame([], $tracktrace->listInvalidProperties());
    }

    public function testGeneralSettingsDeserializesTracktraceIntoFixedModel(): void
    {
        $settings = ObjectSerializer::deserialize(
            json_encode([
                'tracktrace' => [
                    'from_address_email' => 'freek.vanrijt+test@myparcel.nl',
                ],
            ], JSON_THROW_ON_ERROR),
            RefShipmentGeneralSettings::class,
            []
        );

        self::assertInstanceOf(RefShipmentGeneralSettings::class, $settings);
        self::assertInstanceOf(FixedShipmentGeneralSettingsTracktrace::class, $settings->getTracktrace());
        self::assertSame('freek.vanrijt+test@myparcel.nl', $settings->getTracktrace()->getFromAddressEmail());
    }
}
