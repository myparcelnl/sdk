<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

enum RefShipmentTransactionStatus : string
{
    case Pending = 'pending';
    case Unpaid = 'unpaid';
    case Paid = 'paid';
}