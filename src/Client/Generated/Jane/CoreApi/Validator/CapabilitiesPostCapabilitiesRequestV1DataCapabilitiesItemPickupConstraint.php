<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator;

class CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemPickupConstraint extends \Symfony\Component\Validator\Constraints\Compound
{
    protected function getConstraints($options): array
    {
        return [new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.'), new \Symfony\Component\Validator\Constraints\Collection(fields: ['location_type' => new \Symfony\Component\Validator\Constraints\Optional([new \Symfony\Component\Validator\Constraints\Type(type: ['string']), new \Symfony\Component\Validator\Constraints\Choice(choices: ['retail', 'locker', 'post_office'], message: '"{{ value }}" is not part of the set of possible choices for this field: "{{ choices }}".'), new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.')])], allowExtraFields: false)];
    }
}