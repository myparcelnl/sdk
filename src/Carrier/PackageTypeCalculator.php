<?php

namespace Gett\MyParcel\Carrier;

class PackageTypeCalculator
{
    public static function getOrderPackageType(int $id_order, int $default_package_type)
    {
        $package_types = array_unique(self::getOrderProductsPackageTypes($id_order));

        if ($package_types) {
            return min($package_types);
        }

        return $default_package_type;
    }

    private static function getOrderProductsPackageTypes(int $id_order)
    {
        $sql = new \DbQueryCore();
        $sql->select('mpc.*');
        $sql->from('order_detail', 'od');
        $sql->innerJoin('myparcel_product_configuration', 'mpc', 'od.product_id = mpc.id_product');
        $sql->where('id_order = "' . pSQL($id_order) . '" ');
        $result = \Db::getInstance()->executeS($sql);
        $package_types = [];
        foreach ($result as $item) {
            if ($item['name'] == 'MY_PARCEL_PACKAGE_TYPE') {
                $package_types[$item['id_product']] = $item['value'];
            }
        }

        return $package_types;
    }

}