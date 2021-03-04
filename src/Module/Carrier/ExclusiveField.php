<?php

namespace Gett\MyparcelBE\Module\Carrier;

use Carrier;
use Configuration;
use Gett\MyparcelBE\Constant;

class ExclusiveField
{
    /**
     * @param string $countryIso Expected values: 'BE', 'NL'
     * @param string $carrierType Expected values: 'BPOST', 'DPD', 'POSTNL'
     * @param string $field As declared in Constant::CARRIER_CONFIGURATION_FIELDS
     * @param int|null $key When $field is array checks the available options by key index
     *
     * @return bool
     */
    public function isAvailable(string $countryIso, string $carrierType, string $field, int $key = null): bool
    {
        if (!isset(Constant::CARRIER_EXCLUSIVE[$carrierType][$field])) {
            return true;
        }
        if (empty(Constant::CARRIER_EXCLUSIVE[$carrierType][$field][$countryIso])) {
            return false;
        }
        if (is_array(Constant::CARRIER_EXCLUSIVE[$carrierType][$field][$countryIso])) {
            if (!$key || empty(Constant::CARRIER_EXCLUSIVE[$carrierType][$field][$countryIso][$key])) {
                return false;
            }
        }

        return true;
    }

    public function getCarrierType(Carrier $carrier): string
    {
        $carrierReference = (int) $carrier->id_reference;
        $carrierType = null;
        switch ($carrierReference) {
            case (int) Configuration::get(Constant::DPD_CONFIGURATION_NAME):
                $carrierType = 'DPD';
                break;
            case (int) Configuration::get(Constant::BPOST_CONFIGURATION_NAME):
                $carrierType = 'BPOST';
                break;
            case (int) Configuration::get(Constant::POSTNL_CONFIGURATION_NAME):
            default:
                $carrierType = 'POSTNL';
                break;
        }

        return $carrierType;
    }
}
