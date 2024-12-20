<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Validator\Order;

use MyParcelNL\Sdk\Rule\Consignment\DropOffPointRule;
use MyParcelNL\Sdk\Validator\AbstractValidator;

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
