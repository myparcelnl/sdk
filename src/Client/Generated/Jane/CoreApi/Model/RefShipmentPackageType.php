<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

enum RefShipmentPackageType : int
{
    case Package = 1;
    case Mailbox = 2;
    case Unfranked = 3;
    case DigitalStamp = 4;
    case Pallet = 5;
    case SmallPackage = 6;
    case Envelope = 7;
}