<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Support;

use MyParcelNL\Sdk\Support\Collection;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class CollectionMethodsTest extends TestCase
{
    public function testAllReturnsArray(): void
    {
        $c = new Collection([1, 2, 3]);
        $this->assertSame([1, 2, 3], $c->all());
    }

    public function testCountReturnsSize(): void
    {
        $this->assertSame(3, (new Collection([1, 2, 3]))->count());
        $this->assertTrue((new Collection())->isEmpty());
        $this->assertTrue((new Collection([1]))->isNotEmpty());
    }

    public function testFirstAndLast(): void
    {
        $c = new Collection([10, 20, 30]);
        $this->assertSame(10, $c->first());
        $this->assertSame(30, $c->last());
        $this->assertSame(20, $c->first(fn($v) => $v > 10));
        $this->assertSame('default', (new Collection())->first(null, 'default'));
    }

    public function testMapAndEach(): void
    {
        $c = new Collection([1, 2, 3]);
        $mapped = $c->map(fn($v) => $v * 2);
        $this->assertSame([2, 4, 6], $mapped->all());

        $sum = 0;
        $c->each(function ($v) use (&$sum) { $sum += $v; });
        $this->assertSame(6, $sum);
    }

    public function testFilterAndReject(): void
    {
        $c = new Collection([1, 2, 3, 4, 5]);
        $this->assertSame([2, 4], $c->filter(fn($v) => $v % 2 === 0)->values()->all());
        $this->assertSame([1, 3, 5], $c->reject(fn($v) => $v % 2 === 0)->values()->all());
    }

    public function testReduceAndSum(): void
    {
        $c = new Collection([1, 2, 3]);
        $this->assertSame(6, $c->reduce(fn($carry, $v) => $carry + $v, 0));
        $this->assertSame(6, $c->sum());
    }

    public function testPluckAndKeyBy(): void
    {
        $c = new Collection([
            ['id' => 1, 'name' => 'A'],
            ['id' => 2, 'name' => 'B'],
        ]);
        $this->assertSame(['A', 'B'], $c->pluck('name')->all());
        $keyed = $c->keyBy('id');
        $this->assertSame('A', $keyed->get(1)['name']);
    }

    public function testContainsAndSearch(): void
    {
        $c = new Collection([1, 2, 3]);
        $this->assertTrue($c->contains(2));
        $this->assertFalse($c->contains(5));
        $this->assertSame(1, $c->search(2));
    }

    public function testUniqueAndDiff(): void
    {
        $this->assertSame([1, 2, 3], (new Collection([1, 2, 2, 3, 3]))->unique()->values()->all());
        $this->assertSame([1], (new Collection([1, 2, 3]))->diff(new Collection([2, 3]))->values()->all());
    }

    public function testSortAndReverse(): void
    {
        $c = new Collection([3, 1, 2]);
        $this->assertSame([1, 2, 3], $c->sort()->values()->all());
        $this->assertSame([2, 1, 3], $c->reverse()->values()->all());
    }

    public function testMergeAndPush(): void
    {
        $c = new Collection([1, 2]);
        $merged = $c->merge([3, 4]);
        $this->assertSame([1, 2, 3, 4], $merged->all());

        $pushed = $c->push(3);
        $this->assertSame(3, $pushed->count());
    }

    public function testSliceAndChunk(): void
    {
        $c = new Collection([1, 2, 3, 4, 5]);
        $this->assertSame([3, 4, 5], $c->slice(2)->values()->all());
        $chunks = $c->chunk(2);
        $this->assertSame(3, $chunks->count());
    }

    public function testFlattenAndCollapse(): void
    {
        $c = new Collection([[1, 2], [3, 4]]);
        $this->assertSame([1, 2, 3, 4], $c->flatten()->all());
        $this->assertSame([1, 2, 3, 4], $c->collapse()->all());
    }

    public function testWhereAndFirstWhere(): void
    {
        $c = new Collection([
            ['status' => 'active', 'name' => 'A'],
            ['status' => 'inactive', 'name' => 'B'],
            ['status' => 'active', 'name' => 'C'],
        ]);
        $this->assertSame(2, $c->where('status', 'active')->count());
        $this->assertSame('A', $c->firstWhere('status', 'active')['name']);
    }

    public function testGetAndPut(): void
    {
        $c = new Collection(['a' => 1, 'b' => 2]);
        $this->assertSame(1, $c->get('a'));
        $this->assertSame('default', $c->get('z', 'default'));
        $c->put('c', 3);
        $this->assertSame(3, $c->get('c'));
    }

    public function testForgetAndExcept(): void
    {
        $c = new Collection(['a' => 1, 'b' => 2, 'c' => 3]);
        $c->forget('b');
        $this->assertNull($c->get('b'));
        $this->assertSame(['a' => 1], $c->except(['c'])->all());
    }

    public function testOnlyAndKeys(): void
    {
        $c = new Collection(['a' => 1, 'b' => 2, 'c' => 3]);
        $this->assertSame(['a' => 1, 'c' => 3], $c->only(['a', 'c'])->all());
        $this->assertSame(['a', 'b', 'c'], $c->keys()->all());
    }

    public function testMinMaxAvgMedian(): void
    {
        $c = new Collection([1, 3, 5, 7]);
        $this->assertSame(1, $c->min());
        $this->assertSame(7, $c->max());
        $this->assertEquals(4, $c->avg());
        $this->assertEquals(4, $c->median());
    }

    public function testFlatMapAndMapWithKeys(): void
    {
        $c = new Collection([1, 2, 3]);
        $flat = $c->flatMap(fn($v) => [$v, $v * 10]);
        $this->assertSame([1, 10, 2, 20, 3, 30], $flat->all());

        $keyed = $c->mapWithKeys(fn($v) => ['key_' . $v => $v * 2]);
        $this->assertSame(['key_1' => 2, 'key_2' => 4, 'key_3' => 6], $keyed->all());
    }

    public function testZipAndCombine(): void
    {
        $c = new Collection([1, 2, 3]);
        $zipped = $c->zip(['a', 'b', 'c']);
        $this->assertSame(3, $zipped->count());

        $combined = (new Collection(['a', 'b']))->combine([1, 2]);
        $this->assertSame(['a' => 1, 'b' => 2], $combined->all());
    }

    public function testGroupBy(): void
    {
        $c = new Collection([
            ['type' => 'a', 'v' => 1],
            ['type' => 'b', 'v' => 2],
            ['type' => 'a', 'v' => 3],
        ]);
        $grouped = $c->groupBy('type');
        $this->assertSame(2, $grouped->count());
        $this->assertSame(2, $grouped->get('a')->count());
    }

    public function testSortByAndSortByDesc(): void
    {
        $c = new Collection([
            ['n' => 3], ['n' => 1], ['n' => 2],
        ]);
        $this->assertSame(1, $c->sortBy('n')->first()['n']);
        $this->assertSame(3, $c->sortByDesc('n')->first()['n']);
    }

    public function testTake(): void
    {
        $c = new Collection([1, 2, 3, 4, 5]);
        $this->assertSame([1, 2], $c->take(2)->all());
    }

    public function testImplodeAndJoin(): void
    {
        $c = new Collection(['a', 'b', 'c']);
        $this->assertSame('a, b, c', $c->implode(', '));
    }

    public function testEveryAndPipe(): void
    {
        $c = new Collection([2, 4, 6]);
        $this->assertTrue($c->every(fn($v) => $v % 2 === 0));
        $this->assertSame(12, $c->pipe(fn($col) => $col->sum()));
    }

    public function testTapAndWhen(): void
    {
        $tapped = null;
        $c = new Collection([1, 2, 3]);
        $c->tap(function ($col) use (&$tapped) { $tapped = $col->count(); });
        $this->assertSame(3, $tapped);

        $result = $c->when(true, fn($col) => $col->push(4));
        $this->assertSame(4, $result->count());

        $result2 = $c->when(false, fn($col) => $col->push(5));
        $this->assertSame(4, $result2->count());
    }

    public function testUnlessAndTransform(): void
    {
        $c = new Collection([1, 2]);
        $result = $c->unless(false, fn($col) => $col->push(3));
        $this->assertSame(3, $result->count());
    }

    public function testPadAndPop(): void
    {
        $c = new Collection([1, 2]);
        $padded = $c->pad(4, 0);
        $this->assertSame([1, 2, 0, 0], $padded->all());

        $c2 = new Collection([1, 2, 3]);
        $this->assertSame(3, $c2->pop());
        $this->assertSame(2, $c2->count());
    }

    public function testPrependAndShift(): void
    {
        $c = new Collection([2, 3]);
        $c->prepend(1);
        $this->assertSame([1, 2, 3], $c->all());
        $this->assertSame(1, $c->shift());
    }

    public function testMapInto(): void
    {
        $c = new Collection([1, 2, 3]);
        $mapped = $c->mapInto(Collection::class);
        $this->assertInstanceOf(Collection::class, $mapped->first());
    }

    public function testNth(): void
    {
        $c = new Collection([1, 2, 3, 4, 5, 6]);
        $this->assertSame([1, 3, 5], $c->nth(2)->all());
    }

    public function testWhereNotIn(): void
    {
        $c = new Collection([
            ['id' => 1], ['id' => 2], ['id' => 3],
        ]);
        $this->assertSame(1, $c->whereNotIn('id', [2, 3])->count());
    }

    public function testCrossJoin(): void
    {
        $c = new Collection([1, 2]);
        $result = $c->crossJoin(['a', 'b']);
        $this->assertSame(4, $result->count());
    }

    public function testTimesAndMake(): void
    {
        $c = Collection::times(3, fn($i) => $i * 2);
        $this->assertSame([2, 4, 6], $c->all());

        $c2 = Collection::make([1, 2]);
        $this->assertSame([1, 2], $c2->all());
    }

    public function testToJson(): void
    {
        $c = new Collection(['a' => 'b']);
        $this->assertSame('{"a":"b"}', $c->toJson());
    }

    public function testSplice(): void
    {
        $c = new Collection([1, 2, 3, 4, 5]);
        $spliced = $c->splice(1, 2);
        $this->assertSame([2, 3], $spliced->all());
        $this->assertSame([1, 4, 5], $c->all());
    }

    public function testRandom(): void
    {
        $c = new Collection([1, 2, 3, 4, 5]);
        $this->assertTrue($c->contains($c->random()));
    }

    public function testFlip(): void
    {
        $c = new Collection(['a' => 1, 'b' => 2]);
        $this->assertSame([1 => 'a', 2 => 'b'], $c->flip()->all());
    }

    public function testWrap(): void
    {
        $this->assertSame([1], Collection::wrap(1)->all());
        $this->assertSame([1, 2], Collection::wrap([1, 2])->all());
    }

    public function testMode(): void
    {
        $c = new Collection([1, 2, 2, 3]);
        $this->assertSame([2], $c->mode());
    }

    public function testPartition(): void
    {
        $c = new Collection([1, 2, 3, 4]);
        [$even, $odd] = $c->partition(fn($v) => $v % 2 === 0);
        $this->assertSame([2, 4], $even->values()->all());
        $this->assertSame([1, 3], $odd->values()->all());
    }
}
