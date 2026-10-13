<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Connect;

/**
 * The values MyParcel can put in the error parameter of the callback.
 *
 * The list is not closed: the callback relays anything the identity provider produced, so a value
 * outside this list has to be treated as a server error. ConnectException::fromCallbackError() does
 * that.
 *
 * Interim type, written from the MyParcel Connect integration documentation. Replace with the
 * generated model once the ecommerce OpenAPI spec is published. See INT-1837.
 */
class ConnectCallbackError
{
    /**
     * The merchant pressed deny, or their account lacks the order management feature.
     */
    public const ACCESS_DENIED = 'access_denied';

    /**
     * The merchant took longer than MyParcel allows. They have to start again.
     */
    public const SESSION_EXPIRED = 'session_expired';

    /**
     * MyParcel could not link the shop to a sales channel.
     */
    public const INVALID_SALES_CHANNEL = 'invalid_sales_channel';

    /**
     * Anything else MyParcel could not complete.
     */
    public const SERVER_ERROR = 'server_error';

    /**
     * @return string[]
     */
    public static function getAllowableEnumValues(): array
    {
        return [
            self::ACCESS_DENIED,
            self::SESSION_EXPIRED,
            self::INVALID_SALES_CHANNEL,
            self::SERVER_ERROR,
        ];
    }
}
