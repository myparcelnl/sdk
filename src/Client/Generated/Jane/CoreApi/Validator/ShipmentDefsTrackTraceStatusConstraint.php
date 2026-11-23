<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator;

class ShipmentDefsTrackTraceStatusConstraint extends \Symfony\Component\Validator\Constraints\Compound
{
    protected function getConstraints($options): array
    {
        return [new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.'), new \Symfony\Component\Validator\Constraints\Collection(fields: ['current' => new \Symfony\Component\Validator\Constraints\Required([new \Symfony\Component\Validator\Constraints\Type(type: ['integer']), new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.')]), 'main' => new \Symfony\Component\Validator\Constraints\Required([new \Symfony\Component\Validator\Constraints\Type(type: ['string', 'null']), new \Symfony\Component\Validator\Constraints\Choice(choices: ['registered', 'handed_to_carrier', 'sorting', 'distribution', 'delivered', null], message: '"{{ value }}" is not part of the set of possible choices for this field: "{{ choices }}".')]), 'final' => new \Symfony\Component\Validator\Constraints\Required([new \Symfony\Component\Validator\Constraints\Type(type: ['bool']), new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.')])], allowExtraFields: false)];
    }
}