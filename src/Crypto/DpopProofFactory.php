<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Crypto;

use MyParcelNL\Sdk\Support\Str;
use RuntimeException;

/**
 * Builds the DPoP proofs that go in the DPoP request header.
 *
 * A proof is a small signed token, made for one request and thrown away. It says which method and
 * URL the request is, when it was made, and carries the shop's public key. The receiver checks the
 * signature with that key and checks the method and URL against the request it really got. So it
 * shows both that the caller holds the private key and that this proof was not lifted off another
 * request.
 *
 * There are two kinds, and mixing them up fails verification:
 *
 * - At /connect/token and /connect/refresh the method and URL are the ones MyParcel sent on the
 *   callback, not the URL being called, and there is no access token to point at.
 * - On every other call they are the method and URL of that request, plus a hash of the access token
 *   sent with it.
 *
 * Each kind has its own method, so neither can be built by accident.
 */
final class DpopProofFactory
{
    private const TYPE = 'dpop+jwt';

    /**
     * Bytes of randomness per jti. RFC 9449 asks for at least 96 bits.
     */
    private const JTI_LENGTH = 32;

    /**
     * @var \MyParcelNL\Sdk\Crypto\JwsSignerInterface
     */
    private $signer;

    /**
     * @param JwsSignerInterface|null $signer Defaults to signing with ext-openssl. See
     *                                        {@see JwsSignerInterface} for why this is a seam.
     */
    public function __construct(?JwsSignerInterface $signer = null)
    {
        $this->signer = $signer ?? new OpensslJwsSigner();
    }

    /**
     * A proof for /connect/token or /connect/refresh.
     *
     * @param  DpopKeyInterface $key
     * @param  string           $htm   The method from the callback. Always POST today.
     * @param  string           $htu   The URL from the callback, stored since the shop connected.
     * @param  string|null      $nonce The DPoP-Nonce MyParcel last sent, when it sent one.
     * @throws \RuntimeException
     */
    public function createForTokenEndpoint(
        DpopKeyInterface $key,
        string $htm,
        string $htu,
        ?string $nonce = null
    ): string {
        $claims = $this->baseClaims($htm, $htu);

        if (null !== $nonce && '' !== $nonce) {
            $claims['nonce'] = $nonce;
        }

        return $this->sign($key, $claims);
    }

    /**
     * A proof for any call that carries an access token.
     *
     * @param  DpopKeyInterface $key
     * @param  string           $htm         The method of this request.
     * @param  string           $htu         The URL of this request, without query or fragment.
     * @param  string           $accessToken The token going out in the Authorization header. Read it
     *                                       once and pass the same value here, or the two disagree
     *                                       and the call is refused.
     * @throws \RuntimeException
     */
    public function createForResourceRequest(
        DpopKeyInterface $key,
        string $htm,
        string $htu,
        string $accessToken
    ): string {
        $claims = $this->baseClaims($htm, $htu);

        // ath ties this proof to that one token: the hash of the token as it is sent, not of
        // anything inside it.
        $claims['ath'] = Str::base64UrlEncode(hash('sha256', $accessToken, true));

        return $this->sign($key, $claims);
    }

    /**
     * The four claims every proof has.
     *
     * @return array<string, mixed>
     * @throws \RuntimeException
     */
    private function baseClaims(string $htm, string $htu): array
    {
        if (false !== strpos($htu, '?') || false !== strpos($htu, '#')) {
            throw new RuntimeException('htu must not carry a query or a fragment');
        }

        return [
            'jti' => Str::base64UrlEncode(random_bytes(self::JTI_LENGTH)),
            'htm' => $htm,
            'htu' => $htu,
            'iat' => time(),
        ];
    }

    /**
     * @param  array<string, mixed> $claims
     * @throws \RuntimeException
     */
    private function sign(DpopKeyInterface $key, array $claims): string
    {
        return $this->signer->sign($claims, $key, [
            'typ' => self::TYPE,
            'alg' => $key->getAlgorithm(),
            'jwk' => $key->getPublicJwk(),
        ]);
    }
}
