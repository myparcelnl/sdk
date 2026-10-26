<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

enum ShipmentParametersDeliveryOptionsPackageType : string
{
    case Package = 'package';
    case Mailbox = 'mailbox';
    case DigitalStamp = 'digital_stamp';
    case SmallPackage = 'package_small';
    case Pallet = 'pallet';
}