<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Validator;

class OrdersGetResponse200AggregationsStatusItemConstraint extends \Symfony\Component\Validator\Constraints\Compound
{
    protected function getConstraints($options): array
    {
        return [new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.'), new \Symfony\Component\Validator\Constraints\Collection(fields: ['key' => new \Symfony\Component\Validator\Constraints\Required([new \Symfony\Component\Validator\Constraints\Type(type: ['string']), new \Symfony\Component\Validator\Constraints\Choice(choices: ['CANCELED', 'COMPLETED', 'DISCOVERED', 'IMPORT_REQUESTED', 'OPEN', 'PROCESSING', 'RETURNING'], message: '"{{ value }}" is not part of the set of possible choices for this field: "{{ choices }}".'), new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.')]), 'count' => new \Symfony\Component\Validator\Constraints\Required([new \Symfony\Component\Validator\Constraints\GreaterThanOrEqual(value: 0.0), new \Symfony\Component\Validator\Constraints\Type(type: ['integer']), new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.')])], allowExtraFields: true)];
    }
}