<?php

namespace Gett\MyparcelBE\Carrier;

use Db;
use DbQuery;
use Gett\MyparcelBE\Constant;
use Gett\MyparcelBE\Service\CarrierConfigurationProvider;

class PackageFormatCalculator
{
    public static function getOrderPackageFormat(int $id_order, int $id_carrier): int
    {
        $productPackageFormats = array_unique(self::getOrderProductsPackageFormats($id_order));
        $largePackageTypeIndex = Constant::PACKAGE_FORMAT_LARGE_INDEX;

        if ($productPackageFormats) {
            if (isset($productPackageFormats[$largePackageTypeIndex])) {
                return $largePackageTypeIndex;
            }

            return min($productPackageFormats);
        }

        $packageFormat = CarrierConfigurationProvider::get(
            $id_carrier, Constant::PACKAGE_FORMAT_CONFIGURATION_NAME
        );

        return $packageFormat ?: 1;
    }

    private static function getOrderProductsPackageFormats(int $id_order): array
    {
        $sql = new DbQuery();
        $sql->select('mpc.*');
        $sql->from('order_detail', 'od');
        $sql->innerJoin('myparcelbe_product_configuration', 'mpc', 'od.product_id = mpc.id_product');
        $sql->where('id_order = "' . pSQL($id_order) . '" ');
        $result = Db::getInstance()->executeS($sql);
        $packageFormats = [];
        foreach ($result as $item) {
            if ($item['name'] == Constant::PACKAGE_FORMAT_CONFIGURATION_NAME && $item['value']) {
                $packageFormats[$item['id_product']] = (int) $item['value'];
            }
        }

        return $packageFormats;
    }
}
