<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Concerns;

use Exception;
use MyParcelNL\Sdk\src\Concerns\HasApiKey;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class HasApiKeyTest extends TestCase
{
    /**
     * @throws \ReflectionException
     */
    public function testEnsureHasApiKey(): void
    {
        /**
         * @var HasApiKey $trait
         */
        $trait = $this->getObjectForTrait(HasApiKey::class);

        $this->expectException(Exception::class);
        $trait->ensureHasApiKey();

        $trait->setApiKey('an_api_key');
        self::assertEquals('an_api_key', $trait->ensureHasApiKey());
    }

    /**
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function testGetEncodedApiKey(): void
    {
        /**
         * @var HasApiKey $trait
         */
        $trait = $this->getObjectForTrait(HasApiKey::class);
        $trait->setApiKey('my_unencoded_api_key');

        self::assertEquals('bXlfdW5lbmNvZGVkX2FwaV9rZXk=', $trait->getEncodedApiKey());
    }

    /**
     * @throws \ReflectionException
     */
    public function testSetApiKey(): void
    {
        /**
         * @var HasApiKey $trait
         */
        $trait = $this->getObjectForTrait(HasApiKey::class);
        $trait->setApiKey('my_unencoded_api_key');

        self::assertEquals('my_unencoded_api_key', $trait->getApiKey());
    }
}
