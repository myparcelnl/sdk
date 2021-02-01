<?php

namespace Gett\MyparcelBE\Service\Order;

use Configuration;
use Gett\MyparcelBE\Module\Tools\Tools;
use Order;

class OrderTotalWeight
{
    public function provide(int $orderId): int
    {
        $orderObject = new Order($orderId);
        $weight = $orderObject->getTotalWeight();
        if ($weight > 0) {
            $weightType = strtolower(Configuration::get('PS_WEIGHT_UNIT'));
            switch ($weightType) {
                case 'kg':
                    $weight = Tools::ps_round($weight / 1000);
                    break;
                case 't':
                    $weight = Tools::ps_round($weight / 1000000);
                    break;
                default:
                    $weight = Tools::ps_round($weight);
                    break;
            }
        }

        return $weight;
    }
}
