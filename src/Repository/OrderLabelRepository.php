<?php

namespace Gett\MyParcel\Repository;

use Doctrine\DBAL\Connection;

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
        $qb = $this->connection->createQueryBuilder();
        $qb->select(
            'o.id_order,
                    o.reference,
                    co.iso_code,
                    CONCAT(a.firstname, " ",a.lastname) as person,
                    CONCAT(a.address1, " ", a.address2) as full_street,
                    a.postcode,
                    a.city,
                    c.email'
        );
        $qb->from($this->tablePrefix . 'orders', 'o');
        $qb->innerJoin('o', $this->tablePrefix . 'address', 'a', 'o.id_address_delivery = a.id_address');
        $qb->innerJoin('a', $this->tablePrefix . 'country', 'co', 'co.id_country = a.id_country');
        $qb->innerJoin('o', $this->tablePrefix . 'customer', 'c', 'o.id_customer = c.id_customer');
        $qb->where('o.id_order IN (' . implode(',', $orderIds) . ') ');

        return $qb->execute()->fetchAll();
    }
}
