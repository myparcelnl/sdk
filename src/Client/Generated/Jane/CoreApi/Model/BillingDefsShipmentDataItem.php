<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

class BillingDefsShipmentDataItem
{
    /**
     * Unique ID of a Shipment.
     *
     * @var int
     */
    public int $shipmentId;
    /**
     * Money amount with currency
     *
     * @var RefTypesMoney
     */
    public RefTypesMoney $price;
    /**
     * Money amount with currency
     *
     * @var RefTypesMoney
     */
    public RefTypesMoney $priceAfterVat;
}