<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Validator;

class RefShipmentOptionsDeliveryTypeDeliveryDateConstraint extends \Symfony\Component\Validator\Constraints\Compound
{
    protected function getConstraints($options): array
    {
        return [new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.'), new \Symfony\Component\Validator\Constraints\Collection(fields: ['delivery_date' => new \Symfony\Component\Validator\Constraints\Optional([new \Symfony\Component\Validator\Constraints\Regex(pattern: '#([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2}):([0-9]{2})#', message: 'This value is not valid.'), new \Symfony\Component\Validator\Constraints\NotBlank(allowNull: true), new \Symfony\Component\Validator\Constraints\Type(type: ['string', 'null'])])], allowExtraFields: true)];
    }
}