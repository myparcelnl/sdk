<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Rule\Consignment;

use MyParcelNL\Sdk\Rule\Rule;

class MinimumWeightRule extends Rule
{
    /**
     * @param  \MyParcelNL\Sdk\Model\Consignment\AbstractConsignment $validationSubject
     */
    public function validate($validationSubject): void
    {
        if ($validationSubject->getTotalWeight() < 1) {
            $this->addError('Weight must be at least 1 gram');
        }
    }
}
