<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Exception;

use MyParcelNL\Sdk\Exception\ConnectException;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use RuntimeException;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * ConnectException::fromResponse() reads a failed answer of the Symfony HTTP client.
 */
final class ConnectExceptionFromResponseTest extends TestCase
{
    public function testCarriesTheStatusTheErrorCodeAndTheBody(): void
    {
        $exception = ConnectException::fromResponse(self::answer(400, '{"error":"invalid_grant"}'));

        self::assertSame(400, $exception->getStatusCode());
        self::assertSame('invalid_grant', $exception->getErrorCode());
        self::assertSame(['error' => 'invalid_grant'], $exception->getResponseBody());
    }

    public function testCarriesTheHeadersWithLowerCaseNames(): void
    {
        $exception = ConnectException::fromResponse(
            self::answer(400, '{"error":"use_dpop_nonce"}', ['DPoP-Nonce' => 'server-issued-nonce'])
        );

        self::assertSame(['server-issued-nonce'], $exception->getResponseHeaders()['dpop-nonce']);
    }

    public function testAServerErrorDoesNotThrowWhileItIsRead(): void
    {
        // The Symfony client throws for a 5xx when you read its headers or its content without
        // `false`. This method reads the answer, so it must not throw.
        $exception = ConnectException::fromResponse(self::answer(500, '{"error":"server_error"}'));

        self::assertSame(500, $exception->getStatusCode());
        self::assertSame('server_error', $exception->getErrorCode());
    }

    public function testABodyThatIsNotJsonKeepsTheStatus(): void
    {
        // A proxy or a load balancer answers with an HTML page.
        $exception = ConnectException::fromResponse(self::answer(415, '<html>no</html>'));

        self::assertSame(415, $exception->getStatusCode());
        self::assertNull($exception->getErrorCode(), 'there was no error member to read');
        self::assertNull($exception->getResponseBody());
    }

    public function testAJsonBodyThatIsNotAnObjectGivesNoBody(): void
    {
        $exception = ConnectException::fromResponse(self::answer(400, '"just text"'));

        self::assertSame(400, $exception->getStatusCode());
        self::assertNull($exception->getResponseBody());
    }

    public function testKeepsWhatFailedBeforeIt(): void
    {
        $cause = new RuntimeException('The body is not valid JSON.');

        self::assertSame($cause, ConnectException::fromResponse(self::answer(200, 'not json'), $cause)->getPrevious());
    }

    /**
     * An answer as the Symfony HTTP client gives it.
     *
     * @param array<string, string> $headers
     */
    private static function answer(int $status, string $body, array $headers = []): ResponseInterface
    {
        $mock = new MockHttpClient(new MockResponse($body, ['http_code' => $status, 'response_headers' => $headers]));

        return $mock->request('POST', 'https://example.test/connect/refresh');
    }
}
