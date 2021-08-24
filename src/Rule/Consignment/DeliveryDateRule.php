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
        $isDefaultDeliveryType = AbstractConsignment::DEFAULT_DELIVERY_TYPE !== $validationSubject->getDeliveryType();

        if ($isDefaultDeliveryType && ! $validationSubject->getDeliveryDate()) {
            $this->addError('If delivery_type is not 2, delivery_date is required.');
        }
    }
}
