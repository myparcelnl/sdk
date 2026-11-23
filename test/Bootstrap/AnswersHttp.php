<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Bootstrap;

use Closure;
use MyParcelNL\Sdk\Test\Bootstrap\Http\Request;
use MyParcelNL\Sdk\Test\Bootstrap\Http\Response;
use OutOfBoundsException;
use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Throwable;

/**
 * An HTTP client that answers with what a test queues, and records what went out.
 *
 * A test queues a Response, or a Throwable for a failure before an answer arrived. It reads what
 * went out from $history. So a test says what MyParcel answers, and not how the HTTP client works.
 */
trait AnswersHttp
{
    /**
     * @var array<int, array{request: \MyParcelNL\Sdk\Test\Bootstrap\Http\Request, response: mixed}>
     */
    protected $history = [];

    /**
     * @param \MyParcelNL\Sdk\Test\Bootstrap\Http\Response|\Throwable|\Closure ...$answers
     */
    protected function httpAnswering(...$answers): MockHttpClient
    {
        return new MockHttpClient(function (string $method, string $url, array $options) use (&$answers): MockResponse {
            if ([] === $answers) {
                throw new OutOfBoundsException('Mock queue is empty');
            }

            $answer = array_shift($answers);

            // A closure runs while the call is under way, and gives the answer.
            if ($answer instanceof Closure) {
                $answer = $answer();
            }

            $this->history[] = [
                'request'  => new Request($method, $url, self::headersOf($options), self::bodyOf($options)),
                'response' => $answer,
            ];

            if ($answer instanceof Throwable) {
                // A failure before an answer arrived: no DNS, no route, no TLS, or a timeout.
                throw new TransportException($answer->getMessage(), 0, $answer);
            }

            return new MockResponse($answer->getBody(), [
                'http_code'        => $answer->getStatusCode(),
                'response_headers' => $answer->getHeaders(),
            ]);
        });
    }

    /**
     * The request headers as name and values, from the form in which the mock client gets them.
     *
     * @param  array<string, mixed> $options
     * @return array<string, string[]>
     */
    private static function headersOf(array $options): array
    {
        $headers = [];

        foreach ($options['normalized_headers'] ?? [] as $lines) {
            foreach ($lines as $line) {
                [$name, $value] = explode(':', $line, 2);

                $headers[$name][] = ltrim($value);
            }
        }

        return $headers;
    }

    /**
     * @param array<string, mixed> $options
     */
    private static function bodyOf(array $options): string
    {
        $body = $options['body'] ?? '';

        return is_string($body) ? $body : '';
    }
}
