<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Validator\Consignment;

use MyParcelNL\Sdk\src\Rule\Consignment\ApiKeyRule;
use MyParcelNL\Sdk\src\Validator\AbstractValidator;

abstract class AbstractConsignmentValidator extends AbstractValidator
{
    /**
     * @return \MyParcelNL\Sdk\src\Rule\Consignment\ApiKeyRule[]
     */
    protected function getRules(): array
    {
        return [
            new ApiKeyRule(),
        ];
    }
}
