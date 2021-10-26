<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Factory;

use MyParcelNL\Sdk\src\Model\Carrier\AbstractCarrier;
use MyParcelNL\Sdk\src\Model\Carrier\CarrierFactory;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;

class ConsignmentFactory
{
    /**
     * @param  int $carrierId
     *
     * @return \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment
     * @throws \Exception
     */
    public static function createByCarrierId(int $carrierId): AbstractConsignment
    {
        $carrier = CarrierFactory::createFromId($carrierId);

        return self::getConsignmentFromCarrier($carrier);
    }

    /**
     * @param  string $carrierName
     *
     * @return \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment
     * @throws \Exception
     */
    public static function createByCarrierName(string $carrierName): AbstractConsignment
    {
        $carrier = CarrierFactory::createFromName($carrierName);

        return self::getConsignmentFromCarrier($carrier);
    }

    /**
     * @param  \MyParcelNL\Sdk\src\Model\Carrier\AbstractCarrier $carrier
     *
     * @return \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment
     */
    public static function createFromCarrier(AbstractCarrier $carrier): AbstractConsignment
    {
        return self::getConsignmentFromCarrier($carrier);
    }

    /**
     * @param  \MyParcelNL\Sdk\src\Model\Carrier\AbstractCarrier $carrier
     *
     * @return \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment
     */
    private static function getConsignmentFromCarrier(AbstractCarrier $carrier): AbstractConsignment
    {
        $consignmentClass = $carrier->getConsignmentClass();

        return new $consignmentClass();
    }
}
