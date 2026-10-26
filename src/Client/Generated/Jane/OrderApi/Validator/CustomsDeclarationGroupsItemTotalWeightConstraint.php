<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Validator;

class CustomsDeclarationGroupsItemTotalWeightConstraint extends \Symfony\Component\Validator\Constraints\Compound
{
    protected function getConstraints($options): array
    {
        return [new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.'), new \Symfony\Component\Validator\Constraints\Collection(fields: ['unit' => new \Symfony\Component\Validator\Constraints\Required([new \Symfony\Component\Validator\Constraints\Type(type: ['string']), new \Symfony\Component\Validator\Constraints\Choice(choices: ['g', 'kg'], message: '"{{ value }}" is not part of the set of possible choices for this field: "{{ choices }}".'), new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.')]), 'value' => new \Symfony\Component\Validator\Constraints\Required([new \Symfony\Component\Validator\Constraints\LessThanOrEqual(value: 999999999.0), new \Symfony\Component\Validator\Constraints\GreaterThanOrEqual(value: 1.0), new \Symfony\Component\Validator\Constraints\Type(type: ['integer']), new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.')])], allowExtraFields: true)];
    }
}