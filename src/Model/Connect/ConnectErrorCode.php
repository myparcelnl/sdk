<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Connect;

use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ConnectTokenPostJsonResponse500Error;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ConnectTokenPostResponse401Error;

/**
 * The error values /connect/token and /connect/refresh return in the body.
 *
 * Only these come back as {"error": "..."}. A wrong request shape answers with a problem+json body
 * instead, which has no error member at all.
 *
 * One place to read them from. The spec names the four values of a 400 inside an `anyOf` of inline
 * objects, and the generator makes no model and no enum for those. So these four are written here
 * as the spec has them. The 401 and the 500 have a generated enum each, and their constants point
 * at it.
 *
 * @todo Point the four at a generated enum when the spec names the values of a 400 in a schema of
 *       its own.
 */
class ConnectErrorCode
{
    /**
     * The identity provider wants a nonce in the proof. Retry once with the DPoP-Nonce it sent back.
     */
    public const USE_DPOP_NONCE = 'use_dpop_nonce';

    /**
     * At /connect/token: the code is unknown or expired. At /connect/refresh: the connection is gone.
     */
    public const INVALID_GRANT = 'invalid_grant';

    /**
     * Our proof was refused. A retry with the same proof cannot help.
     */
    public const INVALID_DPOP_PROOF = ConnectTokenPostResponse401Error::InvalidDpopProof->value;

    /**
     * MyParcel could not link the shop to a sales channel.
     */
    public const INVALID_SALES_CHANNEL = 'invalid_sales_channel';

    /**
     * Declared by the service but not thrown by it today.
     */
    public const INVALID_CODE = 'invalid_code';

    /**
     * Anything MyParcel could not complete.
     */
    public const SERVER_ERROR = ConnectTokenPostJsonResponse500Error::ServerError->value;

    /**
     * @return string[]
     */
    public static function getAllowableEnumValues(): array
    {
        return [
            self::USE_DPOP_NONCE,
            self::INVALID_GRANT,
            self::INVALID_DPOP_PROOF,
            self::INVALID_SALES_CHANNEL,
            self::INVALID_CODE,
            self::SERVER_ERROR,
        ];
    }
}
