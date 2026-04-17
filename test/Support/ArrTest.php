<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Support;

use MyParcelNL\Sdk\Support\Arr;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class ArrTest extends TestCase
{
    public function testAccessible(): void
    {
        $this->assertTrue(Arr::accessible([]));
        $this->assertFalse(Arr::accessible('string'));
    }

    public function testExists(): void
    {
        $this->assertTrue(Arr::exists(['a' => 1], 'a'));
        $this->assertFalse(Arr::exists(['a' => 1], 'b'));
    }

    public function testGet(): void
    {
        $this->assertSame(1, Arr::get(['a' => ['b' => 1]], 'a.b'));
        $this->assertSame('default', Arr::get([], 'x', 'default'));
        $this->assertSame(['a' => 1], Arr::get(['a' => 1], null));
    }

    public function testSet(): void
    {
        $arr = [];
        Arr::set($arr, 'a.b', 1);
        $this->assertSame(['a' => ['b' => 1]], $arr);
    }

    public function testHas(): void
    {
        $this->assertTrue(Arr::has(['a' => ['b' => 1]], 'a.b'));
        $this->assertFalse(Arr::has(['a' => 1], 'b'));
        $this->assertFalse(Arr::has([], null));
    }

    public function testFirst(): void
    {
        $this->assertSame(2, Arr::first([1, 2, 3], fn($v) => $v > 1));
        $this->assertSame(1, Arr::first([1, 2, 3]));
        $this->assertSame('default', Arr::first([], null, 'default'));
    }

    public function testLast(): void
    {
        $this->assertSame(3, Arr::last([1, 2, 3]));
        $this->assertSame(2, Arr::last([1, 2, 3], fn($v) => $v < 3));
    }

    public function testFlatten(): void
    {
        $this->assertSame([1, 2, 3, 4], Arr::flatten([[1, 2], [3, 4]]));
        $this->assertSame([1, [2, 3], 4], Arr::flatten([[1, [2, 3]], [4]], 1));
    }

    public function testOnly(): void
    {
        $this->assertSame(['a' => 1], Arr::only(['a' => 1, 'b' => 2], ['a']));
    }

    public function testExcept(): void
    {
        $this->assertSame(['b' => 2], Arr::except(['a' => 1, 'b' => 2], ['a']));
    }

    public function testPluck(): void
    {
        $data = [['id' => 1, 'name' => 'A'], ['id' => 2, 'name' => 'B']];
        $this->assertSame(['A', 'B'], Arr::pluck($data, 'name'));
        $this->assertSame([1 => 'A', 2 => 'B'], Arr::pluck($data, 'name', 'id'));
    }

    public function testWhere(): void
    {
        $data = [1, 2, 3, 4];
        $this->assertSame([1 => 2, 3 => 4], Arr::where($data, fn($v) => $v % 2 === 0));
    }

    public function testForget(): void
    {
        $arr = ['a' => 1, 'b' => ['c' => 2]];
        Arr::forget($arr, 'b.c');
        $this->assertSame(['a' => 1, 'b' => []], $arr);
    }

    public function testPull(): void
    {
        $arr = ['a' => 1, 'b' => 2];
        $this->assertSame(1, Arr::pull($arr, 'a'));
        $this->assertSame(['b' => 2], $arr);
    }

    public function testAdd(): void
    {
        $this->assertSame(['a' => 1, 'b' => 2], Arr::add(['a' => 1], 'b', 2));
        $this->assertSame(['a' => 1], Arr::add(['a' => 1], 'a', 2));
    }

    public function testCollapse(): void
    {
        $this->assertSame([1, 2, 3, 4], Arr::collapse([[1, 2], [3, 4]]));
    }

    public function testDot(): void
    {
        $this->assertSame(['a.b' => 1], Arr::dot(['a' => ['b' => 1]]));
    }

    public function testWrap(): void
    {
        $this->assertSame([1], Arr::wrap(1));
        $this->assertSame([1, 2], Arr::wrap([1, 2]));
        $this->assertSame([], Arr::wrap(null));
    }

    public function testSortRecursive(): void
    {
        $sorted = Arr::sortRecursive(['b' => 2, 'a' => 1]);
        $this->assertSame(['a' => 1, 'b' => 2], $sorted);
    }

    public function testShuffle(): void
    {
        $arr = [1, 2, 3, 4, 5];
        $shuffled = Arr::shuffle($arr);
        $this->assertCount(5, $shuffled);
    }

    public function testRandom(): void
    {
        $arr = [1, 2, 3, 4, 5];
        $this->assertContains(Arr::random($arr), $arr);
    }

    public function testIsAssoc(): void
    {
        $this->assertTrue(Arr::isAssoc(['a' => 1]));
        $this->assertFalse(Arr::isAssoc([1, 2, 3]));
    }

    public function testPrepend(): void
    {
        $this->assertSame([0, 1, 2], Arr::prepend([1, 2], 0));
        $this->assertSame(['z' => 0, 'a' => 1], Arr::prepend(['a' => 1], 0, 'z'));
    }

    public function testCrossJoin(): void
    {
        $result = Arr::crossJoin([1, 2], ['a', 'b']);
        $this->assertCount(4, $result);
    }
}
