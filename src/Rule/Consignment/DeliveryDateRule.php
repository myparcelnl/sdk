<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Rule\Consignment;

use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\src\Rule\Rule;

class DeliveryDateRule extends Rule
{
    /**
     * @param  \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment $validationSubject
     */
    public function validate($validationSubject): void
    {
        $isMorningOrEvening = in_array(
            $validationSubject->getDeliveryType(),
            [AbstractConsignment::DELIVERY_TYPE_MORNING, AbstractConsignment::DELIVERY_TYPE_EVENING],
            true
        );

        if ($isMorningOrEvening
            && $validationSubject->canHaveExtraOption(AbstractConsignment::EXTRA_OPTION_DELIVERY_DATE)
            && ! $validationSubject->getDeliveryDate()) {
            $this->addError('If delivery_type is morning or evening, delivery_date is required.');
        }
    }
}
