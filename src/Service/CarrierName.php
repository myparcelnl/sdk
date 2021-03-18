<?php

namespace Gett\MyparcelBE\Service;

use Carrier;
use Configuration;
use Gett\MyparcelBE\Constant;
use Validate;

class CarrierName
{
    public function get(int $idCarrier): string
    {
        $carrier = new Carrier($idCarrier);
        if (!Validate::isLoadedObject($carrier)) {
            return '';
        }
        if ($carrier->id_reference == Configuration::get(Constant::POSTNL_CONFIGURATION_NAME)) {
            return Constant::POSTNL_CARRIER_NAME;
        }

        if ($carrier->id_reference == Configuration::get(Constant::BPOST_CONFIGURATION_NAME)) {
            return Constant::BPOST_CARRIER_NAME;
        }

        if ($carrier->id_reference == Configuration::get(Constant::DPD_CONFIGURATION_NAME)) {
            return Constant::DPD_CARRIER_NAME;
        }

        return '';
    }
}
