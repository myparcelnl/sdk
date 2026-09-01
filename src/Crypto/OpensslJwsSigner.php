<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Crypto;

use MyParcelNL\Sdk\Support\Str;
use RuntimeException;

/**
 * Signs a JWS with ext-openssl.
 *
 * A JWS is three parts joined by dots: the header, the claims, and a signature over the first two.
 * Each part is base64url so the whole thing is safe in a header or a URL. This class does those
 * three steps and nothing else; what goes in the header and the claims is decided elsewhere.
 */
final class OpensslJwsSigner implements JwsSignerInterface
{
    /**
     * The only algorithm MyParcel accepts: ECDSA on P-256 with SHA-256.
     */
    private const ALGORITHM = 'ES256';

    public function sign(array $payload, DpopKeyInterface $key, array $protectedHeader): string
    {
        if (self::ALGORITHM !== $key->getAlgorithm()) {
            throw new RuntimeException(sprintf('A DPoP proof must be signed with %s', self::ALGORITHM));
        }

        // The key decides the algorithm, never the caller. Assigning an existing member keeps its
        // place in the array, so the header order the caller chose stays.
        $protectedHeader['alg'] = $key->getAlgorithm();

        $signingInput = self::encodeSegment($protectedHeader) . '.' . self::encodeSegment($payload);

        return $signingInput . '.' . Str::base64UrlEncode($this->signRaw($signingInput, $key));
    }

    /**
     * Sign the input and return the 64 bytes a JWS needs.
     *
     * @param  DpopKeyInterface $key
     * @throws \RuntimeException
     */
    private function signRaw(string $signingInput, DpopKeyInterface $key): string
    {
        $der = '';

        // openssl_sign() writes the signature into its second argument, by reference.
        if (!openssl_sign($signingInput, $der, $key->getKeyMaterial(), OPENSSL_ALGO_SHA256)) {
            throw new RuntimeException('OpenSSL could not sign the proof');
        }

        return EcdsaSignature::signatureFromDER($der);
    }

    /**
     * @param  array<string, mixed> $value
     * @throws \RuntimeException
     */
    private static function encodeSegment(array $value): string
    {
        // Slashes must stay unescaped. json_encode() turns '/' into '\/' by default, which would
        // change the htu the far side checks against the request it received.
        $json = json_encode($value, JSON_UNESCAPED_SLASHES);

        if (false === $json) {
            throw new RuntimeException('Could not encode a proof segment: ' . json_last_error_msg());
        }

        return Str::base64UrlEncode($json);
    }
}
