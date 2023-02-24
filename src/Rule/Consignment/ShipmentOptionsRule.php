<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Rule\Consignment;

use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\src\Rule\Rule;

class ShipmentOptionsRule extends Rule
{
    /**
     * @var array
     */
    private $requiredOptions;

    /**
     * @var array
     */
    private $countrySpecificOptions;

    /**
     * @param  null|array $required
     * @param  null|array $countrySpecificOptions
     */
    public function __construct(array $required = null, array $countrySpecificOptions = null)
    {
        if (is_array($required)) {
            $this->requiredOptions = $required;
        }

        if (is_array($countrySpecificOptions)) {
            $this->countrySpecificOptions = $countrySpecificOptions;
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
            $this->validateRequiredOptions($options);
        }

        if (! empty($this->countrySpecificOptions)) {
            $this->validateCountrySpecificOptions($validationSubject);
        }
    }

    /**
     * @param  \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment $validationSubject
     */
    private function validateCountrySpecificOptions(AbstractConsignment $validationSubject): void
    {
        foreach ($this->countrySpecificOptions as $country => $conditionalOption) {
            if ($country !== $validationSubject->getCountry()) {
                continue;
            }

            foreach ($conditionalOption as $option) {
                if (in_array($option, $validationSubject->getAllowedShipmentOptions(), true)) {
                    continue;
                }

                $this->addError(sprintf('%s is not allowed in %s', $option, get_class($validationSubject)));
                return;
            }
        }
    }

    /**
     * @param  array $options
     */
    private function validateRequiredOptions(array $options): void
    {
        foreach ($this->requiredOptions as $requiredOption) {
            if ($options[$requiredOption]) {
                continue;
            }

            $this->addError( "$requiredOption is required for this carrier");
            return;
        }
    }
}
