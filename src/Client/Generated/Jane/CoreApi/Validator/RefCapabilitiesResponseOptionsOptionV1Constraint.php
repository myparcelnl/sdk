<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator;

class RefCapabilitiesResponseOptionsOptionV1Constraint extends \Symfony\Component\Validator\Constraints\Compound
{
    protected function getConstraints($options): array
    {
        return [new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.'), new \Symfony\Component\Validator\Constraints\Collection(fields: ['is_selected_by_default' => new \Symfony\Component\Validator\Constraints\Required([new \Symfony\Component\Validator\Constraints\Type(type: ['bool']), new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.')]), 'is_required' => new \Symfony\Component\Validator\Constraints\Required([new \Symfony\Component\Validator\Constraints\Type(type: ['bool']), new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.')]), 'requires' => new \Symfony\Component\Validator\Constraints\Required([new \Symfony\Component\Validator\Constraints\All(constraints: [new \Symfony\Component\Validator\Constraints\Type(type: ['string'])]), new \Symfony\Component\Validator\Constraints\Type(type: ['array']), new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.')]), 'excludes' => new \Symfony\Component\Validator\Constraints\Required([new \Symfony\Component\Validator\Constraints\All(constraints: [new \Symfony\Component\Validator\Constraints\Type(type: ['string'])]), new \Symfony\Component\Validator\Constraints\Type(type: ['array']), new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.')])], allowExtraFields: true)];
    }
}