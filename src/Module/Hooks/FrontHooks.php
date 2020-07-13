<?php

namespace Gett\MyParcel\Module\Hooks;

trait FrontHooks
{
    public function hookActionCarrierProcess($params)
    {
        if (\Tools::isSubmit('confirmDeliveryOption') && $options = \Tools::getValue('myparcel-delivery-options')) {
            \Db::getInstance(_PS_USE_SQL_SLAVE_)->insert(
                'myparcel_delivery_settings',
                ['id_cart' => $params['cart']->id, 'delivery_settings' => $options],
                false,
                true,
                \Db::REPLACE
            );
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
        $address = new \Address($this->context->cart->id_address_delivery);
        if (\Validate::isLoadedObject($address)) {
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
