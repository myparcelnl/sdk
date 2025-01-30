<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Factory;

use MyParcelNL\Sdk\Model\Carrier\AbstractCarrier;
use MyParcelNL\Sdk\Model\Carrier\CarrierFactory;
use MyParcelNL\Sdk\Model\Consignment\AbstractConsignment;

class ConsignmentFactory
{
    /**
     * @param  int $carrierId
     *
     * @return \MyParcelNL\Sdk\Model\Consignment\AbstractConsignment
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
     * @return \MyParcelNL\Sdk\Model\Consignment\AbstractConsignment
     * @throws \Exception
     */
    public static function createByCarrierName(string $carrierName): AbstractConsignment
    {
        $carrier = CarrierFactory::createFromName($carrierName);

        return self::getConsignmentFromCarrier($carrier);
    }

    /**
     * @param  \MyParcelNL\Sdk\Model\Carrier\AbstractCarrier $carrier
     *
     * @return \MyParcelNL\Sdk\Model\Consignment\AbstractConsignment
     */
    public static function createFromCarrier(AbstractCarrier $carrier): AbstractConsignment
    {
        return self::getConsignmentFromCarrier($carrier);
    }

    /**
     * @param  \MyParcelNL\Sdk\Model\Carrier\AbstractCarrier $carrier
     *
     * @return \MyParcelNL\Sdk\Model\Consignment\AbstractConsignment
     */
    private static function getConsignmentFromCarrier(AbstractCarrier $carrier): AbstractConsignment
    {
        $consignmentClass = $carrier->getConsignmentClass();

        return new $consignmentClass();
    }
}
