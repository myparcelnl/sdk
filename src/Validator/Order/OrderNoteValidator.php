<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Validator\Order;

use MyParcelNL\Sdk\src\Rule\Order\AllPropertiesSetRule;
use MyParcelNL\Sdk\src\Rule\Order\MaximumNoteLengthRule;
use MyParcelNL\Sdk\src\Rule\Order\OrderNoteAuthorRule;
use MyParcelNL\Sdk\src\Validator\AbstractValidator;

class OrderNoteValidator extends AbstractValidator
{
    protected function getRules(): array
    {
        return [
            new MaximumNoteLengthRule(),
            new AllPropertiesSetRule(),
            new OrderNoteAuthorRule(),
        ];
    }
}
