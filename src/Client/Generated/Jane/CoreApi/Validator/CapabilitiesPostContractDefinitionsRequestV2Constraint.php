<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator;

class CapabilitiesPostContractDefinitionsRequestV2Constraint extends \Symfony\Component\Validator\Constraints\Compound
{
    protected function getConstraints($options): array
    {
        return [new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.'), new \Symfony\Component\Validator\Constraints\Collection(fields: ['carrier' => new \Symfony\Component\Validator\Constraints\Optional([new \Symfony\Component\Validator\Constraints\Type(type: ['string']), new \Symfony\Component\Validator\Constraints\Choice(choices: ['BRT', 'DHL_EUROPLUS', 'DHL_PARCEL_CONNECT', 'DPD', 'DHL_FOR_YOU', 'INPOST', 'POSTNL'], message: '"{{ value }}" is not part of the set of possible choices for this field: "{{ choices }}".'), new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.')])], allowExtraFields: false)];
    }
}