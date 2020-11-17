<?php

namespace Gett\MyparcelBE\Database;

class CreateCarrierConfigurationTableMigration implements Migration
{
    public static function up(): bool
    {
        $sql = <<<'SQL'
                CREATE TABLE IF NOT EXISTS `{PREFIX}myparcelbe_carrier_configuration` (
  `id_configuration` int(11) NOT NULL AUTO_INCREMENT,
  `id_carrier` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `value` varchar(100) NOT NULL,
  PRIMARY KEY (`id_configuration`)
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
                DROP TABLE IF EXISTS {PREFIX}myparcelbe_carrier_configuration;
SQL;

        return \Db::getInstance(_PS_USE_SQL_SLAVE_)
            ->execute(str_replace(['{PREFIX}', '{ENGINE}'], [_DB_PREFIX_, _MYSQL_ENGINE_], $sql))
        ;
    }
}
