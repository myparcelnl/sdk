<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

enum RefShipmentShipmentTypeV2 : string
{
    case Express = 'EXPRESS';
    case Fulfilment = 'FULFILMENT';
    case LabelPackage = 'LABEL_PACKAGE';
    case MultiCollo = 'MULTI_COLLO';
    case Return = 'RETURN';
    case ReturnErs = 'RETURN_ERS';
    case ReturnSpecial = 'RETURN_SPECIAL';
    case Standard = 'STANDARD';
}