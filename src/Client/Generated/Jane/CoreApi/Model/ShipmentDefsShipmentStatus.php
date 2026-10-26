<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

enum ShipmentDefsShipmentStatus : int
{
    case PendingConcept = 1;
    case PendingRegistered = 2;
    case EnrouteHandedToCarrier = 3;
    case EnrouteSorting = 4;
    case EnrouteDistribution = 5;
    case DeliveredAtCustoms = 6;
    case Delivered = 7;
    case InPickupLocation = 8;
    case PickedUp = 9;
    case ReturnShipmentDelivered = 10;
    case ReturnShipmentInPickupLocation = 11;
    case PrintedUnpaidLabel = 12;
    case Credit = 13;
    case PrintedStamp = 14;
    case PrintedExternalShipment = 15;
    case Expired = 16;
    case Cancelled = 17;
    case PrintedUntrackedShipment = 18;
    case DeliveredAtAgreedLocation = 19;
    case Inactive = 98;
    case Deleted = 99;
    case CreditRejected = 100;
    case CreditApproved = 101;
    case LegacyPendingConcept = 30;
    case LegacyPendingRegistered = 31;
    case LegacyEnrouteHandedToCarrier = 32;
    case LegacyEnrouteSorting = 33;
    case LegacyEnrouteDistribution = 34;
    case LegacyDeliveredAtCustoms = 35;
    case LegacyDelivered = 36;
    case LegacyInPickupLocation = 37;
    case LegacyPickedUp = 38;
}