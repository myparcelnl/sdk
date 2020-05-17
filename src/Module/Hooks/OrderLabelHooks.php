<?php

namespace Gett\MyParcel\Module\Hooks;

use Gett\MyParcel\Constant;

trait OrderLabelHooks
{
    public function hookActionObjectGettMyParcelOrderLabelAddAfter($params)
    {
        if (\Configuration::get(\Gett\MyParcel\Constant::MY_PARCEL_LABEL_CREATED_ORDER_STATUS_CONFIGURATION_NAME)) {
            $history = new \OrderHistory();
            $history->id_order = (int) $params['object']->id_order;
            $history->changeIdOrderState(\Configuration::get(\Gett\MyParcel\Constant::MY_PARCEL_LABEL_CREATED_ORDER_STATUS_CONFIGURATION_NAME), (int) $params['object']->id_order);
            $history->addWithemail();
            $order = new \Order($params['object']->id_order);
            $order->current_state = \Configuration::get(\Gett\MyParcel\Constant::MY_PARCEL_LABEL_CREATED_ORDER_STATUS_CONFIGURATION_NAME);
            $order->save();
        }

        if (\Configuration::get('MY_PARCEL_SENT_ORDER_STATE_FOR_DIGITAL_STAMPS')) {
            $history = new \OrderHistory();
            $history->id_order = (int) $params['object']->id_order;
            $history->changeIdOrderState(4, (int) $params['object']->id_order);
            $history->addWithemail();
            $order = new \Order($params['object']->id_order);
            $order->current_state = 4;
            $order->save();
        }
    }

    public function hookActionObjectGettMyParcelOrderLabelUpdateAfter($params)
    {
        $order = new \Order($params['object']->id_order);
        $ignore = \Configuration::get(Constant::MY_PARCEL_IGNORE_ORDER_STATUS_CONFIGURATION_NAME);
        if ($ignore) {
            $ignore = explode(',', $ignore);
        }
        if (in_array($order->getCurrentState(), $ignore)) {
            if (\Gett\MyParcel\Constant::MY_PARCEL_LABEL_SCANNED_ORDER_STATUS_CONFIGURATION_NAME && $params['object']->new_order_state == '3') {
                $history = new \OrderHistory();
                $history->id_order = (int) $params['object']->id_order;
                $history->changeIdOrderState(\Configuration::get(\Gett\MyParcel\Constant::MY_PARCEL_LABEL_SCANNED_ORDER_STATUS_CONFIGURATION_NAME), (int) $params['object']->id_order);
                $history->addWithemail();

                $order = new \Order($params['object']->id_order);
                $order->current_state = \Configuration::get(\Gett\MyParcel\Constant::MY_PARCEL_LABEL_SCANNED_ORDER_STATUS_CONFIGURATION_NAME);
                $order->save();
            }

            if ($params['object']->new_order_state >= 7 && $params['object']->new_order_state <= 11 && \Configuration::get(\Gett\MyParcel\Constant::MY_PARCEL_DELIVERED_ORDER_STATUS_CONFIGURATION_NAME)) {
                $history = new \OrderHistory();
                $history->id_order = (int) $params['object']->id_order;
                $history->changeIdOrderState(\Configuration::get(\Gett\MyParcel\Constant::MY_PARCEL_DELIVERED_ORDER_STATUS_CONFIGURATION_NAME), (int) $params['object']->id_order);
                $history->addWithemail();

                $order = new \Order($params['object']->id_order);
                $order->current_state = \Configuration::get(\Gett\MyParcel\Constant::MY_PARCEL_DELIVERED_ORDER_STATUS_CONFIGURATION_NAME);
                $order->save();
            }
        }
    }
}
