<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

enum OrderStatus : string
{
    case Canceled = 'CANCELED';
    case Completed = 'COMPLETED';
    case Discovered = 'DISCOVERED';
    case ImportRequested = 'IMPORT_REQUESTED';
    case Open = 'OPEN';
    case Processing = 'PROCESSING';
    case Returning = 'RETURNING';
}