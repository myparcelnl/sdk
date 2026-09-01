<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Crypto;

/**
 * Turns a header and a set of claims into one signed string.
 *
 * OpensslJwsSigner is the only implementation. The interface exists so a library can take the
 * signing over later without touching the code that decides what goes in a proof. Its argument order
 * follows Firebase\JWT\JWT::encode($payload, $key, $alg, $keyId, $head), so an adapter for that
 * library is one call.
 */
interface JwsSignerInterface
{
    /**
     * Sign the claims and return them as a compact JWS.
     *
     * @param  array<string, mixed>  $payload         The claims, complete.
     * @param  DpopKeyInterface      $key             Signs, and supplies the alg header member.
     * @param  array<string, mixed>  $protectedHeader typ and jwk. An alg here is overwritten.
     * @return string base64url(header).base64url(payload).base64url(signature)
     * @throws \RuntimeException When the key cannot sign what this signer produces.
     */
    public function sign(array $payload, DpopKeyInterface $key, array $protectedHeader): string;
}
