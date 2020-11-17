<?php

namespace Gett\MyparcelBE\Repository;

use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Class OrderLabelRepository.
 */
class OrderLabelRepository
{
    /**
     * @var Connection
     */
    private $connection;
    /**
     * @var string
     */
    private $tablePrefix;

    public function __construct(Connection $connection, string $tablePrefix)
    {
        $this->connection = $connection;
        $this->tablePrefix = $tablePrefix;
    }

    public function getOrdersForLabelCreate(array $orderIds)
    {
        $qb = $this->getOrderQueryBuilder();

        $qb->where('o.id_order IN (' . implode(',', $orderIds) . ') ');

        return $qb->execute()->fetchAll();
    }

    public function getIdLabelByBarcode(string $barcode)
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select(
            'ol.id_label'
        );
        $qb->from($this->tablePrefix . 'myparcelbe_order_label', 'ol');
        $qb->where('barcode = "' . $barcode . '" ');

        return $qb->execute()->fetch()['id_label'];
    }

    public function getOrdersLabels(array $orders_id)
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select(
            'ol.id_label'
        );
        $qb->from($this->tablePrefix . 'myparcelbe_order_label', 'ol');
        $qb->where('id_order IN ("' . implode(',', $orders_id) . '") ');

        return $qb->execute()->fetchAll(FetchMode::COLUMN);
    }

    private function getOrderQueryBuilder(): QueryBuilder
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select(
            'o.id_order,
                    o.reference,
                    co.iso_code,
                    CONCAT(a.firstname, " ",a.lastname) as person,
                    CONCAT(a.address1, " ", a.address2) as full_street,
                    a.postcode,
                    a.city,
                    c.email,
                    a.phone,
                    ds.delivery_settings,
                    o.id_carrier
                    '
        );
        $qb->from($this->tablePrefix . 'orders', 'o');
        $qb->innerJoin('o', $this->tablePrefix . 'address', 'a', 'o.id_address_delivery = a.id_address');
        $qb->innerJoin('a', $this->tablePrefix . 'country', 'co', 'co.id_country = a.id_country');
        $qb->innerJoin('o', $this->tablePrefix . 'customer', 'c', 'o.id_customer = c.id_customer');
        $qb->innerJoin('o', $this->tablePrefix . 'myparcelbe_delivery_settings', 'ds', 'o.id_cart = ds.id_cart');

        return $qb;
    }
}
