<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Mapping\Fixtures;

/**
 * A legacy name enum that a later spec could add, in the form that Jane generates.
 */
enum FutureLegacyName: string
{
    case Future = 'future_service';
}
