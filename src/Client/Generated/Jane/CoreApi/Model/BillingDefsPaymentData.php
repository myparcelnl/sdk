<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

class BillingDefsPaymentData
{
    /**
     * @var string
     */
    public string $id;
    /**
     * @var string
     */
    public string $hash;
    /**
     * @var int
     */
    public int $type;
    /**
     * @var string
     */
    public string $notificationHash;
    /**
     * @var string
     */
    public string $originalNotificationHash;
    /**
     * @var BillingDefsPaymentDataPaid|int
     */
    public BillingDefsPaymentDataPaid|int $paid;
    /**
     * Price amount in a specific currency
     *
     * @var CommonDefsPrice
     */
    public CommonDefsPrice $price;
    /**
     * @var string|null
     */
    public ?string $paymentUrl;
    /**
     * @var BillingDefsPaymentDataInvoices
     */
    public BillingDefsPaymentDataInvoices $invoices;
}