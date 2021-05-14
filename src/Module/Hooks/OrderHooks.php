<?php

namespace Gett\MyparcelBE\Module\Hooks;

use Db;
use Exception;
use Gett\MyparcelBE\Carrier\PackageTypeCalculator;
use Gett\MyparcelBE\Constant;
use Gett\MyparcelBE\Logger\Logger;
use Gett\MyparcelBE\Service\CarrierName;
use Gett\MyparcelBE\Service\Order\OrderDeliveryDate;
use StdClass;

trait OrderHooks
{
    /**
     * @param $params array [
     *      'cart' => $this->context->cart,
     *      'order' => $order,
     *      'customer' => $this->context->customer,
     *      'currency' => $this->context->currency,
     *     'orderStatus' => $order_status,
     * ]
     **/
    public function hookActionValidateOrder(array $params)
    {
        $order = $params['order'];
        $cart = $params['cart'];
        $packageTypeCalculator = new PackageTypeCalculator();
        $enableDeliveryOptions = $packageTypeCalculator->allowDeliveryOptions($cart, $this->getModuleCountry());
        if ($enableDeliveryOptions) {
            return;
        }
        $packageTypeId = $packageTypeCalculator->getOrderPackageType((int) $order->id, (int) $order->id_carrier);
        if (!$packageTypeId) {
            $packageTypeId = 1;
        }
        $packageType = Constant::PACKAGE_TYPES[Constant::PACKAGE_TYPE_PACKAGE];
        if (isset(Constant::PACKAGE_TYPES[$packageTypeId])) {
            $packageType = Constant::PACKAGE_TYPES[$packageTypeId];
        }
        $optionsObj = new StdClass();
        $optionsObj->isPickup = false;
        $optionsObj->date = (new OrderDeliveryDate())->get((int) $order->id_carrier);
        $optionsObj->carrier = (new CarrierName())->get((int) $order->id_carrier);
        $optionsObj->packageType = $packageType;
        $optionsObj->deliveryType = 'standard';
        $optionsObj->shipmentOptions = new StdClass();
        $options = json_encode($optionsObj);
        try {
            Db::getInstance(_PS_USE_SQL_SLAVE_)->insert(
                'myparcelbe_delivery_settings',
                ['id_cart' => (int) $order->id_cart, 'delivery_settings' => pSQL($options)],
                false,
                true,
                Db::REPLACE
            );
        } catch (Exception $exception) {
            Logger::addLog($exception->getMessage(), true, true);
            Logger::addLog($exception->getFile(), true, true);
            Logger::addLog($exception->getLine(), true, true);
        }
    }
}
