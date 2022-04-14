<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Validator\Consignment;

use MyParcelNL\Sdk\src\Rule\RequiredRule;
use MyParcelNL\Sdk\src\Validator\AbstractValidator;

class CustomsItemValidator extends AbstractValidator
{
    /**
     * @return \MyParcelNL\Sdk\src\Rule\Rule[]
     */
    protected function getRules(): array
    {
        return [
            new RequiredRule('amount'),
            new RequiredRule('classification'),
            new RequiredRule('country'),
            new RequiredRule('description'),
            new RequiredRule('item_value'),
            new RequiredRule('weight'),
        ];
    }
}
