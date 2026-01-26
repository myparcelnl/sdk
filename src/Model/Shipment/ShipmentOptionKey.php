<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Shipment;

final class ShipmentOptionKey
{
    public const SIGNATURE          = 'signature';
    public const ONLY_RECIPIENT     = 'only_recipient';
    public const AGE_CHECK          = 'age_check';
    public const RECEIPT_CODE       = 'receipt_code';
    public const LARGE_FORMAT       = 'large_format';
    public const PRINTERLESS_RETURN = 'printerless_return';
    public const COLLECT            = 'collect';
    public const RETURN             = 'return';

    private function __construct()
    {
    }
}
