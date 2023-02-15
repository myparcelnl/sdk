<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Validator\Consignment;

use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\src\Rule\Consignment\AllowSpecificCountriesRule;
use MyParcelNL\Sdk\src\Rule\Consignment\DeliveryDateRule;
use MyParcelNL\Sdk\src\Rule\Consignment\DropOffPointRule;
use MyParcelNL\Sdk\src\Rule\Consignment\MaximumWeightRule;
use MyParcelNL\Sdk\src\Rule\Consignment\ShipmentOptionsRule;
use MyParcelNL\Sdk\src\Validator\AbstractValidator;

class DHLEuroPlusConsignmentValidator extends AbstractValidator
{
    /**
     * @return \MyParcelNL\Sdk\src\Rule\Rule[]
     */
    protected function getRules(): array
    {
        return [
            new DeliveryDateRule(),
            new ShipmentOptionsRule([
                AbstractConsignment::SHIPMENT_OPTION_SIGNATURE,
            ], [
                AbstractConsignment::CC_NL => [
                    AbstractConsignment::EXTRA_OPTION_DELIVERY_SATURDAY,
                ],
            ]),
            new DropOffPointRule(),
            new MaximumWeightRule(),
            new AllowSpecificCountriesRule(AbstractConsignment::EURO_COUNTRIES),
        ];
    }
}
