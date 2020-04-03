<?php

namespace Gett\MyParcel\Module\Hooks;

trait DisplayAdminProductsExtra
{
    public function hookActionProductUpdate()
    {
        \Db::getInstance()->execute("DELETE FROM ". _DB_PREFIX_ ."myparcel_product_configuration WHERE id_product = '".\Tools::getValue('id_product')."' ");
        foreach ($_POST as $key => $item) {
            if (stripos($key, 'MY_PARCEL') !== false) {
                \Db::getInstance()->insert('myparcel_product_configuration',[
                    'id_product' => \Tools::getValue('id_product'),
                    'name' => $key,
                    'value' => $item
                ]);
            }
        }
    }

    public function hookDisplayAdminProductsExtra($params)
    {
        return $this->display($this->name, 'views/templates/admin/hook/products_form.tpl');
    }
}

