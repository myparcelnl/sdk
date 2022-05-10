<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Validator\Consignment;

use MyParcelNL\Sdk\src\Rule\Consignment\DeliveryDateRule;
use MyParcelNL\Sdk\src\Rule\Consignment\DropOffPointRule;
use MyParcelNL\Sdk\src\Rule\Consignment\LocalCountryOnlyRule;
use MyParcelNL\Sdk\src\Rule\Consignment\MaximumWeightRule;
use MyParcelNL\Sdk\src\Rule\Consignment\ShipmentOptionsRule;
use MyParcelNL\Sdk\src\Validator\AbstractValidator;

class InstaboxConsignmentValidator extends AbstractValidator
{
    /**
     * @inheritDoc
     */
    protected function getRules(): array
    {
        return [
            new DeliveryDateRule(),
            new DropOffPointRule(),
            new LocalCountryOnlyRule(),
            new ShipmentOptionsRule(),
            new MaximumWeightRule(),
        ];
    }
}
