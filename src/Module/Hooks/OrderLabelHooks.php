<?php

namespace Gett\MyParcel\Module\Hooks;

use Gett\MyParcel\Constant;

trait OrderLabelHooks
{
    public function hookActionObjectGettMyParcelOrderLabelAddAfter($params)
    {
        if (\Configuration::get(\Gett\MyParcel\Constant::LABEL_CREATED_ORDER_STATUS_CONFIGURATION_NAME)) {
            $history = new \OrderHistory();
            $history->id_order = (int) $params['object']->id_order;
            $history->changeIdOrderState(\Configuration::get(\Gett\MyParcel\Constant::LABEL_CREATED_ORDER_STATUS_CONFIGURATION_NAME), (int) $params['object']->id_order, true);
            $history->addWithemail();
        }

        if (\Configuration::get('MY_PARCEL_SENT_ORDER_STATE_FOR_DIGITAL_STAMPS')) {
            $history = new \OrderHistory();
            $history->id_order = (int) $params['object']->id_order;
            $history->changeIdOrderState(\Configuration::get('PS_OS_SHIPPING'), (int) $params['object']->id_order, true);
            $history->addWithemail();
        }
    }

    public function hookActionObjectGettMyParcelOrderLabelUpdateAfter($params)
    {
        $order = new \Order($params['object']->id_order);
        $ignore = \Configuration::get(Constant::IGNORE_ORDER_STATUS_CONFIGURATION_NAME);
        if ($ignore) {
            $ignore = explode(',', $ignore);
        }
        if (is_array($ignore) && in_array($order->getCurrentState(), $ignore)) {
            if (\Gett\MyParcel\Constant::LABEL_SCANNED_ORDER_STATUS_CONFIGURATION_NAME && $params['object']->new_order_state == Constant::SCANNED_STATUS) {
                $history = new \OrderHistory();
                $history->id_order = (int) $params['object']->id_order;
                $history->changeIdOrderState(\Configuration::get(\Gett\MyParcel\Constant::LABEL_SCANNED_ORDER_STATUS_CONFIGURATION_NAME), (int) $params['object']->id_order, true);
                $history->addWithemail();
            }

            if ($params['object']->new_order_state >= Constant::DELIVERED_STATUS && $params['object']->new_order_state <= Constant::RETURN_PICKED_STATUS && \Configuration::get(\Gett\MyParcel\Constant::DELIVERED_ORDER_STATUS_CONFIGURATION_NAME)) {
                $history = new \OrderHistory();
                $history->id_order = (int) $params['object']->id_order;
                $history->changeIdOrderState(\Configuration::get(\Gett\MyParcel\Constant::DELIVERED_ORDER_STATUS_CONFIGURATION_NAME), (int) $params['object']->id_order, true);
                $history->addWithemail();
            }
        }
    }
}
