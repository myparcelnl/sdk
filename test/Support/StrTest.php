<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Support;

use MyParcelNL\Sdk\Support\Str;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class StrTest extends TestCase
{
    public function test_str_limit(): void
    {
        $length = 10;
        // Input, length 13, should be shortened to 10
        $input = 'abc,@def|.ghi';
        $expected = 'abc,@de...';
        $output = Str::limit($input, $length);
        self::assertSame($length, \strlen($output));
        self::assertSame($expected, $output);

        // Input, length 10, should remain 10
        $input2 = 'abcdefghij';
        $expected2 = 'abcdefghij';
        $output2 = Str::limit($input2, $length);
        self::assertSame(\strlen($input2), \strlen($output2));
        self::assertSame($expected2, $output2);
    }
}
