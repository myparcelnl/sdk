<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Rule\Consignment;

use MyParcelNL\Sdk\src\Rule\Rule;

class RestrictCountriesRule extends Rule
{
    private $allowedCountries;

    /**
     * @param array $countries
     */
    public function __construct(array $countries) {
        $this->allowedCountries = $countries;
        parent::__construct();
    }

    /**
     * @param  \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment $validationSubject
     */
    public function validate($validationSubject): void
    {
        if (in_array($validationSubject->getCountry(), $this->allowedCountries, true)) {
            return;
        }

        $this->addError(
            'Shipments are only allowed to the following countries: ' . implode(', ', $this->allowedCountries)
        );
    }
}
