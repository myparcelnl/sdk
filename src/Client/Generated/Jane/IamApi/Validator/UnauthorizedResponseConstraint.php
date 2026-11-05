<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Validator;

class UnauthorizedResponseConstraint extends \Symfony\Component\Validator\Constraints\Compound
{
    protected function getConstraints($options): array
    {
        return [new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.'), new \Symfony\Component\Validator\Constraints\Collection(fields: ['message' => new \Symfony\Component\Validator\Constraints\Required([new \Symfony\Component\Validator\Constraints\Type(type: ['string']), new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.'), new \Symfony\Component\Validator\Constraints\EqualTo(value: 'Unauthorized', message: 'This value should be equal to "{{ compared_value }}".')])], allowExtraFields: true)];
    }
}