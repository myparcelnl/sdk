<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Concerns\Model\Initializable;

use MyParcelNL\Sdk\src\Model\Shipment\PickupLocation;

/**
 * @property \MyParcelNL\Sdk\src\Model\Shipment\PickupLocation $pickupLocation
 */
trait HasPickupLocationAttribute
{
    protected function initializeHasPickupLocationAttribute(): void
    {
        $this->append('pickupLocation');
        $this->pickupLocation = new PickupLocation();
    }
}
