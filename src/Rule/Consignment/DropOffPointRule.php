<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Rule\Consignment;

use MyParcelNL\Sdk\Rule\Rule;

class DropOffPointRule extends Rule
{
    /**
     * @param  \MyParcelNL\Sdk\Model\Consignment\AbstractConsignment|\MyParcelNL\Sdk\Model\Fulfilment\AbstractOrder $validationSubject
     */
    public function validate($validationSubject): void
    {
        if (! $validationSubject->getDropOffPoint() && $validationSubject->getCarrier()->isDropOffPointRequired()) {
            $this->addError('A DropOffPoint is required for ' . get_class($validationSubject));
        }
    }
}
