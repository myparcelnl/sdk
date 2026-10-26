<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator;

class ShipmentPostShipmentsRequestV11DataConstraint extends \Symfony\Component\Validator\Constraints\Compound
{
    protected function getConstraints($options): array
    {
        return [new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.'), new \Symfony\Component\Validator\Constraints\Collection(fields: ['user_agent' => new \Symfony\Component\Validator\Constraints\Optional([new \Symfony\Component\Validator\Constraints\Length(max: 255, maxMessage: 'This value is too long. It should have {{ limit }} characters or less.'), new \Symfony\Component\Validator\Constraints\Type(type: ['string']), new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.')]), 'shipments' => new \Symfony\Component\Validator\Constraints\Required([new \Symfony\Component\Validator\Constraints\Count(max: 100, maxMessage: 'This array has too much values. It should have {{ limit }} values or less.'), new \Symfony\Component\Validator\Constraints\Count(min: 1, minMessage: 'This array has not enough values. It should have {{ limit }} values or more.'), new \Symfony\Component\Validator\Constraints\All(constraints: [new \RefShipmentSenderConstraint(), new \RefShipmentCustomsDeclarationConstraint(), new \RefShipmentPickupConstraint()]), new \Symfony\Component\Validator\Constraints\Type(type: ['array']), new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.')])], allowExtraFields: false)];
    }
}