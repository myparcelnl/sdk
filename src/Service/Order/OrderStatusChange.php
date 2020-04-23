<?php

namespace Gett\MyParcel\Service\Order;

use Gett\MyParcel\Constant;
use PrestaShop\PrestaShop\Core\ConfigurationInterface;

class OrderStatusChange
{
    public function changeOrderStatus(int $order_id, int $order_state)
    {
        $order = new \Order($order_id);
        $currentOrderState = $order->getCurrentOrderState();

        if ($currentOrderState->id === $order_state) {
            return true;
        }

        $history = new \OrderHistory();
        $history->id_order = $order->id;
        $history->id_employee = (int) Context::getContext()->employee->id;

        $history->changeIdOrderState($order_state, $order);
        $history->add();

        $history->sendEmail($order);

        return true;
    }
}
