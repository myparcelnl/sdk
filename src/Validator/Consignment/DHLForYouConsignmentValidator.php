<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Validator\Consignment;

use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\src\Rule\Consignment\RestrictCountriesRule;
use MyParcelNL\Sdk\src\Rule\Consignment\DeliveryDateRule;
use MyParcelNL\Sdk\src\Rule\Consignment\DropOffPointRule;
use MyParcelNL\Sdk\src\Rule\Consignment\MaximumWeightRule;
use MyParcelNL\Sdk\src\Rule\Consignment\ShipmentOptionsRule;
use MyParcelNL\Sdk\src\Validator\AbstractValidator;

class DHLForYouConsignmentValidator extends AbstractValidator
{
    /**
     * @return \MyParcelNL\Sdk\src\Rule\Rule[]
     */
    protected function getRules(): array
    {
        return [
            new DeliveryDateRule(),
            new ShipmentOptionsRule([
                    // Tijdelijk uitgezet i.v.m. DHL Pilot (MY-35887) zodat same_day_delivery uitgezet kan worden vanuit de plugin.
                    // Dient weer geactiveerd te worden zodra de carriers opgesplitst zijn.
                    //AbstractConsignment::SHIPMENT_OPTION_SAME_DAY_DELIVERY,
                ]
            ),
            new DropOffPointRule(),
            new MaximumWeightRule(),
            new RestrictCountriesRule([AbstractConsignment::CC_NL, AbstractConsignment::CC_BE]),
        ];
    }
}
