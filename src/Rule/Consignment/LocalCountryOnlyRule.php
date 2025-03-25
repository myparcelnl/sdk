<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Rule\Consignment;

use MyParcelNL\Sdk\Rule\Rule;

class LocalCountryOnlyRule extends Rule
{
    /**
     * @param  \MyParcelNL\Sdk\Model\Consignment\AbstractConsignment $validationSubject
     */
    public function validate($validationSubject): void
    {
        if ($validationSubject->getCountry() !== $validationSubject->getLocalCountryCode()) {
            get_class($validationSubject) . ' can only be used in ' . $validationSubject->getLocalCountryCode();
        }
    }
}
