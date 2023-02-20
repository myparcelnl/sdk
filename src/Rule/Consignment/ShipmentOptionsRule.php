<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Rule\Consignment;

use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\src\Rule\Rule;

class ShipmentOptionsRule extends Rule
{
    private $requiredOptions;

    private $countrySpecific;

    /**
     * @param $required
     * @param $countrySpecific
     */
    public function __construct($required = null, $countrySpecific = null)
    {
        if (is_array($required)) {
            $this->requiredOptions = $required;
        }

        if (is_array($countrySpecific)) {
            $this->countrySpecific = $countrySpecific;
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

        if (! empty($this->countrySpecific)) {
            $this->validateCountrySpecific($validationSubject);
        }
    }

    /**
     * @param  \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment $validationSubject
     */
    private function validateCountrySpecific(AbstractConsignment $validationSubject): void
    {
        foreach ($this->countrySpecific as $country => $conditionalOption) {
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
