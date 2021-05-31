<?php
declare(strict_types=1);

namespace Concerns;

use Exception;
use MyParcelNL\Sdk\src\Concerns\HasApiKey;
use PHPUnit\Framework\TestCase;

class HasApiKeyTest extends TestCase
{
    public function testGetEncodedApiKey(): void
    {
        /**
         * @var HasApiKey $trait
         */
        $trait = $this->getObjectForTrait(HasApiKey::class);
        $trait->setApiKey('my_unencoded_api_key');

        self::assertEquals('bXlfdW5lbmNvZGVkX2FwaV9rZXk=', $trait->getEncodedApiKey());
    }

    public function testSetApiKey(): void
    {
        /**
         * @var HasApiKey $trait
         */
        $trait = $this->getObjectForTrait(HasApiKey::class);
        $trait->setApiKey('my_unencoded_api_key');

        self::assertEquals('my_unencoded_api_key', $trait->getApiKey());
    }

    public function testEnsureHasApiKey()
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
}
