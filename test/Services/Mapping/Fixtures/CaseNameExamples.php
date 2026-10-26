<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Mapping\Fixtures;

/**
 * Case names in the forms that a spec's x-enum-varnames can have.
 */
enum CaseNameExamples: string
{
    case Package = 'package';
    case DhlCheapCargo = 'dhl';
    case DHLForYou = 'dhlforyou';
    case V2Only = 'v2';
}
