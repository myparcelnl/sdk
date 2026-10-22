<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Connect;

use MyParcelNL\Sdk\Model\Connect\JwksCache;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

final class JwksCacheTest extends TestCase
{
    public function testRoundTripsThroughAnArray(): void
    {
        $record = new JwksCache(['keys' => [['kid' => 'a']]], 1791806400);

        $array = $record->toArray();

        self::assertSame(['version' => 1, 'jwks' => ['keys' => [['kid' => 'a']]], 'fetchedAt' => 1791806400], $array);
        self::assertEquals($record, JwksCache::fromArray($array));
    }

    public function testReadsAFetchedAtThatStorageTurnedIntoAString(): void
    {
        $record = JwksCache::fromArray(['version' => '1', 'jwks' => ['keys' => []], 'fetchedAt' => '1791806400']);

        self::assertSame(1791806400, $record->getFetchedAt());
    }

    /**
     * @dataProvider provideUnusableRows
     */
    public function testAnUnusableRowReadsAsAbsent(?array $row): void
    {
        self::assertNull(JwksCache::fromArray($row));
    }

    public function provideUnusableRows(): array
    {
        return [
            'nothing'        => [null],
            'empty'          => [[]],
            'newer version'  => [['version' => 2, 'jwks' => ['keys' => []], 'fetchedAt' => 1]],
            'no jwks'        => [['version' => 1, 'fetchedAt' => 1]],
            'jwks not array' => [['version' => 1, 'jwks' => '{}', 'fetchedAt' => 1]],
            'jwks no keys'   => [['version' => 1, 'jwks' => ['foo' => []], 'fetchedAt' => 1]],
            'no fetchedAt'   => [['version' => 1, 'jwks' => ['keys' => []]]],
        ];
    }
}
