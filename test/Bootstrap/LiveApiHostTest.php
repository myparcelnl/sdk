<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Bootstrap;

use PHPUnit\Framework\SkippedTestError;

/**
 * The live smoke tests create data in an account, so liveApiHost() must skip them unless a developer
 * asked for them, on a known test host. These tests send no request.
 */
final class LiveApiHostTest extends TestCase
{
    private const VARIABLES = [self::ENV_CI, self::ENV_LIVE_TESTS, self::ENV_LIVE_API_HOST];

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

    /**
     * @dataProvider provideTestHosts
     */
    public function testReturnsTheHostWhenADeveloperAsksForTheLiveTests(string $url): void
    {
        putenv('MYPARCEL_LIVE_TESTS=true');
        putenv('MYPARCEL_API_BASE_URL=' . $url);

        self::assertSame($url, self::liveApiHost());
    }

    /**
     * @return array<string, array{string}>
     */
    public static function provideTestHosts(): array
    {
        return [
            'acceptance'                   => ['https://api.acceptance.myparcel.nl'],
            'testing'                      => ['https://api.testing.myparcel.nl'],
            'upper case and a trailing dot' => ['https://API.Acceptance.MyParcel.nl.'],
        ];
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
            'only a host'                        => [['MYPARCEL_API_BASE_URL' => $acceptance], 'only on request'],
            'no host'                            => [['MYPARCEL_LIVE_TESTS' => '1'], 'test host'],
            'the production host'                => [['MYPARCEL_LIVE_TESTS' => '1', 'MYPARCEL_API_BASE_URL' => 'https://api.myparcel.nl'], 'test host'],
            'the production host in upper case'  => [['MYPARCEL_LIVE_TESTS' => '1', 'MYPARCEL_API_BASE_URL' => 'https://API.myparcel.nl'], 'test host'],
            'the production host with a dot'     => [['MYPARCEL_LIVE_TESTS' => '1', 'MYPARCEL_API_BASE_URL' => 'https://api.myparcel.nl.'], 'test host'],
            'another production host'            => [['MYPARCEL_LIVE_TESTS' => '1', 'MYPARCEL_API_BASE_URL' => 'https://backoffice.myparcel.nl'], 'test host'],
            'a production host without a scheme' => [['MYPARCEL_LIVE_TESTS' => '1', 'MYPARCEL_API_BASE_URL' => 'api.myparcel.nl'], 'test host'],
            'a test host without a scheme'       => [['MYPARCEL_LIVE_TESTS' => '1', 'MYPARCEL_API_BASE_URL' => 'api.acceptance.myparcel.nl'], 'test host'],
            'a test host over plain http'        => [['MYPARCEL_LIVE_TESTS' => '1', 'MYPARCEL_API_BASE_URL' => 'http://api.acceptance.myparcel.nl'], 'test host'],
            'a host that only contains the name' => [['MYPARCEL_LIVE_TESTS' => '1', 'MYPARCEL_API_BASE_URL' => 'https://api.acceptance.myparcel.nl.example.com'], 'test host'],
            'continuous integration'             => [['CI' => 'true', 'MYPARCEL_LIVE_TESTS' => '1', 'MYPARCEL_API_BASE_URL' => $acceptance], 'continuous integration'],
        ];
    }
}
