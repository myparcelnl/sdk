<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model;

class RefTypesMoney
{
    /**
     * The currency as uppercase 3-letter ISO 4217 code.
     *
     * @var RefTypesMoneyCurrency|string
     */
    public RefTypesMoneyCurrency|string $currency;
    /**
     * The amount in the lowest denomination of the currency, e.g. cents for EUR.
     *
     * @var int|string
     */
    public $amount;
}