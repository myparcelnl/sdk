<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

enum RefTypesDeliveryType : int
{
    case Morning = 1;
    case Standard = 2;
    case Evening = 3;
    case Pickup = 4;
    case SameDay = 6;
    case Express = 7;
    case EarlyMorning = 8;
}