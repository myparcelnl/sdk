<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Rule\Consignment;

use MyParcelNL\Sdk\src\Rule\Rule;

class LocalCountryOnlyRule extends Rule
{
    /**
     * @param  \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment $validationSubject
     */
    public function validate($validationSubject): void
    {
        if ($validationSubject->getCountry() !== $validationSubject->getLocalCountryCode()) {
            get_class($validationSubject) . ' can only be used in ' . $validationSubject->getLocalCountryCode();
        }
    }
}
