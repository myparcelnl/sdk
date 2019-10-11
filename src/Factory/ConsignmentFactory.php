<?php declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Factory;

use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\src\Model\Consignment\PostNLConsignment;
use MyParcelNL\Sdk\src\Model\Consignment\BpostConsignment;
use MyParcelNL\Sdk\src\Model\Consignment\DPDConsignment;

class ConsignmentFactory
{
    public static function createByCarrierId(int $carrierId): AbstractConsignment
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

    public static function createByCarrierName(string $carrierName): AbstractConsignment
    {
        switch ($carrierName) {
            case PostNLConsignment::CARRIER_NAME:
                return new PostNLConsignment();
            case BpostConsignment::CARRIER_NAME:
                return new BpostConsignment();
            case DPDConsignment::CARRIER_NAME:
                return new DPDConsignment();
        }

        throw new \BadMethodCallException("Carrier name $carrierName not found");
    }
}
