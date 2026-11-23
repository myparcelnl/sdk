<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Validator;

class LabelWithoutBarcodeConstraint extends \Symfony\Component\Validator\Constraints\Compound
{
    protected function getConstraints($options): array
    {
        return [new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.'), new \Symfony\Component\Validator\Constraints\Collection(fields: ['credited' => new \Symfony\Component\Validator\Constraints\Required([new \Symfony\Component\Validator\Constraints\Type(type: ['bool']), new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.')]), 'createdBy' => new \Symfony\Component\Validator\Constraints\Optional([new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.')]), 'paymentStatus' => new \Symfony\Component\Validator\Constraints\Optional([new \Symfony\Component\Validator\Constraints\Type(type: ['string']), new \Symfony\Component\Validator\Constraints\Choice(choices: ['PAID', 'PENDING', 'UNPAID'], message: '"{{ value }}" is not part of the set of possible choices for this field: "{{ choices }}".'), new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.')])], allowExtraFields: true)];
    }
}