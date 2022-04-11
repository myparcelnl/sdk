<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Concerns\Initializable;

use MyParcelNL\Sdk\src\Entity\Consignment\DeliveryType;

/**
 * @property \MyParcelNL\Sdk\src\Entity\Consignment\DeliveryType $deliveryType
 */
trait HasDeliveryTypeAttribute
{
    protected function initializeHasDeliveryType(): void
    {
        $this->append('deliveryType');
        $this->deliveryType = new DeliveryType();
    }
}
