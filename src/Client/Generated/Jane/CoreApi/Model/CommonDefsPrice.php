<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

class CommonDefsPrice
{
    /**
     * The amount in the lowest denomination of the currency, e.g. cents for EUR.
     *
     * @var int|null
     */
    public ?int $amount;
    /**
     * The currency as uppercase 3-letter ISO 4217 code.
     *
     * @var CommonDefsPriceCurrency|string
     */
    public CommonDefsPriceCurrency|string $currency;
}