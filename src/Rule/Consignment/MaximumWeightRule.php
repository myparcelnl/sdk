<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Rule\Consignment;

use Exception;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\src\Rule\Rule;

class MaximumWeightRule extends Rule
{
    /**
     * @var int
     */
    public const MAX_COLLO_WEIGHT_GRAMS = 30000;

    /**
     * @param  \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment $validationSubject
     *
     * @throws \Exception
     */
    public function validate($validationSubject): void
    {
        $weight = $validationSubject->getTotalWeight();

        if ($weight > self::MAX_COLLO_WEIGHT_GRAMS) {
            throw new Exception(
                sprintf(
                    'Order has not been exported to the MyParcel Backoffice. Shipment contains a weight of %s grams, maximum allowed is %s. Change package type or add more labels to distribute weight.',
                    $weight,
                    self::MAX_COLLO_WEIGHT_GRAMS
                )
            );
        }
    }
}
