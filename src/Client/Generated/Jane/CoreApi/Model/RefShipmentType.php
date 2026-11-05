<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

enum RefShipmentType : int
{
    case Standard = 1;
    case Return = 2;
    case MultiCollo = 3;
    case LabelPackage = 4;
    case ReturnErs = 5;
    case ReturnSpecial = 6;
    case Express = 7;
    case Fulfilment = 8;
}