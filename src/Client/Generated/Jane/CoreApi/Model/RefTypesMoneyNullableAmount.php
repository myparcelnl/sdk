<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

class RefTypesMoneyNullableAmount
{
    /**
     * The currency as uppercase 3-letter ISO 4217 code.
     *
     * @var RefTypesMoneyPropertiesCurrency|string
     */
    public RefTypesMoneyPropertiesCurrency|string $currency;
    /**
     * The amount in the lowest denomination of the currency (e.g. cents for EUR), or null when the requester may not read price amounts.
     *
     * @var null|int|string
     */
    public $amount;
}