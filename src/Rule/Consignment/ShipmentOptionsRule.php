<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Rule\Consignment;

use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\src\Rule\Rule;

class ShipmentOptionsRule extends Rule
{
    /**
     * @param  \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment $validationSubject
     */
    public function validate($validationSubject): void
    {
        $options = [
            AbstractConsignment::SHIPMENT_OPTION_AGE_CHECK      => $validationSubject->hasAgeCheck(),
            AbstractConsignment::SHIPMENT_OPTION_INSURANCE      => $validationSubject->getInsurance(),
            AbstractConsignment::SHIPMENT_OPTION_LARGE_FORMAT   => $validationSubject->isLargeFormat(),
            AbstractConsignment::SHIPMENT_OPTION_ONLY_RECIPIENT => $validationSubject->isOnlyRecipient(),
            AbstractConsignment::SHIPMENT_OPTION_RETURN         => $validationSubject->isReturn(),
            AbstractConsignment::SHIPMENT_OPTION_SIGNATURE      => $validationSubject->isSignature(),
        ];

        foreach ($options as $option => $value) {
            if ($value && ! in_array($option, $validationSubject->getAllowedShipmentOptions(), true)) {
                $this->addError($option . ' is not allowed in ' . get_class($validationSubject));
            }
        }
    }
}
