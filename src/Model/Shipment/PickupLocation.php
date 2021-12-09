<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Shipment;

use MyParcelNL\Sdk\src\Model\Recipient;

/**
 * @property string locationCode
 * @property string locationName
 * @property string retailNetworkId
 */
class PickupLocation extends Recipient
{
    public function __construct(array $data = [])
    {
        $this->append([
            'locationCode',
            'locationName',
            'retailNetworkId',
        ]);

        parent::__construct($data);
    }
}
