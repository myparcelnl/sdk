<?php

namespace Gett\MyParcelBE\Database;

class CreateOrderLabelTableMigration implements Migration
{
    public static function up(): bool
    {
        $sql = <<<'SQL'
        CREATE TABLE IF NOT EXISTS `{PREFIX}myparcel_order_label` (
          `id_order_label` int(11) NOT NULL AUTO_INCREMENT,
          `id_order` int(11) NOT NULL,
          `status` varchar (60) NOT NULL,
          `new_order_state` int(11) DEFAULT NULL,
          `barcode` varchar (60) DEFAULT NULL,
          `track_link` varchar(255) DEFAULT NULL,
          `payment_url` varchar(255) DEFAULT NULL,
          `id_label` bigint(20) NOT NULL,
          PRIMARY KEY (`id_order_label`)
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
                DROP TABLE IF EXISTS {PREFIX}myparcel_order_label;
SQL;

        return \Db::getInstance(_PS_USE_SQL_SLAVE_)
            ->execute(str_replace(['{PREFIX}', '{ENGINE}'], [_DB_PREFIX_, _MYSQL_ENGINE_], $sql))
        ;
    }
}
