<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Connect;

use MyParcelNL\Sdk\Client\Generated\EcommerceApi\Model\ConnectTokenPost400Response;
use MyParcelNL\Sdk\Client\Generated\EcommerceApi\Model\ConnectTokenPost400ResponseAnyOf;
use MyParcelNL\Sdk\Client\Generated\EcommerceApi\Model\ConnectTokenPost400ResponseAnyOf1;
use MyParcelNL\Sdk\Client\Generated\EcommerceApi\Model\ConnectTokenPost400ResponseAnyOf2;
use MyParcelNL\Sdk\Client\Generated\EcommerceApi\Model\ConnectTokenPost401Response;
use MyParcelNL\Sdk\Client\Generated\EcommerceApi\Model\ConnectTokenPost500Response;

/**
 * The error values /connect/token and /connect/refresh return in the body.
 *
 * Only these come back as {"error": "..."}. A wrong request shape answers with a problem+json body
 * instead, which has no error member at all.
 *
 * One place to read them from. The generated client declares all six, but spread across six response
 * models with one value each, so there is nothing generated to import wholesale. Each constant here
 * points at the model that declares it, so a value the spec changes cannot drift from ours: the
 * constant names encode their values, so a renamed or reordered one fails to resolve at class load
 * rather than quietly changing.
 *
 * The aggregate getErrorAllowableValues() on those models is not usable, because the generator
 * collapses each anyOf and keeps only the last branch. The individual constants are correct.
 */
class ConnectErrorCode
{
    /**
     * The identity provider wants a nonce in the proof. Retry once with the DPoP-Nonce it sent back.
     */
    public const USE_DPOP_NONCE = ConnectTokenPost400Response::ERROR_USE_DPOP_NONCE;

    /**
     * At /connect/token: the code is unknown or expired. At /connect/refresh: the connection is gone.
     */
    public const INVALID_GRANT = ConnectTokenPost400ResponseAnyOf1::ERROR_INVALID_GRANT;

    /**
     * Our proof was refused. A retry with the same proof cannot help.
     */
    public const INVALID_DPOP_PROOF = ConnectTokenPost401Response::ERROR_INVALID_DPOP_PROOF;

    /**
     * MyParcel could not link the shop to a sales channel.
     */
    public const INVALID_SALES_CHANNEL = ConnectTokenPost400ResponseAnyOf2::ERROR_INVALID_SALES_CHANNEL;

    /**
     * Declared by the service but not thrown by it today.
     */
    public const INVALID_CODE = ConnectTokenPost400ResponseAnyOf::ERROR_INVALID_CODE;

    /**
     * Anything MyParcel could not complete.
     */
    public const SERVER_ERROR = ConnectTokenPost500Response::ERROR_SERVER_ERROR;

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
