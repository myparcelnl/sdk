<?php

namespace Gett\MyparcelBE\Label;

use Gett\MyparcelBE\Carrier\PackageFormatCalculator;
use Gett\MyparcelBE\Carrier\PackageTypeCalculator;
use Gett\MyparcelBE\Constant;
use Gett\MyparcelBE\OrderLabel;
use Gett\MyparcelBE\Service\CarrierConfigurationProvider;
use Gett\MyparcelBE\Service\ProductConfigurationProvider;

class LabelOptionsResolver
{
    public function getLabelOptions(array $params)
    {
        $delivery_settings = OrderLabel::getOrderDeliveryOptions($params['id_order']);

        $order_products = OrderLabel::getOrderProducts($params['id_order']);

        return json_encode([
            'package_type' => (new PackageTypeCalculator())->getOrderPackageType($params['id_order'], $params['id_carrier']),
            'package_format' => (new PackageFormatCalculator())->getOrderPackageFormat($params['id_order'], $params['id_carrier']),
            'only_to_recipient' => $this->getOnlyToReciepient($delivery_settings, $order_products, $params['id_carrier']),
            'age_check' => $this->getAgeCheck($order_products, $params['id_carrier']),
            'signature' => $this->getSignature($delivery_settings, $order_products, $params['id_carrier']),
            'insurance' => $this->getInsurance($order_products, $params['id_carrier']),
            'return_undelivered' => $this->getReturnUndelivered($order_products, $params['id_carrier']),
        ]);
    }

    private function getOnlyToReciepient($delivery_settings, array $products, int $id_carrier)
    {
        if (isset($delivery_settings->shipmentOptions->only_recipient) && $delivery_settings->shipmentOptions->only_recipient === true) {
            return true;
        }

        foreach ($products as $product) {
            if (ProductConfigurationProvider::get($product['product_id'], Constant::ONLY_RECIPIENT_CONFIGURATION_NAME, false)) {
                return true;
            }
        }

        return CarrierConfigurationProvider::get($id_carrier, Constant::ONLY_RECIPIENT_CONFIGURATION_NAME, false);
    }

    private function getAgeCheck(array $products, int $id_carrier)
    {
        foreach ($products as $product) {
            if (ProductConfigurationProvider::get($product['product_id'], Constant::AGE_CHECK_CONFIGURATION_NAME)) {
                return true;
            }
        }

        return CarrierConfigurationProvider::get($id_carrier, Constant::AGE_CHECK_CONFIGURATION_NAME, false);
    }

    private function getSignature($delivery_settings, array $products, int $id_carrier)
    {
        if (isset($delivery_settings->shipmentOptions->signature) && $delivery_settings->shipmentOptions->signature === true) {
            return true;
        }

        foreach ($products as $product) {
            if (ProductConfigurationProvider::get(
                $product['product_id'],
                Constant::SIGNATURE_REQUIRED_CONFIGURATION_NAME
            )) {
                return true;
            }
        }

        return CarrierConfigurationProvider::get($id_carrier, Constant::SIGNATURE_REQUIRED_CONFIGURATION_NAME, false);
    }

    private function getInsurance(array $products, int $id_carrier)
    {
        foreach ($products as $product) {
            if (ProductConfigurationProvider::get($product['product_id'], Constant::INSURANCE_CONFIGURATION_NAME)) {
                return true;
            }
        }

        return CarrierConfigurationProvider::get($id_carrier, Constant::INSURANCE_CONFIGURATION_NAME, false);
    }

    private function getReturnUndelivered(array $products, int $id_carrier)
    {
        foreach ($products as $product) {
            if (ProductConfigurationProvider::get($product['product_id'], Constant::RETURN_PACKAGE_CONFIGURATION_NAME)) {
                return true;
            }
        }

        return CarrierConfigurationProvider::get($id_carrier, Constant::RETURN_PACKAGE_CONFIGURATION_NAME, false);
    }
}
