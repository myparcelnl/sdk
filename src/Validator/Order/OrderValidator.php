<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Validator\Order;

use MyParcelNL\Sdk\src\Rule\Consignment\DropOffPointRule;
use MyParcelNL\Sdk\src\Validator\AbstractValidator;

class OrderValidator extends AbstractValidator
{
    /**
     * @inheritDoc
     */
    protected function getRules(): array
    {
        return [
            new DropOffPointRule(),
        ];
    }
}
