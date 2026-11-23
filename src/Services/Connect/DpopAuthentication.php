<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Connect;

use Jane\Component\OpenApiRuntime\Client\AuthenticationPlugin;
use LogicException;

/**
 * Signs a request of the generated e-commerce client with the key of the connected shop.
 *
 * The generated `DpopAuthentication` has an empty `decorate()`: the spec names the `dpop` scheme,
 * but a spec cannot say how to make a proof. This class does that work. It stays for as long as
 * the e-commerce API uses DPoP.
 *
 * Jane calls `decorate()` only for an operation that the spec secures with `dpop`. Put the
 * `AuthenticationRegistry` that holds this class inside the `ServerUrlHttpClient`. Then
 * `decorate()` gets the full URL, and a proof must sign the full URL.
 *
 * @internal For the e-commerce client that the SDK builds.
 */
final class DpopAuthentication implements AuthenticationPlugin
{
    /**
     * The ports that a URL can leave out, per scheme.
     */
    private const DEFAULT_PORTS = ['http' => 80, 'https' => 443];

    /**
     * @var \MyParcelNL\Sdk\Services\Connect\ConnectService
     */
    private $connect;

    public function __construct(ConnectService $connect)
    {
        $this->connect = $connect;
    }

    /**
     * Put the access token and its proof in the headers of one request.
     *
     * @param  string               $method  The HTTP method of the request.
     * @param  string               $url     The full URL of the request.
     * @param  array<string, mixed> $options The Symfony HttpClient options of the request.
     * @throws \MyParcelNL\Sdk\Exception\ConnectException When the shop is not connected.
     * @throws \LogicException                           When the URL is not a full URL.
     */
    public function decorate(string $method, string $url, array &$options): void
    {
        $token = $this->connect->getAccessToken();

        $options['headers']['Authorization'] = 'DPoP ' . $token;
        $options['headers']['DPoP'] = $this->connect->createResourceProof($method, $this->htu($url), $token);
    }

    public function getScope(): string
    {
        return 'dpop';
    }

    /**
     * The URL that a proof signs: the request URL without the query and the fragment, with the
     * scheme and the host in lower case, and without a default port. MyParcel compares the proof
     * against this form.
     *
     * @throws \LogicException When the URL has no scheme or no host.
     */
    private function htu(string $url): string
    {
        $parts = parse_url($url);

        if (!is_array($parts) || !isset($parts['scheme'], $parts['host'])) {
            throw new LogicException(sprintf(
                'DpopAuthentication needs a full URL to sign, and got "%s". Put its AuthenticationRegistry inside the ServerUrlHttpClient.',
                $url
            ));
        }

        $scheme = strtolower($parts['scheme']);
        $port   = $parts['port'] ?? null;

        if (null !== $port && $port === (self::DEFAULT_PORTS[$scheme] ?? null)) {
            $port = null;
        }

        return sprintf(
            '%s://%s%s%s',
            $scheme,
            strtolower($parts['host']),
            null === $port ? '' : ':' . $port,
            $parts['path'] ?? ''
        );
    }
}
