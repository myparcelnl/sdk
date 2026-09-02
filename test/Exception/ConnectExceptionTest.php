<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Exception;

use Exception;
use MyParcelNL\Sdk\Exception\ConnectException;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class ConnectExceptionTest extends TestCase
{
    public function testMapsEveryKnownCallbackErrorToItsOwnCase(): void
    {
        $cases = [
            'access_denied'         => 'access_denied',
            'session_expired'       => 'session_expired',
            'invalid_sales_channel' => 'invalid_sales_channel',
            'server_error'          => 'server_error',
        ];

        foreach ($cases as $wire => $expected) {
            self::assertSame($expected, ConnectException::fromCallbackError($wire)->getErrorCode());
        }
    }

    public function testMapsAnUnknownCallbackErrorToServerError(): void
    {
        // The callback relays any error the identity provider produced, so the list is open ended.
        self::assertSame('server_error', ConnectException::fromCallbackError('something_new')->getErrorCode());
        self::assertSame('server_error', ConnectException::fromCallbackError('')->getErrorCode());
    }

    public function testKeepsTheUnknownWireValueInTheMessageForLogging(): void
    {
        self::assertStringContainsString(
            'something_new',
            ConnectException::fromCallbackError('something_new')->getMessage()
        );
    }

    public function testHttpErrorCarriesTheStatusHeadersAndBody(): void
    {
        $exception = ConnectException::httpError(400, ['dpop-nonce' => ['abc']], ['error' => 'use_dpop_nonce']);

        self::assertSame(400, $exception->getStatusCode());
        self::assertSame(['dpop-nonce' => ['abc']], $exception->getResponseHeaders());
        self::assertSame(['error' => 'use_dpop_nonce'], $exception->getResponseBody());
        self::assertSame('use_dpop_nonce', $exception->getErrorCode());
    }

    public function testHttpErrorWithoutAnErrorMemberHasNoErrorCode(): void
    {
        // A problem+json body carries no 'error' member, so there is nothing to map.
        $exception = ConnectException::httpError(415, [], ['type' => 'urn:problem:unsupported-media-type']);

        self::assertNull($exception->getErrorCode());
        self::assertSame(415, $exception->getStatusCode());
    }

    public function testCasesThatAreNotHttpFailuresCarryNoStatus(): void
    {
        $exception = ConnectException::notConnected();

        self::assertNull($exception->getStatusCode());
        self::assertSame([], $exception->getResponseHeaders());
        self::assertNull($exception->getResponseBody());
    }

    /**
     * @dataProvider provideCasesWithoutArguments
     */
    public function testEveryCaseHasAMessage(string $case): void
    {
        self::assertNotSame('', ConnectException::$case()->getMessage(), "$case has no message");
    }

    public function provideCasesWithoutArguments(): array
    {
        return $this->createProviderDataset([
            ['accessDenied'],
            ['sessionExpired'],
            ['invalidSalesChannel'],
            ['serverError'],
            ['connectionRevoked'],
            ['notConnected'],
            ['keyMismatch'],
            ['nonceRequired'],
            ['decryptionFailed'],
        ]);
    }

    public function testIsCatchableAsAPlainException(): void
    {
        self::assertInstanceOf(Exception::class, ConnectException::notConnected());
    }
}
