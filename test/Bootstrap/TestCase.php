<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Bootstrap;

use DateTime;
use Faker\Factory;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use MyParcelNL\Sdk\Client\Jane\JaneClientFactory;
use MyParcelNL\Sdk\Helper\MyParcelCurl;
use MyParcelNL\Sdk\Model\MyParcelRequest;
use MyParcelNL\Sdk\Support\Arr;
use RuntimeException;

class TestCase extends \PHPUnit\Framework\TestCase
{
    use MockeryPHPUnitIntegration;
    protected const ENV_CI            = 'CI';
    protected const ENV_TEST_ORDERS   = 'TEST_ORDERS';
    protected const ENV_LIVE_API_HOST = 'MYPARCEL_API_BASE_URL';
    protected const ENV_LIVE_TESTS    = 'MYPARCEL_LIVE_TESTS';

    /**
     * @var \Faker\Factory
     */
    protected $faker;

    /**
     * @param  string $name
     * @param  array  $data
     * @param  string $dataName
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        $this->faker = Factory::create('nl_NL');
        parent::__construct($name, $data, $dataName);
    }

    /**
     * Set up for each test
     */
    // test/Bootstrap/TestCase.php
    protected function setUp(): void
    {
        parent::setUp();
        MyParcelRequest::setCurlFactory(static function () {
            throw new \RuntimeException('No Curl mock provided. Call $this->mockCurl() in your test.');
        });
    }

    
    /**
     * Create a mock of MyParcelCurl and inject it into MyParcelRequest
     * 
     * @return \Mockery\MockInterface|MyParcelCurl
     */
    protected function mockCurl()
    {
        $mock = Mockery::mock(MyParcelCurl::class);
        MyParcelRequest::setCurlFactory(fn() => $mock);
        
        return $mock;
    }

    /**
     * Compares two arrays but ignores order of keys.
     *
     * @param  array       $array1
     * @param  array       $array2
     * @param  string|null $message
     */
    protected static function assertArraySame(array $array1, array $array2, ?string $message = null): void
    {
        ksort($array1);
        ksort($array2);

        self::assertSame($array1, $array2, $message);
    }

    /**
     * @param  string $key
     *
     * @return string
     */
    protected static function createMessage(string $key): string
    {
        return sprintf('Result of "%s" did not match expected value.', $key);
    }

    /**
     * Formats provider datasets for easier usage.
     * - Merges given data sets into default values (if available). This allows values to be omitted and entered in
     * any arbitrary order.
     * - If the array is not an associative array already, add the first value as name to each data set for a more
     * readable test output.
     * - Wrap inner array into another array, so it can be used as a single parameter.
     *
     * @param  array[] $datasets
     *
     * @return array[]
     */
    protected function createProviderDataset(array $datasets, array $defaults = []): array
    {
        $newDatasets   = [];
        $defaultsArray = [];

        foreach ($datasets as $key => $data) {
            $datasetKey = $key;

            if (! is_string($key)) {
                $datasetKey = array_values($data)[0] ?? '';
            }

            $wrapArray                    = empty($data) || Arr::isAssoc($data);
            $newDatasets[$datasetKey]     = $wrapArray ? [$data] : $data;
            $defaultsArray[$datasetKey][] = $defaults;
        }

        return array_replace_recursive($defaultsArray, $newDatasets);
    }

    /**
     * @return string
     */
    protected function generateTimestamp(): string
    {
        return (new DateTime())->format('YmdHis');
    }

    /**
     * @return string|null
     * @throws \Exception
     */
    protected function getApiKey(): ?string
    {
        return 'mock_api_key';
    }

    /**
     * @return void
     * @deprecated Don't use this, fix the actual test instead.
     */
    protected static function markTestBroken(): void
    {
        if (! getenv(self::ENV_CI)) {
            return;
        }

        self::markTestSkipped(
            'Test skipped: It is currently broken and a continuous integration environment was detected.'
        );
    }

    /**
     * @param  string      $env
     * @param  null|string $reason
     *
     * @return void
     */
    protected static function skipUnlessEnabled(string $env, string $reason): void
    {
        if (getenv($env)) {
            return;
        }

        self::markTestSkipped("Test skipped: $reason\n\nSet environment variable '$env=true' to override.");
    }

    /**
     * Return the API host for a live smoke test, or skip the test when no developer asked for it.
     *
     * @return string The value of `MYPARCEL_API_BASE_URL`.
     */
    protected static function liveApiHost(): string
    {
        if (getenv(self::ENV_CI)) {
            self::markTestSkipped('Live smoke tests never run in a continuous integration environment.');
        }

        if ('1' !== getenv(self::ENV_LIVE_TESTS)) {
            self::markTestSkipped('Live smoke tests run only on request: set ' . self::ENV_LIVE_TESTS . '=1.');
        }

        $host = (string) getenv(self::ENV_LIVE_API_HOST);

        if ('' === $host) {
            self::markTestSkipped('Set ' . self::ENV_LIVE_API_HOST . ' to the host for the live smoke tests, such as https://api.acceptance.myparcel.nl.');
        }

        // The smoke tests create data in the account of the API key, so they never use production.
        if (parse_url($host, PHP_URL_HOST) === parse_url(JaneClientFactory::CORE_API_HOST, PHP_URL_HOST)) {
            self::markTestSkipped('Live smoke tests do not run on the production host ' . JaneClientFactory::CORE_API_HOST . '.');
        }

        return $host;
    }
}
