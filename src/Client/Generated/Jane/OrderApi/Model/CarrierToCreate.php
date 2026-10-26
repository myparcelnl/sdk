<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

enum CarrierToCreate : string
{
    case Bpost = 'BPOST';
    case Brt = 'BRT';
    case CheapCargo = 'CHEAP_CARGO';
    case DhlEuroplus = 'DHL_EUROPLUS';
    case DhlForYou = 'DHL_FOR_YOU';
    case DhlFreight = 'DHL_FREIGHT';
    case DhlParcelConnect = 'DHL_PARCEL_CONNECT';
    case Dpd = 'DPD';
    case Gls = 'GLS';
    case Inpost = 'INPOST';
    case Postnl = 'POSTNL';
    case PosteItaliane = 'POSTE_ITALIANE';
    case Spring = 'SPRING';
    case Trunkrs = 'TRUNKRS';
    case UpsExpressSaver = 'UPS_EXPRESS_SAVER';
    case UpsStandard = 'UPS_STANDARD';
    case ViaTim = 'VIA_TIM';
}