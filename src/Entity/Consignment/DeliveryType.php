<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Entity\Consignment;

use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;

/**
 * @property bool isPickup
 */
class DeliveryType extends AbstractEntity
{
    /**
     * @param  mixed $input
     */
    public function __construct($input = null)
    {
        parent::__construct($input ?? AbstractConsignment::DELIVERY_TYPE_STANDARD);
    }

    protected function getIsPickupAttribute(): bool
    {
        return in_array($this->name, [
            AbstractConsignment::DELIVERY_TYPE_PICKUP_EXPRESS_NAME,
            AbstractConsignment::DELIVERY_TYPE_PICKUP_NAME,
        ], true);
    }

    protected function getNamesIdsMap(): array
    {
        return AbstractConsignment::DELIVERY_TYPES_NAMES_IDS_MAP;
    }
}
