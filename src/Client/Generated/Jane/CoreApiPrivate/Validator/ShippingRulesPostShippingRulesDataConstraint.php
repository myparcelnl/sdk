<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Validator;

class ShippingRulesPostShippingRulesDataConstraint extends \Symfony\Component\Validator\Constraints\Compound
{
    protected function getConstraints($options): array
    {
        return [new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.'), new \Symfony\Component\Validator\Constraints\Collection(fields: ['shipping_rules' => new \Symfony\Component\Validator\Constraints\Required([new \Symfony\Component\Validator\Constraints\Count(min: 1, minMessage: 'This array has not enough values. It should have {{ limit }} values or more.'), new \Symfony\Component\Validator\Constraints\Type(type: ['array']), new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.')])], allowExtraFields: false)];
    }
}