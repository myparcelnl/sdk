<?php

use Gett\MyparcelBE\Constant;
use Gett\MyparcelBE\Service\CarrierConfigurationProvider;
use Gett\MyparcelBE\Service\DeliverySettingsProvider;

class MyParcelBECheckoutModuleFrontController extends ModuleFrontController
{
    public $requestOriginalShippingCost = false;

    public function postProcess()
    {
        $id_carrier = intval(Tools::getValue('id_carrier'));
        $params = (new DeliverySettingsProvider($this->module, $id_carrier, $this->context))->get();

        echo json_encode($params);
        exit;
    }
}
