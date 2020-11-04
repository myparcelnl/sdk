<?php

namespace Gett\MyparcelBE\Module\Hooks;

use Gett\MyparcelBE\Constant;

trait OrderLabelHooks
{
    public function hookActionObjectGettMyParcelBEOrderLabelAddAfter($params)
    {
        if (\Configuration::get(Constant::LABEL_CREATED_ORDER_STATUS_CONFIGURATION_NAME)) {
            $history = new \OrderHistory();
            $history->id_order = (int) $params['object']->id_order;
            $history->changeIdOrderState(\Configuration::get(
                Constant::LABEL_CREATED_ORDER_STATUS_CONFIGURATION_NAME),
                (int) $params['object']->id_order,
                true
            );
            $history->addWithemail();
        }

        if (\Configuration::get(Constant::SENT_ORDER_STATE_FOR_DIGITAL_STAMPS_CONFIGURATION_NAME) && $this->isNL()) {
            $history = new \OrderHistory();
            $history->id_order = (int) $params['object']->id_order;
            $history->changeIdOrderState(
                \Configuration::get('PS_OS_SHIPPING'),
                (int) $params['object']->id_order,
                true
            );
            $history->addWithemail();
        }
    }

    public function hookActionObjectGettMyParcelBEOrderLabelUpdateAfter($params)
    {
        $order = new \Order($params['object']->id_order);
        $ignore = \Configuration::get(Constant::IGNORE_ORDER_STATUS_CONFIGURATION_NAME);
        if ($ignore) {
            $ignore = explode(',', $ignore);
        }
        if (is_array($ignore) && in_array($order->getCurrentState(), $ignore)) {
            if (Constant::LABEL_SCANNED_ORDER_STATUS_CONFIGURATION_NAME
                && $params['object']->new_order_state == Constant::SCANNED_STATUS) {
                $history = new \OrderHistory();
                $history->id_order = (int) $params['object']->id_order;
                $history->changeIdOrderState(
                    \Configuration::get(Constant::LABEL_SCANNED_ORDER_STATUS_CONFIGURATION_NAME),
                    (int) $params['object']->id_order,
                    true
                );
                $history->addWithemail();
            }

            if ($params['object']->new_order_state >= Constant::DELIVERED_STATUS
                && $params['object']->new_order_state <= Constant::RETURN_PICKED_STATUS
                && \Configuration::get(Constant::DELIVERED_ORDER_STATUS_CONFIGURATION_NAME)) {
                $history = new \OrderHistory();
                $history->id_order = (int) $params['object']->id_order;
                $history->changeIdOrderState(
                    \Configuration::get(Constant::DELIVERED_ORDER_STATUS_CONFIGURATION_NAME),
                    (int) $params['object']->id_order,
                    true
                );
                $history->addWithemail();
            }
        }
    }
}
