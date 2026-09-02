<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Crypto;

use MyParcelNL\Sdk\Concerns\RequiresOpenssl;
use MyParcelNL\Sdk\Exception\ConnectException;

/**
 * Encrypts the secrets in the connect state before they reach the consumer's storage.
 *
 * Uses AES-256-GCM. GCM both hides the value and detects any change to it: decrypting produces a
 * short "tag" alongside the ciphertext, and a value that was edited afterwards fails to decrypt
 * rather than coming back wrong.
 *
 * The result is one base64 string holding four things in a fixed order:
 *
 *     version (1 byte) | iv (12 bytes) | tag (16 bytes) | ciphertext
 *
 * The iv is a fresh random value per call, so encrypting the same secret twice gives two different
 * strings. The version byte is there so a later change to this layout can be recognised instead of
 * guessed at.
 */
final class AesGcmCipher
{
    use RequiresOpenssl;

    private const CIPHER = 'aes-256-gcm';

    /**
     * Bumped only if the layout above changes.
     */
    private const VERSION = 1;

    private const IV_LENGTH = 12;

    private const TAG_LENGTH = 16;

    /**
     * @var string 32 raw bytes.
     */
    private $key;

    /**
     * @var callable Takes a length and returns that many random bytes.
     */
    private $randomBytes;

    /**
     * @param string        $key         Any non-empty string. Hashed to the 32 bytes AES needs, so
     *                                   the consumer can pass a passphrase.
     * @param callable|null $randomBytes Only for tests, to pin the iv.
     * @throws \MyParcelNL\Sdk\Exception\ConnectException When the key is empty.
     * @throws \RuntimeException                          When ext-openssl is missing. ConnectService
     *                                                    checks for it first and reports it as a
     *                                                    ConnectException, so a consumer that starts
     *                                                    there never sees this one.
     */
    public function __construct(string $key, ?callable $randomBytes = null)
    {
        self::assertOpensslIsLoaded();

        if ('' === $key) {
            throw ConnectException::invalidArgument('The encryption key cannot be empty');
        }

        $this->key         = hash('sha256', $key, true);
        $this->randomBytes = $randomBytes ?? 'random_bytes';
    }

    /**
     * @return string base64 of the envelope described on the class.
     * @throws \MyParcelNL\Sdk\Exception\ConnectException
     */
    public function encrypt(string $plaintext): string
    {
        $version = chr(self::VERSION);
        $iv      = ($this->randomBytes)(self::IV_LENGTH);
        $tag     = '';

        // The version travels as additional authenticated data: it is not encrypted, but changing it
        // breaks the tag, so it cannot be swapped for another one unnoticed.
        $ciphertext = openssl_encrypt(
            $plaintext,
            self::CIPHER,
            $this->key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag,
            $version,
            self::TAG_LENGTH
        );

        if (false === $ciphertext) {
            throw ConnectException::encryptionFailed();
        }

        return base64_encode($version . $iv . $tag . $ciphertext);
    }

    /**
     * @param  string $ciphertext What encrypt() returned.
     * @throws \MyParcelNL\Sdk\Exception\ConnectException When the value was changed, or the key is
     *                                                   not the one it was encrypted with. The two
     *                                                   cannot be told apart.
     */
    public function decrypt(string $ciphertext): string
    {
        $raw = base64_decode($ciphertext, true);

        if (false === $raw || strlen($raw) < 1 + self::IV_LENGTH + self::TAG_LENGTH) {
            throw ConnectException::decryptionFailed();
        }

        $version = substr($raw, 0, 1);

        if (self::VERSION !== ord($version)) {
            throw ConnectException::decryptionFailed();
        }

        $plaintext = openssl_decrypt(
            substr($raw, 1 + self::IV_LENGTH + self::TAG_LENGTH),
            self::CIPHER,
            $this->key,
            OPENSSL_RAW_DATA,
            substr($raw, 1, self::IV_LENGTH),
            substr($raw, 1 + self::IV_LENGTH, self::TAG_LENGTH),
            $version
        );

        if (false === $plaintext) {
            throw ConnectException::decryptionFailed();
        }

        return $plaintext;
    }
}
