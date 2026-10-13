<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Connect;

/**
 * The scopes /connect/start accepts.
 *
 * A scope is a permission the merchant grants. Ask for all of them unless you know you need fewer,
 * see ConnectConfig::withScopes().
 *
 * Hand written, and it stays that way: the spec types the scope parameter as a plain string, so there
 * is no generated list to take these from.
 */
class ConnectScope
{
    /**
     * The only scope this SDK asks for.
     *
     * The three below are the ones the spec's scope parameter documents. Both lists are accepted by
     * /connect/start, so they stay commented rather than removed.
     */
    public const INTEGRATION = 'integration';

    // public const READ_CAPABILITIES = 'read:capabilities';

    // public const WRITE_ORDERS = 'write:orders';

    // public const WRITE_PRODUCTS = 'write:products';

    /**
     * @return string[]
     */
    public static function getAllowableEnumValues(): array
    {
        return [
            self::INTEGRATION,
            // self::READ_CAPABILITIES,
            // self::WRITE_ORDERS,
            // self::WRITE_PRODUCTS,
        ];
    }
}
