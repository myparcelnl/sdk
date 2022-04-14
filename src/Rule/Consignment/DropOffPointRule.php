<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Rule\Consignment;

use MyParcelNL\Sdk\src\Rule\Rule;

class DropOffPointRule extends Rule
{
    /**
     * @param  \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment|\MyParcelNL\Sdk\src\Model\Fulfilment\AbstractOrder $validationSubject
     */
    public function validate($validationSubject): void
    {
        if (! $validationSubject->getDropOffPoint() && $validationSubject->getCarrier()->isDropOffPointRequired()) {
            $this->addError('A DropOffPoint is required for ' . get_class($validationSubject));
        }
    }
}
