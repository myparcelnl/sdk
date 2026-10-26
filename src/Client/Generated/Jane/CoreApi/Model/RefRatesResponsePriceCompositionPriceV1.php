<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

class RefRatesResponsePriceCompositionPriceV1
{
    /**
     * @var string
     */
    public string $name;
    /**
     * @var string
     */
    public string $key;
    /**
     * Money amount with currency, where the amount may be null. The amount is null when the requester lacks permission to read price amounts (Permissions::BILLING_READ_PRICE_AMOUNTS).
     *
     * @var RefTypesMoneyNullableAmount
     */
    public RefTypesMoneyNullableAmount $price;
    /**
     * Money amount with currency, where the amount may be null. The amount is null when the requester lacks permission to read price amounts (Permissions::BILLING_READ_PRICE_AMOUNTS).
     *
     * @var RefTypesMoneyNullableAmount
     */
    public RefTypesMoneyNullableAmount $vat;
    /**
     * Money amount with currency, where the amount may be null. The amount is null when the requester lacks permission to read price amounts (Permissions::BILLING_READ_PRICE_AMOUNTS).
     *
     * @var RefTypesMoneyNullableAmount
     */
    public RefTypesMoneyNullableAmount $priceWithVat;
}