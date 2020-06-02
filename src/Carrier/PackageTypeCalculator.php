<?php

namespace Gett\MyParcel\Carrier;

use Gett\MyParcel\Constant;
use Gett\MyParcel\Service\CarrierConfigurationProvider;

class PackageTypeCalculator
{
    public static function getOrderPackageType(int $id_order, int $id_carrier)
    {
        $package_types = array_unique(self::getOrderProductsPackageTypes($id_order));

        if ($package_types) {
            return min($package_types);
        }

        $packageType = CarrierConfigurationProvider::get(
            $id_carrier, Constant::MY_PARCEL_PACKAGE_TYPE_CONFIGURATION_NAME
        );

        return $packageType ?: 1;
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
            if ($item['name'] == 'MY_PARCEL_PACKAGE_TYPE' && $item['value']) {
                $package_types[$item['id_product']] = $item['value'];
            }
        }

        return $package_types;
    }
}
