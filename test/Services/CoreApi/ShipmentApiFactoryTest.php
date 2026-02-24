<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\CoreApi;

use MyParcelNL\Sdk\Services\CoreApi\ShipmentApiFactory;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use ReflectionMethod;

final class ShipmentApiFactoryTest extends TestCase
{
    public function testNormalizeShipmentRequestPayloadCastsNumericEnumStrings(): void
    {
        $payload = [
            'data' => [
                'shipments' => [
                    [
                        'carrier' => '1',
                        'options' => [
                            'package_type' => '2',
                        ],
                    ],
                ],
            ],
        ];

        $normalized = $this->invokeFactoryNormalizer('normalizeShipmentRequestPayload', $payload);

        self::assertIsInt($normalized['data']['shipments'][0]['carrier']);
        self::assertSame(1, $normalized['data']['shipments'][0]['carrier']);
        self::assertIsInt($normalized['data']['shipments'][0]['options']['package_type']);
        self::assertSame(2, $normalized['data']['shipments'][0]['options']['package_type']);
    }

    public function testNormalizeTrackTraceResponsePayloadKeepsStableFieldsOnly(): void
    {
        $payload = [
            'data' => [
                'tracktraces' => [
                    [
                        'shipment_id' => 123,
                        'carrier' => 1,
                        'code' => 'delivered',
                        'description' => 'Delivered',
                        'time' => '2026-02-24T09:00:00+00:00',
                        'link_consumer_portal' => 'https://consumer',
                        'link_tracktrace' => 'https://track',
                        'delayed' => false,
                        'returnable' => true,
                        // Known problematic nested structures for generated deserializer.
                        'recipient' => ['email' => 'user@example.com', 'street' => 'Main'],
                        'sender' => ['email' => 'sender@example.com'],
                        'options' => ['package_type' => 1],
                    ],
                ],
            ],
        ];

        $normalized = $this->invokeFactoryNormalizer('normalizeTrackTraceResponsePayload', $payload);
        $trackTrace = $normalized['data']['tracktraces'][0];

        self::assertSame(
            [
                'shipment_id',
                'carrier',
                'code',
                'description',
                'time',
                'link_consumer_portal',
                'link_tracktrace',
                'delayed',
                'returnable',
            ],
            array_keys($trackTrace)
        );
        self::assertIsString($trackTrace['carrier']);
        self::assertSame('1', $trackTrace['carrier']);
    }

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    private function invokeFactoryNormalizer(string $method, array $payload): array
    {
        $reflection = new ReflectionMethod(ShipmentApiFactory::class, $method);
        $reflection->setAccessible(true);

        /** @var array<string, mixed> $normalized */
        $normalized = $reflection->invoke(null, $payload);

        return $normalized;
    }
}
