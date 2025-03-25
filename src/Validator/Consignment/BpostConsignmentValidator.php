<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Validator\Consignment;

use MyParcelNL\Sdk\Rule\Consignment\DeliveryDateRule;
use MyParcelNL\Sdk\Rule\Consignment\MinimumItemValueRule;
use MyParcelNL\Sdk\Rule\Consignment\MinimumWeightRule;
use MyParcelNL\Sdk\Rule\Consignment\ShipmentOptionsRule;
use MyParcelNL\Sdk\Validator\AbstractValidator;

class BpostConsignmentValidator extends AbstractValidator
{
    /**
     * @return \MyParcelNL\Sdk\Rule\Rule[]
     */
    protected function getRules(): array
    {
        return [
            new DeliveryDateRule(),
            new MinimumItemValueRule(),
            new MinimumWeightRule(),
            new ShipmentOptionsRule(),
        ];
    }
}
