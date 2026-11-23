<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Validator;

class ConnectTokenPostResponse401Constraint extends \Symfony\Component\Validator\Constraints\Compound
{
    protected function getConstraints($options): array
    {
        return [new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.'), new \Symfony\Component\Validator\Constraints\Collection(fields: ['error' => new \Symfony\Component\Validator\Constraints\Required([new \Symfony\Component\Validator\Constraints\Type(type: ['string']), new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.'), new \Symfony\Component\Validator\Constraints\EqualTo(value: 'invalid_dpop_proof', message: 'This value should be equal to "{{ compared_value }}".')])], allowExtraFields: true)];
    }
}