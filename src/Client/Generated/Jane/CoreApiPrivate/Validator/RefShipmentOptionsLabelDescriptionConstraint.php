<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Validator;

class RefShipmentOptionsLabelDescriptionConstraint extends \Symfony\Component\Validator\Constraints\Compound
{
    protected function getConstraints($options): array
    {
        return [new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.'), new \Symfony\Component\Validator\Constraints\Collection(fields: ['label_description' => new \Symfony\Component\Validator\Constraints\Optional([new \Symfony\Component\Validator\Constraints\Length(max: 45, maxMessage: 'This value is too long. It should have {{ limit }} characters or less.'), new \Symfony\Component\Validator\Constraints\Type(type: ['string']), new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.')])], allowExtraFields: true)];
    }
}