<?php

namespace Gett\MyparcelBE\Module\Carrier\Provider;

use Carrier;
use Gett\MyparcelBE\Constant;
use Gett\MyparcelBE\Module\Carrier\ExclusiveField;
use Module;

class CarrierSettingsProvider
{
    protected $module;

    public function __construct($module)
    {
        $this->module = $module;
    }

    public function provide(int $carrierId)
    {
        $exclusiveField = new ExclusiveField();
        $carrier = new Carrier($carrierId);
        $carrierType = $exclusiveField->getCarrierType($carrier);
        $countryIso = $this->module->getModuleCountry();
        $carrierSettings = Constant::CARRIER_EXCLUSIVE[$carrierType];
        $carrierLabelSettings = [
            'delivery' => [],
            'return' => []
        ];
        foreach (Constant::SINGLE_LABEL_CREATION_OPTIONS as $key => $field) {
            $carrierLabelSettings['delivery'][$key] = $carrierSettings[$field][$countryIso];
            $carrierLabelSettings['return'][$key] = $carrierSettings['return_' . $field][$countryIso];
        }
        $carrierLabelSettings['delivery']['ALLOW_FORM'] = $carrierSettings['ALLOW_DELIVERY_FORM'][$countryIso];
        $carrierLabelSettings['return']['ALLOW_FORM'] = $carrierSettings['ALLOW_RETURN_FORM'][$countryIso];

        return $carrierLabelSettings;
    }
}
