<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Bootstrap;

use DateTime;
use Faker\Factory;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use MyParcelNL\Sdk\Helper\MyParcelCurl;
use MyParcelNL\Sdk\Model\MyParcelRequest;
use MyParcelNL\Sdk\Support\Arr;
use RuntimeException;

class TestCase extends \PHPUnit\Framework\TestCase
{
    use MockeryPHPUnitIntegration;
    protected const ENV_API_KEY_BE  = 'API_KEY_BE';
    protected const ENV_API_KEY_NL  = 'API_KEY_NL';
    protected const ENV_CI          = 'CI';
    protected const ENV_TEST_ORDERS = 'TEST_ORDERS';

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
    protected function setUp(): void
    {
        parent::setUp();
        // Reset factory to ensure clean state between tests
        MyParcelRequest::setCurlFactory(null);
        // Mockery integration handles mock cleanup automatically
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
     * @param  string $name
     *
     * @return string|null
     * @throws \Exception
     */
    public function getEnvironmentVariable(string $name): ?string
    {
        $value = $_ENV[$name] ?? null;

        if (null === $value) {
            throw new RuntimeException(
                "Environment variable '$name' is missing. Add it up in the env file at '/.env' and try again."
            );
        }

        return (string) $value;
    }

    /**
     * Compares two arrays but ignores order of keys.
     *
     * @param  array       $array1
     * @param  array       $array2
     * @param  string|null $message
     */
    protected static function assertArraySame(array $array1, array $array2, string $message = null): void
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
     * @param  string $key
     *
     * @return string|null
     * @throws \Exception
     */
    protected function getApiKey(string $key = self::ENV_API_KEY_NL): ?string
    {
        return $this->getEnvironmentVariable($key);
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
}
