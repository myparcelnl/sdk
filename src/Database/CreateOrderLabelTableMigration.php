<?php

namespace Gett\MyparcelBE\Database;

class CreateOrderLabelTableMigration implements Migration
{
    public static function up(): bool
    {
        $sql = <<<'SQL'
        CREATE TABLE IF NOT EXISTS `{PREFIX}myparcelbe_order_label` (
          `id_order_label` int(11) NOT NULL AUTO_INCREMENT,
          `id_order` int(11) NOT NULL,
          `status` varchar (60) NOT NULL,
          `new_order_state` int(11) DEFAULT NULL,
          `barcode` varchar (60) DEFAULT NULL,
          `track_link` varchar(255) DEFAULT NULL,
          `payment_url` varchar(255) DEFAULT NULL,
          `id_label` bigint(20) NOT NULL,
          `date_add` datetime NOT NULL,
          `date_upd` datetime NOT NULL,
          PRIMARY KEY (`id_order_label`),
          KEY `id_order` (`id_order`),
          KEY `new_order_state` (`new_order_state`),
          KEY `barcode` (`barcode`),
          KEY `id_label` (`id_label`),
          KEY `date_add` (`date_add`),
          KEY `date_upd` (`date_upd`)
        ) ENGINE={ENGINE} DEFAULT CHARSET=utf8
SQL;

        return \Db::getInstance(_PS_USE_SQL_SLAVE_)
            ->execute(str_replace(['{PREFIX}', '{ENGINE}'], [_DB_PREFIX_, _MYSQL_ENGINE_], $sql))
        ;
    }

    public static function down(): bool
    {
        $sql =
            <<<'SQL'
                DROP TABLE IF EXISTS {PREFIX}myparcelbe_order_label;
SQL;

        return \Db::getInstance(_PS_USE_SQL_SLAVE_)
            ->execute(str_replace(['{PREFIX}', '{ENGINE}'], [_DB_PREFIX_, _MYSQL_ENGINE_], $sql))
        ;
    }
}
