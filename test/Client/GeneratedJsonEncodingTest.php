<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Client;

use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

final class GeneratedJsonEncodingTest extends TestCase
{
    public function testGeneratedClientsDoNotUseDeprecatedGuzzleJsonEncoder(): void
    {
        $apiFiles = glob(dirname(__DIR__, 2) . '/src/Client/Generated/*/Api/*.php') ?: [];

        self::assertNotEmpty($apiFiles);

        $deprecatedFiles = array_filter(
            $apiFiles,
            static fn(string $file): bool => false !== strpos(
                (string) file_get_contents($file),
                '\\GuzzleHttp\\Utils::jsonEncode'
            )
        );

        self::assertSame([], array_values($deprecatedFiles));
    }

    public function testGeneratedClientsFailOnInvalidAsyncJsonResponses(): void
    {
        $apiFiles = glob(dirname(__DIR__, 2) . '/src/Client/Generated/*/Api/*.php') ?: [];

        self::assertNotEmpty($apiFiles);

        $unsafeFiles = array_filter(
            $apiFiles,
            static fn(string $file): bool => false !== strpos(
                (string) file_get_contents($file),
                '$content = json_decode($content);'
            )
        );

        self::assertSame([], array_values($unsafeFiles));
    }
}
