<?php

namespace Gett\MyparcelBE\Module\Hooks;

use Address;
use Db;
use Tools;
use Validate;

trait FrontHooks
{
    public function hookActionCarrierProcess($params)
    {
        $options = Tools::getValue('myparcel-delivery-options');
        if (Tools::isSubmit('confirmDeliveryOption') && !empty($options)) {
            Db::getInstance(_PS_USE_SQL_SLAVE_)->insert(
                'myparcel_delivery_settings',
                ['id_cart' => $params['cart']->id, 'delivery_settings' => $options],
                false,
                true,
                Db::REPLACE
            );
        }
        $action = Tools::getValue('action');
        $id_carrier = Tools::getValue('delivery_option');
        if ($action == 'selectDeliveryOption' && !empty($options) && !empty($id_carrier)) {
            if (is_array($id_carrier)) {
                $id_carrier = (int) reset($id_carrier);
            }
            $carrier = new \Carrier($id_carrier);
            if (Validate::isLoadedObject($carrier)) {
                $options_decoded = json_decode($options);
                $options_decoded->carrier = str_replace(' ', '', strtolower($carrier->name));
                Db::getInstance(_PS_USE_SQL_SLAVE_)->insert(
                    'myparcel_delivery_settings',
                    ['id_cart' => $params['cart']->id, 'delivery_settings' => json_encode($options_decoded)],
                    false,
                    true,
                    Db::REPLACE
                );
            }
        }
    }

    public function hookDisplayHeader()
    {
        if ($this->context->controller instanceof \OrderController) {
            $this->context->controller->addCss($this->_path . 'views/css/myparcel.css');
            $this->context->controller->addJs($this->_path . 'views/dist/myparcel.js');
            $this->context->controller->addJs($this->_path . 'dist/front.bundle.js');
        }
    }

    public function hookDisplayCarrierExtraContent()
    {
        $address = new Address($this->context->cart->id_address_delivery);
        if (Validate::isLoadedObject($address)) {
            $address->address1 = preg_replace('/[^0-9]/', '', $address->address1);

            $this->context->smarty->assign([
                'address' => $address,
                'delivery_settings' => $this->getDeliverySettingsByCart((int) $this->context->cart->id),
            ]);

            return $this->display($this->name, 'views/templates/hook/carrier.tpl');
        }

        return '';
    }
}
