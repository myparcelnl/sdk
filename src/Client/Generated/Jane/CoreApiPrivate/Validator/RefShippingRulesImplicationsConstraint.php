<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Validator;

class RefShippingRulesImplicationsConstraint extends \Symfony\Component\Validator\Constraints\Compound
{
    protected function getConstraints($options): array
    {
        return [new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.'), new \Symfony\Component\Validator\Constraints\Collection(fields: ['carrier_id' => new \Symfony\Component\Validator\Constraints\Required([new \Symfony\Component\Validator\Constraints\Type(type: ['integer']), new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.')]), 'contract_id' => new \Symfony\Component\Validator\Constraints\Required([new \Symfony\Component\Validator\Constraints\Type(type: ['null', 'integer'])]), 'shipment_options' => new \Symfony\Component\Validator\Constraints\Required([new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.')]), 'physical_properties' => new \Symfony\Component\Validator\Constraints\Required([new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.')]), 'shipping_rule_id' => new \Symfony\Component\Validator\Constraints\Required([new \Symfony\Component\Validator\Constraints\LessThanOrEqual(value: 9.223372036854776E+18), new \Symfony\Component\Validator\Constraints\GreaterThanOrEqual(value: 1.0), new \Symfony\Component\Validator\Constraints\Type(type: ['null', 'integer'])])], allowExtraFields: true)];
    }
}