<?php

namespace Gett\MyparcelBE\Carrier;

use Gett\MyparcelBE\Constant;
use Gett\MyparcelBE\Service\CarrierConfigurationProvider;

class PackageFormatCalculator extends AbstractPackageCalculator
{
    public function getOrderPackageFormat(int $id_order, int $id_carrier): int
    {
        $productPackageFormats = array_unique($this->getOrderProductsPackageFormats($id_order));
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

    private function getOrderProductsPackageFormats(int $id_order): array
    {
        $result = $this->getOrderProductsConfiguration($id_order);
        $packageFormats = [];
        foreach ($result as $item) {
            if ($item['name'] == Constant::PACKAGE_FORMAT_CONFIGURATION_NAME && $item['value']) {
                $packageFormats[$item['id_product']] = (int) $item['value'];
            }
        }

        return $packageFormats;
    }
}
