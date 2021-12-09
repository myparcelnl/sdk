<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Validator\Consignment;

use MyParcelNL\Sdk\src\Rule\Consignment\DeliveryDateRule;
use MyParcelNL\Sdk\src\Rule\Consignment\MinimumItemValueRule;
use MyParcelNL\Sdk\src\Rule\Consignment\MinimumWeightRule;
use MyParcelNL\Sdk\src\Rule\Consignment\ShipmentOptionsRule;

class BpostConsignmentValidator extends AbstractConsignmentValidator
{
    /**
     * @return \MyParcelNL\Sdk\src\Rule\Rule[]
     */
    protected function getRules(): array
    {
        return parent::getRules() + [
                new DeliveryDateRule(),
                new MinimumItemValueRule(),
                new MinimumWeightRule(),
                new ShipmentOptionsRule(),
            ];
    }
}
