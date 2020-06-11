<?php

namespace Gett\MyParcel\Module\Hooks;

use Gett\MyParcel\Constant;

trait DisplayAdminProductsExtra
{
    public function hookActionProductUpdate()
    {
        \Db::getInstance()->execute('DELETE FROM ' . _DB_PREFIX_ . "myparcel_product_configuration WHERE id_product = '" . pSQL(\Tools::getValue('id_product')) . "' ");
        foreach ($_POST as $key => $item) {
            if (stripos($key, 'MY_PARCEL') !== false) {
                \Db::getInstance()->insert('myparcel_product_configuration', [
                    'id_product' => \Tools::getValue('id_product'),
                    'name' => $key,
                    'value' => $item,
                ]);
            }
        }
    }

    public function hookDisplayAdminProductsExtra($params)
    {
        $params = $this->getProductSettings((int) $params['id_product']);

        $this->context->smarty->assign(
            [
                'params' => $params,
                'countries' => \Country::getCountries(\Context::getContext()->language->id),
                'isBE' => $this->isBE(),
            ]
        );

        return $this->display($this->name, 'views/templates/admin/hook/products_form.tpl');
    }

    private function getProductSettings(int $id_product)
    {
        $result = \Db::getInstance()->executeS('SELECT * FROM ' . _DB_PREFIX_ . 'myparcel_product_configuration WHERE id_product = "' . $id_product . '" ');
        $return = [];
        foreach ($result as $item) {
            $return[$item['name']] = $item['value'] ? $item['value'] : 0;
        }

        if (!$return[Constant::CUSTOMS_FORM_CONFIGURATION_NAME]) {
            $return[Constant::CUSTOMS_FORM_CONFIGURATION_NAME] = \Configuration::get(Constant::CUSTOMS_FORM_CONFIGURATION_NAME);
        }

        if (!$return[Constant::CUSTOMS_ORIGIN_CONFIGURATION_NAME]) {
            $return[Constant::CUSTOMS_ORIGIN_CONFIGURATION_NAME] = \Configuration::get(Constant::CUSTOMS_ORIGIN_CONFIGURATION_NAME);
        }

        return $return;
    }
}
