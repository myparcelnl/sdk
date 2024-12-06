<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Validator\Consignment;

use MyParcelNL\Sdk\Rule\Consignment\DeliveryDateRule;
use MyParcelNL\Sdk\Rule\Consignment\DropOffPointRule;
use MyParcelNL\Sdk\Rule\Consignment\MaximumWeightRule;
use MyParcelNL\Sdk\Rule\Consignment\ShipmentOptionsRule;
use MyParcelNL\Sdk\Validator\AbstractValidator;

class PostNLConsignmentValidator extends AbstractValidator
{
    /**
     * @return \MyParcelNL\Sdk\Rule\Rule[]
     */
    protected function getRules(): array
    {
        return [
            new DeliveryDateRule(),
            new ShipmentOptionsRule(),
            new DropOffPointRule(),
            new MaximumWeightRule(),
        ];
    }
}
