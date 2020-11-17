<?php

namespace Gett\MyparcelBE\Database;

class CreateProductConfigurationTableMigration implements Migration
{
    public static function up(): bool
    {
        $sql = <<<'SQL'
                CREATE TABLE IF NOT EXISTS `{PREFIX}myparcelbe_product_configuration` (
`id` int(11) NOT NULL AUTO_INCREMENT,
  `id_product` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `value` text,
  PRIMARY KEY (`id`)
) ENGINE={ENGINE} DEFAULT CHARSET=utf8;
SQL;

        return \Db::getInstance(_PS_USE_SQL_SLAVE_)
            ->execute(str_replace(['{PREFIX}', '{ENGINE}'], [_DB_PREFIX_, _MYSQL_ENGINE_], $sql))
        ;
    }

    public static function down(): bool
    {
        $sql =
            <<<'SQL'
                DROP TABLE IF EXISTS {PREFIX}myparcelbe_product_configuration;
SQL;

        return \Db::getInstance(_PS_USE_SQL_SLAVE_)
            ->execute(str_replace(['{PREFIX}', '{ENGINE}'], [_DB_PREFIX_, _MYSQL_ENGINE_], $sql))
        ;
    }
}
