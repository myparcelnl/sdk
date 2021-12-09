<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Concerns\Model\Initializable;

/**
 * @property \MyParcelNL\Sdk\src\Model\Carrier\AbstractCarrier $carrier
 */
trait HasCarrierAttribute
{
    protected function initializeHasCarrier(): void
    {
        $this->append('carrier');
    }
}
