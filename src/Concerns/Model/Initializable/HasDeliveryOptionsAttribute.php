<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Concerns\Model\Initializable;

use MyParcelNL\Sdk\src\Model\Shipment\DeliveryOptionsAdapter;

/**
 * @property \MyParcelNL\Sdk\src\Model\Shipment\DeliveryOptionsAdapter $deliveryOptions
 */
trait HasDeliveryOptionsAttribute
{
    /**
     * @param  array $data
     */
    public function setDeliveryOptions(array $data = []): void
    {
        $this->deliveryOptions = new DeliveryOptionsAdapter($data);
    }

    protected function initializeHasDeliveryOptionsAttribute(): void
    {
        $this->append('deliveryOptions');
    }
}
