<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

enum PaymentStatus : string
{
    case Paid = 'PAID';
    case Pending = 'PENDING';
    case Unpaid = 'UNPAID';
}