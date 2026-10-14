<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Helper;

use MyParcelNL\Sdk\Helper\MyParcelCurl;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class MyParcelCurlTest extends TestCase
{
    public function testCloseCanBeCalledTwice(): void
    {
        $curl = new MyParcelCurl();

        self::assertSame($curl, $curl->close());
        self::assertSame($curl, $curl->close());
    }
}
