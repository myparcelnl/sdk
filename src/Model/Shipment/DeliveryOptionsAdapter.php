<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Shipment;

use MyParcelNL\Sdk\src\Concerns\Model\Initializable\HasCarrierAttribute;
use MyParcelNL\Sdk\src\Concerns\Model\Initializable\HasDeliveryTypeAttribute;
use MyParcelNL\Sdk\src\Concerns\Model\Initializable\HasPackageTypeAttribute;
use MyParcelNL\Sdk\src\Concerns\Model\Initializable\HasPickupLocationAttribute;
use MyParcelNL\Sdk\src\Model\BaseModel;

/**
 * @property bool $isPickup
 */
class DeliveryOptionsAdapter extends BaseModel
{
    use HasCarrierAttribute;
    use HasDeliveryTypeAttribute;
    use HasPackageTypeAttribute;
    use HasPickupLocationAttribute;

    protected function getIsPickupAttribute(): bool
    {
        return $this->pickupLocation && $this->deliveryType->isPickup;
    }
}
