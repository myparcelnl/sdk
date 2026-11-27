<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Bootstrap\Http;

/**
 * An answer that a test queues for the mock HTTP client: a status, headers and a body.
 */
final class Response
{
    /**
     * @var int
     */
    private $status;

    /**
     * @var array<string, string|string[]>
     */
    private $headers;

    /**
     * @var string
     */
    private $body;

    /**
     * @param array<string, string|string[]> $headers
     */
    public function __construct(int $status = 200, array $headers = [], string $body = '')
    {
        $this->status  = $status;
        $this->headers = $headers;
        $this->body    = $body;
    }

    public function getStatusCode(): int
    {
        return $this->status;
    }

    /**
     * @return array<string, string|string[]>
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getBody(): string
    {
        return $this->body;
    }
}
