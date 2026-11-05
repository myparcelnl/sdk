<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Validator;

class PackageCommonConstraint extends \Symfony\Component\Validator\Constraints\Compound
{
    protected function getConstraints($options): array
    {
        return [new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.'), new \Symfony\Component\Validator\Constraints\Collection(fields: ['customsDeclaration' => new \Symfony\Component\Validator\Constraints\Optional([new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.'), new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Validator\CustomsDeclarationConstraint()]), 'options' => new \Symfony\Component\Validator\Constraints\Optional([new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.'), new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Validator\ShipmentOptionsPackageConstraint()]), 'physicalProperties' => new \Symfony\Component\Validator\Constraints\Optional([new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.'), new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Validator\PhysicalPropertiesWeightRequiredConstraint()])], allowExtraFields: true)];
    }
}