<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Support;

use MyParcelNL\Sdk\Support\Str;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class StrBase64UrlTest extends TestCase
{
    public function testEncodeReplacesTheTwoUrlUnsafeCharactersAndDropsPadding(): void
    {
        // These three bytes are base64 "+/8=", which holds both characters that need replacing.
        $value = base64_decode('+/8=');

        self::assertSame('+/8=', base64_encode($value), 'precondition: plain base64 of this input');
        self::assertSame('-_8', Str::base64UrlEncode($value));
    }

    public function testEncodeNeverEmitsPaddingWhateverTheInputLength(): void
    {
        foreach ([1, 2, 3, 4, 5, 16, 32] as $length) {
            $encoded = Str::base64UrlEncode(str_repeat("\xff", $length));

            self::assertStringNotContainsString('=', $encoded, "padding found for $length bytes");
            self::assertStringNotContainsString('+', $encoded, "plus found for $length bytes");
            self::assertStringNotContainsString('/', $encoded, "slash found for $length bytes");
        }
    }

    public function testDecodeReversesEncodeForEveryPaddingLength(): void
    {
        // 1, 2 and 3 bytes cover all three padding cases base64 can produce.
        foreach ([1, 2, 3, 31, 32, 33] as $length) {
            $value = random_bytes($length);

            self::assertSame($value, Str::base64UrlDecode(Str::base64UrlEncode($value)), "round trip of $length bytes");
        }
    }

    public function testDecodeAcceptsAValueProducedElsewhere(): void
    {
        // The RFC 9449 section 6.1 jkt: 32 bytes, so 43 characters and no padding.
        $jkt = '0ZcOCORZNYy-DWpqq30jZyJGHTN0d2HglBV3uiguA4I';

        self::assertSame(32, strlen(Str::base64UrlDecode($jkt)));
    }

    public function testDecodeRejectsCharactersOutsideTheBase64UrlAlphabet(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Str::base64UrlDecode('not base64url!');
    }

    public function testDecodeRejectsATrailingNewline(): void
    {
        // A value read from a file or pasted into a settings field keeps its newline. base64_decode()
        // ignores whitespace even in strict mode, so without a guard this decodes silently and the
        // later comparison fails with nothing pointing at the input.
        $this->expectException(\InvalidArgumentException::class);

        Str::base64UrlDecode("QUJ\n");
    }

    public function testDecodeRejectsALengthNoBase64ValueCanHave(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Str::base64UrlDecode('QUJDQ');
    }
}
