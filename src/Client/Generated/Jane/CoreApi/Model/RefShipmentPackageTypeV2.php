<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

enum RefShipmentPackageTypeV2 : string
{
    case Package = 'PACKAGE';
    case Mailbox = 'MAILBOX';
    case Unfranked = 'UNFRANKED';
    case DigitalStamp = 'DIGITAL_STAMP';
    case Pallet = 'PALLET';
    case SmallPackage = 'SMALL_PACKAGE';
    case Envelope = 'ENVELOPE';
}