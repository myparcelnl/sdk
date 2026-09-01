<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Crypto;

use MyParcelNL\Sdk\Concerns\RequiresOpenssl;
use MyParcelNL\Sdk\Support\Str;
use RuntimeException;

/**
 * The key pair a shop signs its DPoP proofs with.
 *
 * Two matching numbers on the P-256 curve. The private half never leaves the shop and does the
 * signing. The public half is derived from it and is safe to hand out, so it travels inside every
 * proof. One pair per shop, kept for the shop's whole lifetime: MyParcel recognises the shop by the
 * thumbprint of this key, so a new key makes it a different shop.
 */
final class DpopKeyPair implements DpopKeyInterface
{
    use RequiresOpenssl;

    /**
     * The only curve MyParcel accepts. 'prime256v1' is the OpenSSL name for it; the same curve is
     * called 'P-256' in a JWK.
     */
    private const CURVE = 'prime256v1';

    private const JWK_CURVE = 'P-256';

    private const ALGORITHM = 'ES256';

    /**
     * Bytes per coordinate on P-256.
     */
    private const COORDINATE_LENGTH = 32;

    /**
     * @var resource|\OpenSSLAsymmetricKey
     */
    private $key;

    /**
     * @param resource|\OpenSSLAsymmetricKey $key
     */
    private function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * Make a new key pair. Call this once per shop, then store what getPrivateKeyPem() returns.
     *
     * Only the private key needs storing. To get the public half back later, pass the stored PEM to
     * fromPem() and ask the object it returns: getPublicJwk() and getThumbprint() give the same
     * values as before, because both are worked out from the private key every time. Storing the
     * public key as well would be a second copy that can drift.
     *
     * @throws \RuntimeException When OpenSSL cannot produce a P-256 key.
     */
    public static function generate(): self
    {
        self::assertOpensslIsLoaded();

        // Without private_key_type, OpenSSL quietly returns an RSA key and ignores the curve.
        $key = openssl_pkey_new([
            'private_key_type' => OPENSSL_KEYTYPE_EC,
            'curve_name'       => self::CURVE,
        ]);

        if (!$key) {
            throw new RuntimeException('Could not generate a P-256 key pair');
        }

        return new self(self::assertIsP256($key));
    }

    /**
     * Load a stored key pair back.
     *
     * @param  string $pem What getPrivateKeyPem() returned, decrypted.
     * @throws \RuntimeException When the text is not a P-256 private key.
     */
    public static function fromPem(string $pem): self
    {
        self::assertOpensslIsLoaded();

        // openssl_pkey_get_private() reads a 'file://' value from disk. This parameter is text, and
        // it arrives from storage the consumer owns, so a path must not be followed.
        if ('-----BEGIN' !== substr(ltrim($pem), 0, 10)) {
            throw new RuntimeException('A private key must be given as PEM text');
        }

        $key = openssl_pkey_get_private($pem);

        if (!$key) {
            throw new RuntimeException('Could not read a private key from the given PEM');
        }

        return new self(self::assertIsP256($key));
    }

    /**
     * The thumbprint of any public JWK, per RFC 7638.
     *
     * Keep only the four required members, put them in this exact order, write them as JSON with no
     * spaces, then SHA-256 it. Order and spacing are part of the input, so a different order gives a
     * different thumbprint and MyParcel would see a different shop.
     *
     * @param  array<string, string> $jwk
     * @return string 43 base64url characters.
     */
    public static function thumbprintOfJwk(array $jwk): string
    {
        $canonical = [];

        foreach (['crv', 'kty', 'x', 'y'] as $member) {
            if (!isset($jwk[$member]) || !is_string($jwk[$member]) || '' === $jwk[$member]) {
                throw new RuntimeException(sprintf('A public JWK needs a non-empty %s member', $member));
            }

            $canonical[$member] = $jwk[$member];
        }

        $json = json_encode($canonical, JSON_UNESCAPED_SLASHES);

        if (false === $json) {
            throw new RuntimeException('Could not encode the JWK: ' . json_last_error_msg());
        }

        return Str::base64UrlEncode(hash('sha256', $json, true));
    }

    /**
     * @return resource|\OpenSSLAsymmetricKey
     */
    public function getKeyMaterial()
    {
        return $this->key;
    }

    public function getAlgorithm(): string
    {
        return self::ALGORITHM;
    }

    /**
     * The public key as a JWK, for the jwk member of a proof header.
     *
     * Four members in the order thumbprintOfJwk() needs, and never the private key: RFC 9449
     * forbids one there.
     *
     * @return array<string, string>
     */
    public function getPublicJwk(): array
    {
        $details = openssl_pkey_get_details($this->key);

        if (!$details || !isset($details['ec']['x'], $details['ec']['y'])) {
            throw new RuntimeException('Could not read the public key');
        }

        $ec = $details['ec'];

        return [
            'crv' => self::JWK_CURVE,
            'kty' => 'EC',
            'x'   => Str::base64UrlEncode(self::padCoordinate($ec['x'])),
            'y'   => Str::base64UrlEncode(self::padCoordinate($ec['y'])),
        ];
    }

    /**
     * This key's thumbprint. Sent as the jkt parameter to /connect/start, and carried back in the
     * cnf.jkt claim of every access token issued for it.
     */
    public function getThumbprint(): string
    {
        return self::thumbprintOfJwk($this->getPublicJwk());
    }

    /**
     * The private key as text, to hand to storage.
     *
     * Encrypt this before it reaches any storage. It is the shop's identity at MyParcel: whoever
     * holds it can use the shop's tokens.
     *
     * @return string A PEM, to be passed back to fromPem() later.
     * @throws \RuntimeException When OpenSSL cannot export the key.
     */
    public function getPrivateKeyPem(): string
    {
        // The header differs per OpenSSL version, BEGIN EC PRIVATE KEY on 1.1.1 and BEGIN PRIVATE
        // KEY on 3.x. Both load again on both, so never assert on it.
        if (!openssl_pkey_export($this->key, $pem)) {
            throw new RuntimeException('Could not export the private key');
        }

        return $pem;
    }

    /**
     * Pad a coordinate back to its full width.
     *
     * OpenSSL 1.1.1 drops a leading zero byte, so about one key in 256 comes back a byte short,
     * where OpenSSL 3 pads it. RFC 7518 requires the full size, and without padding one key would
     * produce two different thumbprints across two PHP versions. That thumbprint is the shop's
     * identity at MyParcel, so it has to be the same everywhere the SDK runs.
     */
    private static function padCoordinate(string $coordinate): string
    {
        if (strlen($coordinate) > self::COORDINATE_LENGTH) {
            throw new RuntimeException('A P-256 coordinate cannot be longer than 32 bytes');
        }

        return str_pad($coordinate, self::COORDINATE_LENGTH, "\x00", STR_PAD_LEFT);
    }

    /**
     * @param  resource|\OpenSSLAsymmetricKey $key
     * @return resource|\OpenSSLAsymmetricKey
     * @throws \RuntimeException
     */
    private static function assertIsP256($key)
    {
        $details = openssl_pkey_get_details($key);

        if (!$details || OPENSSL_KEYTYPE_EC !== $details['type']) {
            throw new RuntimeException('A DPoP key must be an elliptic curve key');
        }

        if (self::CURVE !== ($details['ec']['curve_name'] ?? null)) {
            throw new RuntimeException(sprintf('A DPoP key must be on the %s curve', self::CURVE));
        }

        return $key;
    }
}
