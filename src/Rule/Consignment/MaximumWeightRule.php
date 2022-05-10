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
    public const MAX_COLLO_WEIGHT_PACKAGE_GRAMS = 30000;
    /**
     * @var int
     */
    public const MAX_COLLO_WEIGHT_MAILBOX_GRAMS = 2000;
    /**
     * @var int
     */
    public const MAX_COLLO_WEIGHT_LETTER_GRAMS = 2000;
    /**
     * @var int
     */
    public const MAX_COLLO_WEIGHT_DIGITAL_STAMP_GRAMS = 2000;

    /**
     * @param  \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment $validationSubject
     *
     * @throws \Exception
     */
    public function validate($validationSubject): void
    {
        $weight      = $validationSubject->getTotalWeight();
        $packageType = $validationSubject->getPackageType();

        switch ($packageType) {
            case AbstractConsignment::PACKAGE_TYPE_PACKAGE:
                $weightLimit = self::MAX_COLLO_WEIGHT_PACKAGE_GRAMS;
                break;
            case AbstractConsignment::PACKAGE_TYPE_MAILBOX:
                $weightLimit = self::MAX_COLLO_WEIGHT_MAILBOX_GRAMS;
                break;
            case AbstractConsignment::PACKAGE_TYPE_LETTER:
                $weightLimit = self::MAX_COLLO_WEIGHT_LETTER_GRAMS;
                break;
            case AbstractConsignment::PACKAGE_TYPE_DIGITAL_STAMP:
                $weightLimit = self::MAX_COLLO_WEIGHT_DIGITAL_STAMP_GRAMS;
                break;
            default:
                $weightLimit = null;
        }

        if (! $weightLimit) {
            return;
        }

        if ($weight > $weightLimit) {
            throw new Exception(
                sprintf(
                    'Order has not been exported to the MyParcel Backoffice. Shipment contains a weight of %s grams, maximum allowed is %s. Change package type or add more labels to distribute weight.',
                    $weight,
                    $weightLimit
                )
            );
        }
    }
}
