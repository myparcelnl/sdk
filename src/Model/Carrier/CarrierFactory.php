<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Carrier;

use Exception;
use MyParcelNL\Sdk\src\Model\Consignment\BpostConsignment;
use MyParcelNL\Sdk\src\Model\Consignment\DPDConsignment;
use MyParcelNL\Sdk\src\Model\Consignment\PostNLConsignment;

class CarrierFactory
{
    /**
     * @param  string|int $carrierNameOrId
     *
     * @return \MyParcelNL\Sdk\src\Model\Carrier\AbstractCarrier
     * @throws \Exception
     */
    public static function create($carrierNameOrId): AbstractCarrier
    {
        if (is_numeric($carrierNameOrId)) {
            return self::createFromId((int) $carrierNameOrId);
        }

        return self::createFromName($carrierNameOrId);
    }

    /**
     * @param  int $carrierId
     *
     * @return \MyParcelNL\Sdk\src\Model\Carrier\AbstractCarrier
     * @throws \Exception
     */
    public static function createFromId(int $carrierId): AbstractCarrier
    {
        switch ($carrierId) {
            case PostNLConsignment::CARRIER_ID:
                return new PostNLCarrier();
            case DPDConsignment::CARRIER_ID:
                return new CarrierDPD();
            case BpostConsignment::CARRIER_ID:
                return new CarrierBpost();
            default:
                throw new Exception('No carrier found for id ' . $carrierId);
        }
    }

    /**
     * @param  string $carrierName
     *
     * @return \MyParcelNL\Sdk\src\Model\Carrier\AbstractCarrier
     * @throws \Exception
     */
    public static function createFromName(string $carrierName): AbstractCarrier
    {
        switch ($carrierName) {
            case PostNLConsignment::CARRIER_NAME:
                return new PostNLCarrier();
            case DPDConsignment::CARRIER_NAME:
                return new CarrierDPD();
            case BpostConsignment::CARRIER_NAME:
                return new CarrierBpost();
            default:
                throw new Exception('No carrier found for name ' . $carrierName);
        }
    }
}
