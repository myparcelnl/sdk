<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Shipment;

use MyParcelNL\Sdk\src\Model\Concerns\Initializable\HasCarrierAttribute;
use MyParcelNL\Sdk\src\Model\Concerns\Initializable\HasDeliveryTypeAttribute;
use MyParcelNL\Sdk\src\Model\Concerns\Initializable\HasPackageTypeAttribute;
use MyParcelNL\Sdk\src\Model\Concerns\Initializable\HasPickupLocationAttribute;
use MyParcelNL\Sdk\src\Model\Model;

/**
 * @property bool $isPickup
 */
class DeliveryOptionsAdapter extends Model
{
    use HasCarrierAttribute;
    use HasDeliveryTypeAttribute;
    use HasPackageTypeAttribute;
    use HasPickupLocationAttribute;

    /**
     * @return bool
     */
    protected function getIsPickupAttribute(): bool
    {
        return $this->pickupLocation && $this->deliveryType->isPickup;
    }
}
