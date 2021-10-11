<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Factory;

use BadMethodCallException;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\src\Model\Consignment\BpostConsignment;
use MyParcelNL\Sdk\src\Model\Consignment\DPDConsignment;
use MyParcelNL\Sdk\src\Model\Consignment\PostNLConsignment;
use MyParcelNL\Sdk\src\Model\Consignment\RedJePakketjeConsignment;

class ConsignmentFactory
{
    /**
     * @param  int $carrierId
     *
     * @return \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment
     */
    public static function createByCarrierId(int $carrierId): AbstractConsignment
    {
        switch ($carrierId) {
            case PostNLConsignment::CARRIER_ID:
                return new PostNLConsignment();
            case BpostConsignment::CARRIER_ID:
                return new BpostConsignment();
            case DPDConsignment::CARRIER_ID:
                return new DPDConsignment();
            case RedJePakketjeConsignment::CARRIER_ID:
                return new RedJePakketjeConsignment();
        }

        throw new BadMethodCallException("Carrier id $carrierId not found");
    }

    /**
     * @param  string $carrierName
     *
     * @return \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment
     */
    public static function createByCarrierName(string $carrierName): AbstractConsignment
    {
        switch ($carrierName) {
            case PostNLConsignment::CARRIER_NAME:
                return new PostNLConsignment();
            case BpostConsignment::CARRIER_NAME:
                return new BpostConsignment();
            case DPDConsignment::CARRIER_NAME:
                return new DPDConsignment();
            case RedJePakketjeConsignment::CARRIER_NAME:
                return new RedJePakketjeConsignment();
        }

        throw new BadMethodCallException("Carrier name $carrierName not found");
    }
}
