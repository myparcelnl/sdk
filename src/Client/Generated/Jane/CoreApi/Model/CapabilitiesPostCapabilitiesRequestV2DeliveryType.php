<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

enum CapabilitiesPostCapabilitiesRequestV2DeliveryType : string
{
    case EveningDelivery = 'EVENING_DELIVERY';
    case ExpressDelivery = 'EXPRESS_DELIVERY';
    case EarlyMorningDelivery = 'EARLY_MORNING_DELIVERY';
    case MorningDelivery = 'MORNING_DELIVERY';
    case PickupDelivery = 'PICKUP_DELIVERY';
    case SameDayDelivery = 'SAME_DAY_DELIVERY';
    case StandardDelivery = 'STANDARD_DELIVERY';
}