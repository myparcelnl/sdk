<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Connect;

use Jane\Component\OpenApiRuntime\Client\Plugin\AuthenticationRegistry;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;

/**
 * Sends a signed request once more with a fresh token when MyParcel answers 401.
 *
 * `ConnectService::getAccessToken()` refreshes a token that has expired. A 401 here means that
 * MyParcel refused a token that had not expired yet: a clock that runs behind, or a connection
 * that was revoked during the call. One refresh and one more attempt solve the first case.
 *
 * Put this client outside the `AuthenticationRegistry` that holds `DpopAuthentication`. The
 * second attempt then goes through the registry again and gets the fresh token and a new proof.
 *
 * A request that the spec does not secure, such as a connect call, carries no token. A 401 on
 * such a request comes back as it is.
 *
 * @internal For the e-commerce client that the SDK builds.
 */
final class TokenRefreshHttpClient implements HttpClientInterface
{
    /**
     * @var \Symfony\Contracts\HttpClient\HttpClientInterface
     */
    private $httpClient;

    /**
     * @var \MyParcelNL\Sdk\Services\Connect\ConnectService
     */
    private $connect;

    public function __construct(HttpClientInterface $httpClient, ConnectService $connect)
    {
        $this->httpClient = $httpClient;
        $this->connect    = $connect;
    }

    /**
     * Send the request. After a 401 on a signed request, refresh the token and send the request one
     * more time.
     *
     * @throws \MyParcelNL\Sdk\Exception\ConnectException When the refresh fails, for example
     *                                                    because the connection was revoked.
     */
    public function request(string $method, string $url, array $options = []): ResponseInterface
    {
        $response = $this->httpClient->request($method, $url, $options);

        if (401 === $response->getStatusCode() && $this->isSigned($options) && $this->canSendAgain($options)) {
            $this->connect->refresh();
            $response = $this->httpClient->request($method, $url, $options);
        }

        return $response;
    }

    public function stream(ResponseInterface|iterable $responses, ?float $timeout = null): ResponseStreamInterface
    {
        return $this->httpClient->stream($responses, $timeout);
    }

    public function withOptions(array $options): static
    {
        return new self($this->httpClient->withOptions($options), $this->connect);
    }

    /**
     * The generated client marks a request of an operation that the spec secures. Only such a
     * request carries the token of the shop, so only for such a request can a fresh token help.
     *
     * @param array<string, mixed> $options
     */
    private function isSigned(array $options): bool
    {
        return [] !== (array) ($options['extra'][AuthenticationRegistry::SCOPES_OPTION] ?? []);
    }

    /**
     * A text body or no body can go out twice. A stream is read to the end by the first attempt,
     * so a second attempt would send an empty body, and a push without orders looks like a success.
     *
     * @param array<string, mixed> $options
     */
    private function canSendAgain(array $options): bool
    {
        $body = $options['body'] ?? null;

        return null === $body || is_string($body) || is_array($body);
    }
}
