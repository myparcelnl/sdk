<?php

namespace Gett\MyparcelBE\Carrier;

use Carrier;
use Cart;
use Configuration;
use Gett\MyparcelBE\Constant;
use Gett\MyparcelBE\Module\Carrier\ExclusiveField;
use Gett\MyparcelBE\Service\CarrierConfigurationProvider;
use Gett\MyparcelBE\Service\ProductConfigurationProvider;
use Order;

class PackageTypeCalculator extends AbstractPackageCalculator
{
    public function isMyParcelCarrier(int $idCarrier): bool
    {
        $allowedCarriers = array_map('intval', [
            Configuration::get(Constant::DPD_CONFIGURATION_NAME),
            Configuration::get(Constant::BPOST_CONFIGURATION_NAME),
            Configuration::get(Constant::POSTNL_CONFIGURATION_NAME),
        ]);

        return in_array($idCarrier, $allowedCarriers);
    }

    public function getOrderPackageType(int $id_order, int $id_carrier): int
    {
        $package_types = array_unique($this->getOrderProductsPackageTypes($id_order));

        if (!empty($package_types)) {
            $order = new Order($id_order);
            $cart = new Cart($order->id_cart);
            $weight = $cart->getTotalWeight();

            return $this->getProductsPackageType($package_types, $weight);
        }

        $packageType = (int) CarrierConfigurationProvider::get(
            $id_carrier, Constant::PACKAGE_TYPE_CONFIGURATION_NAME
        );

        return $packageType ?: 1;
    }

    public function allowDeliveryOptions(Cart $cart, string $countryIso): bool
    {
        if (empty($cart->id) || empty($cart->id_carrier)) {
            return false;
        }
        $carrier = new Carrier($cart->id_carrier);
        if (empty($carrier->id)) {
            return false;
        }

        $carrierPackageTypes = $this->getCarrierPackageTypes($carrier, $countryIso);
        if (empty($carrierPackageTypes)) {
            return false;
        }
        // If only parcel type is set then return true
        if (count($carrierPackageTypes) === 1 && $carrierPackageTypes[0] === Constant::PACKAGE_TYPE_PACKAGE) {
            return true;
        }

        $productsPackageTypes = $this->getProductsPackageTypes($cart);
        if (empty($productsPackageTypes)) {
            return true;
        }

        // 1. At least 1 product in cart is of type parcel, regardless of weight: order is considered parcel
        if (in_array(Constant::PACKAGE_TYPE_PACKAGE, $productsPackageTypes)) {
            return true; // delivery options
        }

        // 2. Only products in cart of type letter, regardless of total weight: order is considered letter
        if (count($productsPackageTypes) === 1 && in_array(Constant::PACKAGE_TYPE_LETTER, $productsPackageTypes)) {
            return false; // no delivery options
        }

        // 3. Total weight is above 2 Kg, regardless of package types, order is considered parcel
        $weight = $cart->getTotalWeight();
        if ($weight >= Constant::PACKAGE_TYPE_WEIGHT_LIMIT) {
            return true; // delivery options
        }

        // 4. Products of type letter, digital stamp, mailbox package AND total weight is less than 2 Kg
        return false; // no delivery options
    }

    private function getOrderProductsPackageTypes(int $id_order): array
    {
        $result = $this->getOrderProductsConfiguration($id_order);
        $package_types = [];
        foreach ($result as $item) {
            if ($item['name'] == 'MYPARCELBE_PACKAGE_TYPE' && $item['value']) {
                $package_types[$item['id_product']] = (int) $item['value'];
            }
        }

        return $package_types;
    }

    private function getCarrierPackageTypes(Carrier $carrier, string $countryIso): array
    {
        $exclusiveField = new ExclusiveField();
        $carrierType = $exclusiveField->getCarrierType($carrier);
        $packageTypes = [];
        foreach (Constant::PACKAGE_TYPES as $packageType => $packageName) {
            if ($exclusiveField->isAvailable(
                $countryIso,
                $carrierType,
                Constant::PACKAGE_TYPE_CONFIGURATION_NAME,
                $packageType
            )) {
                $packageTypes[] = $packageType;
            }
        }

        return $packageTypes;
    }

    private function getProductsPackageTypes(Cart $cart): array
    {
        $products = $cart->getProducts();
        if (empty($products)) {
            return [];
        }
        $types = [];
        foreach ($products as $product) {
            $type = (int) ProductConfigurationProvider::get(
                (int) $product['id_product'],
                Constant::PACKAGE_TYPE_CONFIGURATION_NAME,
                Constant::PACKAGE_TYPE_PACKAGE
            );
            if (!in_array($type, $types)) {
                $types[] = $type;
            }
        }

        return $types;
    }

    private function getProductsPackageType(array $productsPackageTypes, $weight): int
    {
        // 1. At least 1 product in cart is of type parcel, regardless of weight: order is considered parcel
        if (in_array(Constant::PACKAGE_TYPE_PACKAGE, $productsPackageTypes)) {
            return Constant::PACKAGE_TYPE_PACKAGE;
        }

        // 2. Only products in cart of type letter, regardless of total weight: order is considered letter
        if (count($productsPackageTypes) === 1 && in_array(Constant::PACKAGE_TYPE_LETTER, $productsPackageTypes)) {
            return Constant::PACKAGE_TYPE_LETTER;
        }

        // 3. Total weight is above 2 Kg, regardless of package types, order is considered parcel
        if ($weight >= Constant::PACKAGE_TYPE_WEIGHT_LIMIT) {
            return Constant::PACKAGE_TYPE_PACKAGE;
        }

        // 4. At least 1 product in cart is of type mailbox package AND total weight is less than 2 Kg: order is considered mailbox package
        if (in_array(Constant::PACKAGE_TYPE_MAILBOX, $productsPackageTypes)) {
            return Constant::PACKAGE_TYPE_MAILBOX;
        }

        // 5. At least 1 product in cart is of type digital stamp AND total weight is less than 2 Kg: order is considered digital stamp
        if (in_array(Constant::PACKAGE_TYPE_DIGITAL_STAMP, $productsPackageTypes)) {
            return Constant::PACKAGE_TYPE_DIGITAL_STAMP;
        }

        // Fall back to Package
        return Constant::PACKAGE_TYPE_PACKAGE;
    }
}
