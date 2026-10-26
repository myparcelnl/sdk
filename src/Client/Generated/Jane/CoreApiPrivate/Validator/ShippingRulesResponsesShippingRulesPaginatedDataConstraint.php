<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Validator;

class ShippingRulesResponsesShippingRulesPaginatedDataConstraint extends \Symfony\Component\Validator\Constraints\Compound
{
    protected function getConstraints($options): array
    {
        return [new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.'), new \Symfony\Component\Validator\Constraints\Collection(fields: ['shipping_rules' => new \Symfony\Component\Validator\Constraints\Required([new \Symfony\Component\Validator\Constraints\Type(type: ['array']), new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.')]), 'results' => new \Symfony\Component\Validator\Constraints\Required([new \Symfony\Component\Validator\Constraints\GreaterThanOrEqual(value: 0.0), new \Symfony\Component\Validator\Constraints\Type(type: ['integer']), new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.')]), 'page' => new \Symfony\Component\Validator\Constraints\Required([new \Symfony\Component\Validator\Constraints\LessThanOrEqual(value: 1000.0), new \Symfony\Component\Validator\Constraints\GreaterThanOrEqual(value: 1.0), new \Symfony\Component\Validator\Constraints\Type(type: ['integer']), new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.')]), 'size' => new \Symfony\Component\Validator\Constraints\Required([new \Symfony\Component\Validator\Constraints\LessThanOrEqual(value: 10000.0), new \Symfony\Component\Validator\Constraints\GreaterThanOrEqual(value: 1.0), new \Symfony\Component\Validator\Constraints\Type(type: ['integer']), new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.')])], allowExtraFields: false)];
    }
}