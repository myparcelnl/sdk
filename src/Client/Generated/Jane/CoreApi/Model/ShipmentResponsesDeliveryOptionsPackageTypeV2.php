<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

enum ShipmentResponsesDeliveryOptionsPackageTypeV2 : string
{
    case Package = 'package';
    case Mailbox = 'mailbox';
    case DigitalStamp = 'digital_stamp';
    case Pallet = 'pallet';
    case SmallPackage = 'package_small';
}