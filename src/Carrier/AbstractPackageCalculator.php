<?php

namespace Gett\MyparcelBE\Carrier;

use Db;
use DbQuery;

abstract class AbstractPackageCalculator
{
    public function getOrderProductsConfiguration(int $idOrder)
    {
        $sql = new DbQuery();
        $sql->select('mpc.*');
        $sql->from('order_detail', 'od');
        $sql->innerJoin('myparcelbe_product_configuration', 'mpc', 'od.product_id = mpc.id_product');
        $sql->where('id_order = ' . $idOrder);

        return Db::getInstance()->executeS($sql);
    }
}
