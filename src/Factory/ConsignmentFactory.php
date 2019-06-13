<?php
/**
 * Created by PhpStorm.
 * User: richardperdaan
 * Date: 2019-06-13
 * Time: 10:02
 */

namespace MyparcelNL\Sdk\src\Factory;


use MyParcelNL\Sdk\src\Model\AbstractConsignment;
use MyparcelNL\Sdk\src\Model\PostNLConsignment;

class ConsignmentFactory
{

    public static function createByCarrierId(string $carrierId): AbstractConsignment
    {
        switch ($carrierId) {
            case PostNLConsignment::CARRIER_ID:
                return new PostNLConsignment();
        }

        throw new \BadMethodCallException("Carrier id $carrierId not found");
    }
}