<?php

namespace MyParcelNL\Sdk\Test\Model\Shipment;

use MyParcelNL\Sdk\src\Entity\Consignment\PackageType;
use MyParcelNL\Sdk\src\Factory\DeliveryOptionsAdapterFactory;
use MyParcelNL\Sdk\src\Model\Carrier\CarrierRedJePakketje;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\src\Model\Recipient;
use MyParcelNL\Sdk\src\Model\Shipment\AbstractShipment;
use MyParcelNL\Sdk\src\Model\Shipment\DeliveryOptionsAdapter;
use PHPUnit\Framework\TestCase;

class AbstractShipmentTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function test()
    {
//        $delo = new DeliveryOptionsAdapter();
//
//        var_dump(json_encode($delo->toArray(), JSON_PRETTY_PRINT));
//        exit();

        $shipment = new AbstractShipment([
            'apiKey'          => 'abcdef',
//            'recipient'        => new Recipient([
//                'person' => 'Edie',
//            ]),
//            'delivery_options' => DeliveryOptionsAdapterFactory::create([
//                'deliveryType' => 'morning',
//                'carrier'      => new CarrierRedJePakketje(),
//                'packageType'  => new PackageType(AbstractConsignment::PACKAGE_TYPE_PACKAGE),
//            ]),
        ]);

        var_dump(__FILE__ . "@" . __FUNCTION__ . ":" . __LINE__, $shipment->deliveryOptions->deliveryType->name);

        $arr = $shipment->toArray();

        var_dump(json_encode($arr, JSON_PRETTY_PRINT));
    }
}
