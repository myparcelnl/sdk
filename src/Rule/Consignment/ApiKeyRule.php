<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Rule\Consignment;

use MyParcelNL\Sdk\src\Exception\MissingFieldException;
use MyParcelNL\Sdk\src\Rule\Rule;

class ApiKeyRule extends Rule
{
    /**
     * @param  \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment $validationSubject
     *
     * @return void
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    public function validate($validationSubject): void
    {
        if ($validationSubject->getApiKey()) {
            return;
        }

        throw new MissingFieldException(
            'Consignment is missing api key. Use setApiKey().'
        );
    }
}
