<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator;

class ShipmentDefsShipmentShippedItemConstraint extends \Symfony\Component\Validator\Constraints\Compound
{
    protected function getConstraints($options): array
    {
        return [new \Symfony\Component\Validator\Constraints\Collection(fields: ['quantity' => new \Symfony\Component\Validator\Constraints\Optional([new \Symfony\Component\Validator\Constraints\Type(type: ['null', 'integer'])]), 'order_line_identifier' => new \Symfony\Component\Validator\Constraints\Optional([new \Symfony\Component\Validator\Constraints\Type(type: ['null', 'string'])]), 'order_line' => new \Symfony\Component\Validator\Constraints\Optional([]), 'picklist_identifier' => new \Symfony\Component\Validator\Constraints\Optional([new \Symfony\Component\Validator\Constraints\Type(type: ['null', 'string'])]), 'order_identifier' => new \Symfony\Component\Validator\Constraints\Optional([new \Symfony\Component\Validator\Constraints\Type(type: ['null', 'string'])])], allowExtraFields: false)];
    }
}