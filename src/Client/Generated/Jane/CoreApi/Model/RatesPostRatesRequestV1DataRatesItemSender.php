<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

class RatesPostRatesRequestV1DataRatesItemSender
{
    /**
     * Alpha-2 Country code of the sender.
     *
     * @var string
     */
    public string $cc;
    /**
     * Postal code of the sender.
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