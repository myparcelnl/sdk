<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Validator\Consignment;

use MyParcelNL\Sdk\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\Rule\Consignment\RestrictCountriesRule;
use MyParcelNL\Sdk\Rule\Consignment\DeliveryDateRule;
use MyParcelNL\Sdk\Rule\Consignment\DropOffPointRule;
use MyParcelNL\Sdk\Rule\Consignment\MaximumWeightRule;
use MyParcelNL\Sdk\Rule\Consignment\ShipmentOptionsRule;
use MyParcelNL\Sdk\Validator\AbstractValidator;

class DHLEuroplusConsignmentValidator extends AbstractValidator
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
            new RestrictCountriesRule(array_merge(AbstractConsignment::EURO_COUNTRIES, ['UK', 'GB']))
        ];
    }
}
