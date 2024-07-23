<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Rule\Consignment;

use MyParcelNL\Sdk\src\Rule\Rule;

class MinimumItemValueRule extends Rule
{
    /**
     * @param  \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment $validationSubject
     */
    public function validate($validationSubject): void
    {
        if (empty($validationSubject->items)) {
            return;
        }

        $totalValue = 0;

        foreach ($validationSubject->items as $item) {
            $itemValue = $item->getItemValue();

            if (! isset($itemValue['amount'])) {
                continue;
            }

            $totalValue += (int) $itemValue['amount'];
        }

        if ($totalValue < 100) {
            $this->addError('Price of customs_items must be in cents, with a minimum value of 100');
        }
    }
}
