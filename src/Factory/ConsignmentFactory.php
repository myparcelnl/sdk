<?php declare(strict_types=1);
/*@todo command*/
namespace MyParcelNL\Sdk\src\Factory;

use MyParcelNL\Sdk\src\Model\AbstractConsignment;
use MyParcelNL\Sdk\src\Model\BpostConsignment;
use MyParcelNL\Sdk\src\Model\DPDConsignment;
use MyParcelNL\Sdk\src\Model\PostNLConsignment;

class ConsignmentFactory
{
    public static function createByCarrierId(string $carrierId): AbstractConsignment
    {
        switch ($carrierId) {
            case PostNLConsignment::CARRIER_ID:
                return new PostNLConsignment();
            case BpostConsignment::CARRIER_ID:
                return new BpostConsignment();
            case DPDConsignment::CARRIER_ID:
                return new DPDConsignment();
        }

        throw new \BadMethodCallException("Carrier id $carrierId not found");
    }
}