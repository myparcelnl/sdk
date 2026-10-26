<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Mapping\Fixtures;

/**
 * A v2 name enum that a later spec could add, in the form that Jane generates.
 */
enum FutureV2Name: string
{
    case Future = 'FUTURE_SERVICE';
    case V2Only = 'V2_ONLY';
}
