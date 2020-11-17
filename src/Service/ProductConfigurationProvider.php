<?php

namespace Gett\MyparcelBE\Service;

class ProductConfigurationProvider
{
    public static $products = [];

    public static function get(int $id_product, string $param, $default = null)
    {
        if (!isset(static::$products[$id_product][$param])) {
            $result = \Db::getInstance()->executeS(
                'SELECT name, value FROM ' . _DB_PREFIX_ . "myparcelbe_product_configuration WHERE id_product = '{$id_product}' "
            );
            foreach ($result as $item) {
                static::$products[$id_product][$item['name']] = $item['value'];
            }
        }

        return isset(static::$products[$id_product][$param]) && static::$products[$id_product][$param] ? static::$products[$id_product][$param] : $default;
    }
}
