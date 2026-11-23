<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Validator;

class ShipmentOptionsFreshFoodConstraint extends \Symfony\Component\Validator\Constraints\Compound
{
    protected function getConstraints($options): array
    {
        return [new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.')];
    }
}