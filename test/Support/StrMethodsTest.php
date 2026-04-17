<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Support;

use MyParcelNL\Sdk\Support\Str;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class StrMethodsTest extends TestCase
{
    public function testAfter(): void
    {
        $this->assertSame('world', Str::after('hello world', 'hello '));
        $this->assertSame('hello', Str::after('hello', 'missing'));
    }

    public function testBefore(): void
    {
        $this->assertSame('hello', Str::before('hello world', ' world'));
    }

    public function testContains(): void
    {
        $this->assertTrue(Str::contains('hello world', 'world'));
        $this->assertFalse(Str::contains('hello world', 'xyz'));
    }

    public function testStartsWith(): void
    {
        $this->assertTrue(Str::startsWith('hello', 'hel'));
        $this->assertFalse(Str::startsWith('hello', 'xyz'));
    }

    public function testEndsWith(): void
    {
        $this->assertTrue(Str::endsWith('hello', 'llo'));
        $this->assertFalse(Str::endsWith('hello', 'xyz'));
    }

    public function testCamelAndSnake(): void
    {
        $this->assertSame('fooBar', Str::camel('foo_bar'));
        $this->assertSame('foo_bar', Str::snake('fooBar'));
        $this->assertSame('foo_bar', Str::snake('FooBar'));
    }

    public function testStudly(): void
    {
        $this->assertSame('FooBar', Str::studly('foo_bar'));
    }

    public function testTitle(): void
    {
        $this->assertSame('Hello World', Str::title('hello world'));
    }

    public function testKebab(): void
    {
        $this->assertSame('foo-bar', Str::kebab('fooBar'));
    }

    public function testLength(): void
    {
        $this->assertSame(5, Str::length('hello'));
    }

    public function testLimit(): void
    {
        $this->assertSame('hel...', Str::limit('hello world', 6));
        $this->assertSame('hello', Str::limit('hello', 10));
    }

    public function testUpper(): void
    {
        $this->assertSame('HELLO', Str::upper('hello'));
    }

    public function testLower(): void
    {
        $this->assertSame('hello', Str::lower('HELLO'));
    }

    public function testSubstr(): void
    {
        $this->assertSame('llo', Str::substr('hello', 2));
        $this->assertSame('el', Str::substr('hello', 1, 2));
    }

    public function testUcfirst(): void
    {
        $this->assertSame('Hello', Str::ucfirst('hello'));
    }

    public function testReplaceFirst(): void
    {
        $this->assertSame('xbc', Str::replaceFirst('a', 'x', 'abc'));
    }

    public function testReplaceLast(): void
    {
        $this->assertSame('abx', Str::replaceLast('c', 'x', 'abc'));
    }

    public function testStart(): void
    {
        $this->assertSame('/path', Str::start('path', '/'));
        $this->assertSame('/path', Str::start('/path', '/'));
    }

    public function testFinish(): void
    {
        $this->assertSame('path/', Str::finish('path', '/'));
        $this->assertSame('path/', Str::finish('path/', '/'));
    }

    public function testIs(): void
    {
        $this->assertTrue(Str::is('foo*', 'foobar'));
        $this->assertFalse(Str::is('bar*', 'foobar'));
    }

    public function testRandom(): void
    {
        $this->assertSame(16, strlen(Str::random(16)));
    }
}
