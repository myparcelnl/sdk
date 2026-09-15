<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Crypto;

/**
 * A key that can sign DPoP proofs, and can describe its public half.
 *
 * Only DpopKeyPair implements this. The interface exists so the signing can move to a library later
 * without changing anything that asks a key about itself. Its shape follows Firebase\JWT\Key, which
 * is the one library worth swapping to, so an adapter is a delegation and not a rewrite.
 */
interface DpopKeyInterface
{
    /**
     * The key in whatever form a signer accepts.
     *
     * Untyped on purpose: it is a resource on PHP 7.4 and an OpenSSLAsymmetricKey on 8.0 and later,
     * and PHP 7.4 has no union types. Mirrors Firebase\JWT\Key::getKeyMaterial().
     *
     * @return resource|\OpenSSLAsymmetricKey
     */
    public function getKeyMaterial();

    /**
     * The JWS algorithm name that goes in the proof header. Always 'ES256' here.
     *
     * Mirrors Firebase\JWT\Key::getAlgorithm().
     *
     * @return string
     */
    public function getAlgorithm(): string;

    /**
     * The public key as a JWK, for the jwk member of the proof header.
     *
     * @return array<string, string>
     */
    public function getPublicJwk(): array;

    /**
     * The thumbprint of the public key: a short fingerprint MyParcel uses to recognise the shop.
     *
     * @return string 43 base64url characters.
     */
    public function getThumbprint(): string;
}
