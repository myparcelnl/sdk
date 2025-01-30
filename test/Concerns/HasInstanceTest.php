<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Concerns;

use MyParcelNL\Sdk\Concerns\HasInstance;
use PHPUnit\Framework\TestCase;

class HasInstanceTest extends TestCase
{
    public function testGetInstance(): void
    {
        ClassWithInstance::getInstance('test');

        self::assertEquals(
            'test',
            ClassWithInstance::getInstance('other value')
                ->getValue()
        );
    }
}

class ClassWithInstance
{
    use HasInstance;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @param  mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
