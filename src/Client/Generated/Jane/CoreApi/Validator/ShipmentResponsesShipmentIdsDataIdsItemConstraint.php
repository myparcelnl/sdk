<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator;

class ShipmentResponsesShipmentIdsDataIdsItemConstraint extends \Symfony\Component\Validator\Constraints\Compound
{
    protected function getConstraints($options): array
    {
        return [new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.'), new \Symfony\Component\Validator\Constraints\Collection(fields: ['id' => new \Symfony\Component\Validator\Constraints\Required([new \Symfony\Component\Validator\Constraints\Type(type: ['integer']), new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.')]), 'reference_identifier' => new \Symfony\Component\Validator\Constraints\Required([new \Symfony\Component\Validator\Constraints\Length(max: 255, maxMessage: 'This value is too long. It should have {{ limit }} characters or less.'), new \Symfony\Component\Validator\Constraints\Regex(pattern: '#^[^=\?]*$#', message: 'This value is not valid.'), new \Symfony\Component\Validator\Constraints\NotBlank(allowNull: true), new \Symfony\Component\Validator\Constraints\Type(type: ['null', 'integer', 'string'])])], allowExtraFields: false)];
    }
}