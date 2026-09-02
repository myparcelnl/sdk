<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Exception;

use Exception;
use Throwable;
use MyParcelNL\Sdk\Model\Connect\ConnectCallbackError;
use MyParcelNL\Sdk\Model\Connect\ConnectErrorCode;

/**
 * Everything that can go wrong in MyParcel Connect.
 *
 * One type, so a consumer catches one thing. Which case it is, is in getErrorCode() when the failure
 * came off the wire, and in the message otherwise.
 *
 * The named constructors are the whole list of failures the flow can produce. Use them instead of
 * `new ConnectException(...)`, so every message reads the same wherever it is thrown.
 */
class ConnectException extends Exception
{
    /**
     * @var string|null The value as it came off the wire, for logging.
     */
    private $errorCode;

    /**
     * @var int|null Set only for a failed HTTP call.
     */
    private $statusCode;

    /**
     * @var array<string, mixed>
     */
    private $responseHeaders = [];

    /**
     * @var array<string, mixed>|null
     */
    private $responseBody;

    /**
     * The merchant, or their account, refused the connection.
     */
    public static function accessDenied(): self
    {
        return self::withCode(ConnectCallbackError::ACCESS_DENIED, 'The connection was denied');
    }

    /**
     * The merchant took too long. They have to start again.
     */
    public static function sessionExpired(): self
    {
        return self::withCode(ConnectCallbackError::SESSION_EXPIRED, 'The connect session expired, start again');
    }

    /**
     * MyParcel could not match the shop to a sales channel.
     */
    public static function invalidSalesChannel(): self
    {
        return self::withCode(ConnectErrorCode::INVALID_SALES_CHANNEL, 'MyParcel could not link this shop to a sales channel');
    }

    /**
     * Anything MyParcel could not handle, and anything we do not recognise.
     *
     * @param string|null $wireValue The unrecognised value, when there was one.
     */
    public static function serverError(?string $wireValue = null): self
    {
        $message = 'MyParcel could not complete the connection';

        if (null !== $wireValue && '' !== $wireValue) {
            $message .= sprintf(' (reported as "%s")', $wireValue);
        }

        return self::withCode(ConnectErrorCode::SERVER_ERROR, $message);
    }

    /**
     * Turn the error parameter of a callback into one of the cases above.
     *
     * The list is open ended, because the callback relays anything the identity provider produced.
     * So an unknown value becomes a server error, with the value kept in the message.
     *
     * @param string $error The raw value from the callback query.
     */
    public static function fromCallbackError(string $error): self
    {
        switch ($error) {
            case ConnectCallbackError::ACCESS_DENIED:
                return self::accessDenied();
            case ConnectCallbackError::SESSION_EXPIRED:
                return self::sessionExpired();
            case ConnectCallbackError::INVALID_SALES_CHANNEL:
                return self::invalidSalesChannel();
            case ConnectCallbackError::SERVER_ERROR:
                return self::serverError();
            default:
                return self::serverError($error);
        }
    }

    /**
     * The callback did not belong to a flow this shop started, or it arrived twice.
     */
    public static function invalidCallback(string $reason): self
    {
        return new self(sprintf('The connect callback was refused: %s', $reason));
    }

    /**
     * MyParcel no longer has this connection. The merchant has to connect again.
     */
    public static function connectionRevoked(): self
    {
        return self::withCode(ConnectErrorCode::INVALID_GRANT, 'The connection was revoked, the shop has to connect again');
    }

    /**
     * There is no connection to use yet.
     */
    public static function notConnected(): self
    {
        return new self('This shop is not connected to MyParcel');
    }

    /**
     * The token MyParcel issued is bound to a different key than the one we hold.
     */
    public static function keyMismatch(): self
    {
        return new self('The access token is not bound to this shop\'s key');
    }

    /**
     * MyParcel asked for a nonce twice in a row, which one retry cannot fix.
     */
    public static function nonceRequired(): self
    {
        return self::withCode(ConnectErrorCode::USE_DPOP_NONCE, 'MyParcel keeps asking for a new nonce');
    }

    /**
     * The state could not be encrypted. Nothing a caller passed can cause this: the cipher and the
     * key are fixed and the plaintext can be anything, so openssl itself failed.
     */
    public static function encryptionFailed(): self
    {
        return new self('Could not encrypt the connect state');
    }

    /**
     * The stored state could not be read. Usually a changed or lost encryption key.
     */
    public static function decryptionFailed(): self
    {
        return new self('Could not decrypt the stored connect state, check the encryption key');
    }

    /**
     * A value the caller passed cannot be used.
     */
    public static function invalidArgument(string $reason): self
    {
        return new self($reason);
    }

    /**
     * This PHP installation cannot run Connect.
     */
    public static function unsupportedRuntime(string $reason): self
    {
        return new self($reason);
    }

    /**
     * A call to MyParcel failed. Carries what came back, so the caller can read an error code or a
     * DPoP-Nonce header off it.
     *
     * @param int                        $statusCode
     * @param array<string, mixed>       $headers    Header names in lower case.
     * @param array<string, mixed>|null  $body       The decoded response body.
     * @param \Throwable|null            $previous   What the HTTP layer raised. Its message names
     *                                               the method, the URL and part of the body,
     *                                               which this message does not carry.
     */
    public static function httpError(
        int $statusCode,
        array $headers = [],
        ?array $body = null,
        ?Throwable $previous = null
    ): self {
        $wireValue = isset($body['error']) && is_string($body['error']) ? $body['error'] : null;

        $exception                  = new self(sprintf('MyParcel answered %d', $statusCode), 0, $previous);
        $exception->errorCode       = $wireValue;
        $exception->statusCode      = $statusCode;
        $exception->responseHeaders = $headers;
        $exception->responseBody    = $body;

        return $exception;
    }

    /**
     * The value as MyParcel wrote it, for a log line. Null when the failure was local.
     */
    public function getErrorCode(): ?string
    {
        return $this->errorCode;
    }

    /**
     * The HTTP status, when the failure was a call to MyParcel.
     */
    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }

    /**
     * @return array<string, mixed>
     */
    public function getResponseHeaders(): array
    {
        return $this->responseHeaders;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getResponseBody(): ?array
    {
        return $this->responseBody;
    }

    private static function withCode(string $errorCode, string $message): self
    {
        $exception            = new self($message);
        $exception->errorCode = $errorCode;

        return $exception;
    }
}
