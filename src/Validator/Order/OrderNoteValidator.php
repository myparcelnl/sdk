<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Validator\Order;

use MyParcelNL\Sdk\Rule\Order\AllPropertiesSetRule;
use MyParcelNL\Sdk\Rule\Order\MaximumNoteLengthRule;
use MyParcelNL\Sdk\Rule\Order\OrderNoteAuthorRule;
use MyParcelNL\Sdk\Validator\AbstractValidator;

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
