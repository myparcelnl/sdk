<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Concerns;

use RuntimeException;

/**
 * Fails with a readable message on a host without ext-openssl.
 *
 * The extension is a suggest and not a require, because only MyParcel Connect needs it and the SDK
 * ships to hosts that do not have it. Without this check the caller gets a fatal error for an
 * undefined function, which a plugin catching RuntimeException cannot handle.
 */
trait RequiresOpenssl
{
    /**
     * @throws \RuntimeException
     */
    private static function assertOpensslIsLoaded(): void
    {
        if (!extension_loaded('openssl')) {
            throw new RuntimeException('MyParcel Connect needs the openssl PHP extension');
        }
    }
}
