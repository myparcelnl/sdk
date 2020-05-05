<?php

namespace Gett\MyParcel\Module\Hooks;

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
        $this->context->smarty->assign(
                [
                    'params' => $this->getProductSettings($params['id_product']),
                    'countries' => \Country::getCountries(\Context::getContext()->language->id)
                ]
        );

        return $this->display($this->name, 'views/templates/admin/hook/products_form.tpl');
    }

    private function getProductSettings(int $id_product)
    {
        $result = \Db::getInstance()->executeS('SELECT * FROM ' . _DB_PREFIX_ . 'myparcel_product_configuration WHERE id_product = "'.$id_product.'" ');
        $return = [];
        foreach ($result as $item) {
            $return[$item['name']] = $item['value'] ? $item['value'] : 0;
        }

        return $return;
    }
}
