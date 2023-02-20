<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Rule\Consignment;

use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\src\Rule\Rule;

class ShipmentOptionsRule extends Rule
{
    private $requiredOptions;

    private $conditionalOptions;

    /**
     * @param $required
     * @param $conditional
     */
    public function __construct($required = null, $conditional = null)
    {
        if (is_array($required)) {
            $this->requiredOptions = $required;
        }

        if (is_array($conditional)) {
            $this->conditionalOptions = $conditional;
        }

        parent::__construct();
    }

    /**
     * @param  \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment $validationSubject
     */
    public function validate($validationSubject): void
    {
        $options = [
            AbstractConsignment::SHIPMENT_OPTION_AGE_CHECK         => $validationSubject->hasAgeCheck(),
            AbstractConsignment::SHIPMENT_OPTION_INSURANCE         => $validationSubject->getInsurance(),
            AbstractConsignment::SHIPMENT_OPTION_LARGE_FORMAT      => $validationSubject->isLargeFormat(),
            AbstractConsignment::SHIPMENT_OPTION_ONLY_RECIPIENT    => $validationSubject->isOnlyRecipient(),
            AbstractConsignment::SHIPMENT_OPTION_RETURN            => $validationSubject->isReturn(),
            AbstractConsignment::SHIPMENT_OPTION_SIGNATURE         => $validationSubject->isSignature(),
            AbstractConsignment::SHIPMENT_OPTION_SAME_DAY_DELIVERY => $validationSubject->isSameDayDelivery(),
        ];

        foreach ($options as $option => $value) {
            if ($value && ! in_array($option, $validationSubject->getAllowedShipmentOptions(), true)) {
                $this->addError($option . ' is not allowed in ' . get_class($validationSubject));
            }
        }

        if (! empty($this->requiredOptions)) {
            $this->validateRequired($options);
        }

        if (! empty($this->conditionalOptions)) {
            $this->validateConditional($validationSubject, $options);
        }
    }

    /**
     * @param  \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment $validationSubject
     * @param  array                                                     $options
     */
    private function validateConditional(AbstractConsignment $validationSubject, array $options): void
    {
        foreach ($this->conditionalOptions as $country => $conditionalOption) {
            if ($country === $validationSubject->getCountry()) {
                foreach ($conditionalOption as $option) {
                    if ($option && ! in_array($option, $validationSubject->getAllowedShipmentOptions(), true)) {
                        $this->addError($option . ' is not allowed in ' . get_class($validationSubject));
                    }
                }
            }
        }
    }

    /**
     * @param  array $options
     */
    private function validateRequired(array $options): void
    {
        foreach ($this->requiredOptions as $requiredOption) {
            if (! $options[$requiredOption]) {
                $this->addError($requiredOption . ' is required for this carrier');
            }
        }
    }
}
