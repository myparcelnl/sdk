<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Shipment;

use MyParcelNL\Sdk\src\Model\Address;

/**
 * @property string locationCode
 * @property string locationName
 * @property string retailNetworkId
 */
class PickupLocation extends Address
{
//    public function __construct(array $data = [])
//    {
//        $this->append([
//            'locationCode',
//            'locationName',
//            'retailNetworkId',
//        ]);
//
//        parent::__construct($data);
//    }

    public function getAttributes(): array
    {
        return parent::getAttributes() + [
                'locationCode'    => null,
                'locationName'    => null,
                'retailNetworkId' => null,
            ];
    }
}
