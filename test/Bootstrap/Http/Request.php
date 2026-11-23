<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Bootstrap\Http;

/**
 * A request that the mock HTTP client received, for a test to read.
 */
final class Request
{
    /**
     * @var string
     */
    private $method;

    /**
     * @var string
     */
    private $uri;

    /**
     * @var array<string, string[]> The header values, by the header name in lower case.
     */
    private $headers = [];

    /**
     * @var string
     */
    private $body;

    /**
     * @param array<string, string[]> $headers
     */
    public function __construct(string $method, string $uri, array $headers = [], string $body = '')
    {
        $this->method = $method;
        $this->uri    = $uri;
        $this->body   = $body;

        foreach ($headers as $name => $values) {
            $this->headers[strtolower($name)] = $values;
        }
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function hasHeader(string $name): bool
    {
        return isset($this->headers[strtolower($name)]);
    }

    /**
     * The values of a header, joined with a comma. An empty string when the header was not sent.
     */
    public function getHeaderLine(string $name): string
    {
        return implode(', ', $this->headers[strtolower($name)] ?? []);
    }
}
