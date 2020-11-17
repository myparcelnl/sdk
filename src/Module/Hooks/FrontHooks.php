<?php

namespace Gett\MyparcelBE\Module\Hooks;

use Address;
use Configuration;
use Currency;
use Db;
use Product;
use Tools;
use Validate;

trait FrontHooks
{
    public function hookActionCarrierProcess($params)
    {
        $options = Tools::getValue('myparcel-delivery-options');
        $optionsObj = null;
        if (!empty($options)) {
            $optionsObj = json_decode($options);
            // Signature is required for pickup delivery type
            if (!empty($optionsObj->isPickup)) {
                $optionsObj->shipmentOptions = new \StdClass();
                $optionsObj->shipmentOptions->signature = true;
            }
            $options = json_encode($optionsObj);
        }
        if (Tools::isSubmit('confirmDeliveryOption') && !empty($options)) {
            Db::getInstance(_PS_USE_SQL_SLAVE_)->insert(
                'myparcelbe_delivery_settings',
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
                $optionsObj->carrier = str_replace(' ', '', strtolower($carrier->name));
                Db::getInstance(_PS_USE_SQL_SLAVE_)->insert(
                    'myparcelbe_delivery_settings',
                    ['id_cart' => $params['cart']->id, 'delivery_settings' => json_encode($optionsObj)],
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
            $this->context->controller->addJs($this->_path . 'views/dist/front.js');
        }
    }

    public function hookDisplayCarrierExtraContent($params)
    {
        $address = new Address($this->context->cart->id_address_delivery);
        if (Validate::isLoadedObject($address)) {
            $address->address1 = preg_replace('/[^0-9]/', '', $address->address1);

            $include_taxes = !Product::getTaxCalculationMethod((int) $this->context->cart->id_customer)
                && (int) Configuration::get('PS_TAX');
            $display_taxes_label = (Configuration::get('PS_TAX') && !Configuration::get('AEUC_LABEL_TAX_INC_EXC'));

            $shipping_cost = Tools::displayPrice(
                $this->carrierStandardShippingCost[$params['carrier']['id']] ?? $this->cartCarrierStandardShippingCost,
                Currency::getCurrencyInstance((int) $this->context->cart->id_currency),
                false
            );

            if ($include_taxes) {
                if ($display_taxes_label) {
                    $shipping_cost = $this->context->getTranslator()->trans(
                        '%price% tax incl.',
                        array('%price%' => $shipping_cost),
                        'Shop.Theme.Checkout'
                    );
                }
            } else {
                if ($display_taxes_label) {
                    $shipping_cost = $this->context->getTranslator()->trans(
                        '%price% tax excl.',
                        array('%price%' => $shipping_cost),
                        'Shop.Theme.Checkout'
                    );
                }
            }

            $this->context->smarty->assign([
                'address' => $address,
                'delivery_settings' => $this->getDeliverySettingsByCart((int) $this->context->cart->id),
                'shipping_cost' => $shipping_cost,
                'carrier' => $params['carrier'],
            ]);

            return $this->display($this->name, 'views/templates/hook/carrier.tpl');
        }

        return '';
    }
}
