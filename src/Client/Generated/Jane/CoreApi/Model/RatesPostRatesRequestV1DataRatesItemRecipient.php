<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

class RatesPostRatesRequestV1DataRatesItemRecipient
{
    /**
     * Alpha-2 Country code of the recipient.
     *
     * @var string
     */
    public string $cc;
    /**
     * Postal code of the recipient.
     *
     * @var string
     */
    public string $postalCode;
    /**
     * Whether this is a business address.
     *
     * @var bool
     */
    public bool $isBusiness;
}