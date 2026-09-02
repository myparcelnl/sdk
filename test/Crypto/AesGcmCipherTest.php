<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Crypto;

use MyParcelNL\Sdk\Crypto\AesGcmCipher;
use MyParcelNL\Sdk\Exception\ConnectException;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class AesGcmCipherTest extends TestCase
{
    private const KEY = 'a key a shop put in wp-config.php';

    public function testRoundTripsAValue(): void
    {
        $cipher = new AesGcmCipher(self::KEY);
        $secret = "-----BEGIN PRIVATE KEY-----\nMIGH\n-----END PRIVATE KEY-----\n";

        self::assertSame($secret, $cipher->decrypt($cipher->encrypt($secret)));
    }

    public function testRoundTripsAnEmptyValue(): void
    {
        $cipher = new AesGcmCipher(self::KEY);

        self::assertSame('', $cipher->decrypt($cipher->encrypt('')));
    }

    public function testTheSameValueEncryptsDifferentlyEveryTime(): void
    {
        // A fresh iv per call. Reusing one with the same key would leak that two values are equal.
        $cipher = new AesGcmCipher(self::KEY);

        self::assertNotSame($cipher->encrypt('same'), $cipher->encrypt('same'));
    }

    public function testTheCiphertextDoesNotContainThePlaintext(): void
    {
        $cipher = new AesGcmCipher(self::KEY);

        self::assertStringNotContainsString('findme', base64_decode($cipher->encrypt('findme')));
    }

    public function testAnyStringWorksAsAKey(): void
    {
        // The consumer supplies a passphrase, not 32 bytes, so the key is hashed to size.
        $cipher = new AesGcmCipher('x');

        self::assertSame('value', $cipher->decrypt($cipher->encrypt('value')));
    }

    public function testAnotherKeyCannotDecrypt(): void
    {
        $encrypted = (new AesGcmCipher(self::KEY))->encrypt('value');

        $this->expectException(ConnectException::class);

        (new AesGcmCipher('a different key'))->decrypt($encrypted);
    }

    public function testATamperedCiphertextIsRefused(): void
    {
        $raw = base64_decode((new AesGcmCipher(self::KEY))->encrypt('value'));

        // Flip the last byte of the ciphertext. GCM authenticates, so this must not decode.
        $raw[strlen($raw) - 1] = chr(ord($raw[strlen($raw) - 1]) ^ 0xff);

        $this->expectException(ConnectException::class);

        (new AesGcmCipher(self::KEY))->decrypt(base64_encode($raw));
    }

    public function testATamperedVersionByteIsRefused(): void
    {
        $raw = base64_decode((new AesGcmCipher(self::KEY))->encrypt('value'));

        $raw[0] = chr(9);

        $this->expectException(ConnectException::class);

        (new AesGcmCipher(self::KEY))->decrypt(base64_encode($raw));
    }

    public function testTheEnvelopeStartsWithAVersionByte(): void
    {
        $raw = base64_decode((new AesGcmCipher(self::KEY))->encrypt('value'));

        self::assertSame(1, ord($raw[0]), 'version 1 is the only envelope written today');
    }

    public function testATruncatedEnvelopeIsRefused(): void
    {
        $this->expectException(ConnectException::class);

        (new AesGcmCipher(self::KEY))->decrypt(base64_encode("\x01short"));
    }

    public function testSomethingThatIsNotAnEnvelopeIsRefused(): void
    {
        $this->expectException(ConnectException::class);

        (new AesGcmCipher(self::KEY))->decrypt('not base64 at all!!');
    }

    public function testRejectsAnEmptyKey(): void
    {
        $this->expectException(ConnectException::class);

        new AesGcmCipher('');
    }

    public function testUsesTheInjectedRandomSourceForTheIv(): void
    {
        $cipher = new AesGcmCipher(self::KEY, static function (int $length): string {
            return str_repeat("\x07", $length);
        });

        $raw = base64_decode($cipher->encrypt('value'));

        self::assertSame(str_repeat("\x07", 12), substr($raw, 1, 12), 'a 12 byte iv follows the version');
    }
}
