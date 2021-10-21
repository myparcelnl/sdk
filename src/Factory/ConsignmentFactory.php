<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Factory;

use MyParcelNL\Sdk\src\Model\Carrier\AbstractCarrier;
use MyParcelNL\Sdk\src\Model\Carrier\CarrierBpost;
use MyParcelNL\Sdk\src\Model\Carrier\CarrierDPD;
use MyParcelNL\Sdk\src\Model\Carrier\CarrierFactory;
use MyParcelNL\Sdk\src\Model\Carrier\CarrierPostNL;
use MyParcelNL\Sdk\src\Model\Carrier\CarrierRedJePakketje;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\src\Model\Consignment\BpostConsignment;
use MyParcelNL\Sdk\src\Model\Consignment\DPDConsignment;
use MyParcelNL\Sdk\src\Model\Consignment\PostNLConsignment;
use MyParcelNL\Sdk\src\Model\Consignment\RedJePakketjeConsignment;

class ConsignmentFactory
{
    private const CARRIER_CONSIGNMENT_MAP = [
        CarrierBpost::class         => BpostConsignment::class,
        CarrierDPD::class           => DPDConsignment::class,
        CarrierPostNL::class        => PostNLConsignment::class,
        CarrierRedJePakketje::class => RedJePakketjeConsignment::class,
    ];

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
        $carrierClass     = get_class($carrier);
        $consignmentClass = self::CARRIER_CONSIGNMENT_MAP[$carrierClass];

        return new $consignmentClass();
    }
}
