<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Bootstrap;

use PHPUnit\Framework\SkippedTestError;

/**
 * The live smoke tests create data in an account, so liveApiHost() must skip them unless a developer
 * asked for them, with a host that is not the production host. These tests send no request.
 */
final class LiveApiHostTest extends TestCase
{
    private const VARIABLES = ['CI', 'MYPARCEL_LIVE_TESTS', 'MYPARCEL_API_BASE_URL'];

    /**
     * @var array<string, string|false>
     */
    private array $previous = [];

    protected function setUp(): void
    {
        parent::setUp();

        foreach (self::VARIABLES as $name) {
            $this->previous[$name] = getenv($name);
            putenv($name);
        }
    }

    protected function tearDown(): void
    {
        foreach ($this->previous as $name => $value) {
            putenv(false === $value ? $name : $name . '=' . $value);
        }

        parent::tearDown();
    }

    public function testReturnsTheHostWhenADeveloperAsksForTheLiveTests(): void
    {
        putenv('MYPARCEL_LIVE_TESTS=1');
        putenv('MYPARCEL_API_BASE_URL=https://api.acceptance.myparcel.nl');

        self::assertSame('https://api.acceptance.myparcel.nl', self::liveApiHost());
    }

    /**
     * @dataProvider provideEnvironmentsThatSkip
     *
     * @param array<string, string> $environment
     */
    public function testSkipsTheLiveTests(array $environment, string $message): void
    {
        foreach ($environment as $name => $value) {
            putenv($name . '=' . $value);
        }

        try {
            self::liveApiHost();
        } catch (SkippedTestError $skipped) {
            self::assertStringContainsString($message, $skipped->getMessage());

            return;
        }

        self::fail('liveApiHost() must skip the test.');
    }

    /**
     * @return array<string, array{array<string, string>, string}>
     */
    public static function provideEnvironmentsThatSkip(): array
    {
        $acceptance = 'https://api.acceptance.myparcel.nl';

        return [
            'only a host'             => [['MYPARCEL_API_BASE_URL' => $acceptance], 'only on request'],
            'no host'                 => [['MYPARCEL_LIVE_TESTS' => '1'], 'Set MYPARCEL_API_BASE_URL'],
            'the production host'     => [['MYPARCEL_LIVE_TESTS' => '1', 'MYPARCEL_API_BASE_URL' => 'https://api.myparcel.nl'], 'production host'],
            'continuous integration'  => [['CI' => 'true', 'MYPARCEL_LIVE_TESTS' => '1', 'MYPARCEL_API_BASE_URL' => $acceptance], 'continuous integration'],
        ];
    }
}
