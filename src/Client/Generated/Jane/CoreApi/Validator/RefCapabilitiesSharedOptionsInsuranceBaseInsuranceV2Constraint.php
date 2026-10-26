<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator;

class RefCapabilitiesSharedOptionsInsuranceBaseInsuranceV2Constraint extends \Symfony\Component\Validator\Constraints\Compound
{
    protected function getConstraints($options): array
    {
        return [new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.'), new \Symfony\Component\Validator\Constraints\Collection(fields: ['insuredAmount' => new \Symfony\Component\Validator\Constraints\Optional([new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.')]), 'default' => new \Symfony\Component\Validator\Constraints\Optional([new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.'), new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\RefTypesMoneyConstraint()]), 'min' => new \Symfony\Component\Validator\Constraints\Optional([new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.'), new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\RefTypesMoneyConstraint()]), 'max' => new \Symfony\Component\Validator\Constraints\Optional([new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.'), new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\RefTypesMoneyConstraint()])], allowExtraFields: true)];
    }
}