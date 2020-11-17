<?php

namespace Gett\MyparcelBE\Module\Hooks;

use Gett\MyparcelBE\Constant;

trait CarrierHooks
{
    public function hookActionCarrierUpdate(array $params): void
    {
        $oldCarrierId = (int) $params['id_carrier'];
        $newCarrier = $params['carrier']; // Carrier object

        \Db::getInstance(_PS_USE_SQL_SLAVE_)->execute('INSERT INTO `' . _DB_PREFIX_ . 'myparcelbe_carrier_configuration`
            (`id_carrier`, `name`, `value`)
            SELECT ' . (int) $newCarrier->id . ' AS `id_carrier`, `name`, `value`
            FROM `' . _DB_PREFIX_ . 'myparcelbe_carrier_configuration` 
            WHERE `id_carrier` = ' . (int) $oldCarrierId
        );
    }
}
