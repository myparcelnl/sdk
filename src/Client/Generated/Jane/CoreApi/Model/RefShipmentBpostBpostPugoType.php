<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

enum RefShipmentBpostBpostPugoType : int
{
    case Unknown = 0;
    case PostOffice = 1;
    case PostPoint = 2;
    case ParcelLocker = 4;
    case ClickCollect = 8;
    case ParcelPoint = 16;
}