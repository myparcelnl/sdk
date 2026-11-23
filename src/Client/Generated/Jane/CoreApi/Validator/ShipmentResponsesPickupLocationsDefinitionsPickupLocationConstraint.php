<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator;

class ShipmentResponsesPickupLocationsDefinitionsPickupLocationConstraint extends \Symfony\Component\Validator\Constraints\Compound
{
    protected function getConstraints($options): array
    {
        return [new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.'), new \Symfony\Component\Validator\Constraints\Collection(fields: ['address' => new \Symfony\Component\Validator\Constraints\Required([new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.'), new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\ShipmentResponsesPickupLocationsDefinitionsAddressConstraint()]), 'possibilities' => new \Symfony\Component\Validator\Constraints\Required([new \Symfony\Component\Validator\Constraints\Type(type: ['array']), new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.')]), 'location' => new \Symfony\Component\Validator\Constraints\Required([new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.'), new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\ShipmentResponsesPickupLocationsDefinitionsLocationConstraint()])], allowExtraFields: false)];
    }
}