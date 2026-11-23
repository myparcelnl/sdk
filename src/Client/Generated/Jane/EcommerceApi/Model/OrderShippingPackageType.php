<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model;

enum OrderShippingPackageType : string
{
    case DigitalStamp = 'DIGITAL_STAMP';
    case Envelope = 'ENVELOPE';
    case Mailbox = 'MAILBOX';
    case Package = 'PACKAGE';
    case Pallet = 'PALLET';
    case SmallPackage = 'SMALL_PACKAGE';
    case Unfranked = 'UNFRANKED';
}