<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Connect;

/**
 * The scopes /connect/start accepts.
 *
 * A scope is a permission the merchant grants. Ask for all three unless you know you need fewer, see
 * ConnectConfig::withScopes().
 *
 * Hand written, and it stays that way: the spec types the scope parameter as a plain string, so there
 * is no generated list to take these from.
 */
class ConnectScope
{
    /**
     * Everything an integration needs beyond the two below.
     */
    public const INTEGRATION = 'integration';

    public const WRITE_ORDERS = 'write:orders';

    public const WRITE_PRODUCTS = 'write:products';

    /**
     * @return string[]
     */
    public static function getAllowableEnumValues(): array
    {
        return [
            self::INTEGRATION,
            self::WRITE_ORDERS,
            self::WRITE_PRODUCTS,
        ];
    }
}
