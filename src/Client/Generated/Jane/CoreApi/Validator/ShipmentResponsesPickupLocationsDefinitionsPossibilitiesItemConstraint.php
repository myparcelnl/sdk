<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator;

class ShipmentResponsesPickupLocationsDefinitionsPossibilitiesItemConstraint extends \Symfony\Component\Validator\Constraints\Compound
{
    protected function getConstraints($options): array
    {
        return [new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.'), new \Symfony\Component\Validator\Constraints\Collection(fields: ['delivery_type_id' => new \Symfony\Component\Validator\Constraints\Required([new \Symfony\Component\Validator\Constraints\Type(type: ['integer']), new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.')]), 'delivery_type_name' => new \Symfony\Component\Validator\Constraints\Required([new \Symfony\Component\Validator\Constraints\Type(type: ['string']), new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.')]), 'price' => new \Symfony\Component\Validator\Constraints\Required([new \Symfony\Component\Validator\Constraints\Type(type: ['integer']), new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.')]), 'moment' => new \Symfony\Component\Validator\Constraints\Required([new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.')])], allowExtraFields: false)];
    }
}