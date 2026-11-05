<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

enum RefTypesDeliveryTypeV2 : string
{
    case Morning = 'MORNING_DELIVERY';
    case Standard = 'STANDARD_DELIVERY';
    case Evening = 'EVENING_DELIVERY';
    case Pickup = 'PICKUP_DELIVERY';
    case SameDay = 'SAME_DAY_DELIVERY';
    case Express = 'EXPRESS_DELIVERY';
    case EarlyMorning = 'EARLY_MORNING_DELIVERY';
}