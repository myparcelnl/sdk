<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Bootstrap;

use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

/**
 * Records the requests that a Jane client sends to a mock HTTP client, and reads their headers.
 */
trait MocksHttpClient
{
    /**
     * @var array<int, array{method: string, url: string, options: array<string, mixed>}> The requests, in the order they were sent.
     */
    protected array $requests = [];

    /**
     * Create a client that records each request and answers every one with the same response.
     */
    protected function mockHttpClient(string $body = '{}', int $status = 200, string $contentType = 'application/json'): MockHttpClient
    {
        return new MockHttpClient(function (string $method, string $url, array $options) use ($body, $status, $contentType): MockResponse {
            $this->requests[] = ['method' => $method, 'url' => $url, 'options' => $options];

            return new MockResponse($body, ['http_code' => $status, 'response_headers' => ['content-type' => $contentType]]);
        });
    }

    /**
     * Read a header of a recorded request, without its name, or null when the request did not send it.
     */
    protected function header(string $name, int $request = 0): ?string
    {
        $line = $this->requests[$request]['options']['normalized_headers'][strtolower($name)][0] ?? null;

        return null === $line ? null : substr($line, strlen($name) + 2);
    }
}
