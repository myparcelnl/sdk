<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Validator;

class OrderShipmentsCommandConstraint extends \Symfony\Component\Validator\Constraints\Compound
{
    protected function getConstraints($options): array
    {
        return [new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.'), new \Symfony\Component\Validator\Constraints\Collection(fields: ['commandId' => new \Symfony\Component\Validator\Constraints\Required([new \Symfony\Component\Validator\Constraints\Type(type: ['string']), new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.')]), 'connectionId' => new \Symfony\Component\Validator\Constraints\Required([new \Symfony\Component\Validator\Constraints\Uuid(), new \Symfony\Component\Validator\Constraints\Type(type: ['string']), new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.')]), 'type' => new \Symfony\Component\Validator\Constraints\Required([new \Symfony\Component\Validator\Constraints\Type(type: ['string']), new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.'), new \Symfony\Component\Validator\Constraints\EqualTo(value: 'ORDER_SHIPMENTS', message: 'This value should be equal to "{{ compared_value }}".')]), 'order' => new \Symfony\Component\Validator\Constraints\Required([new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.')]), 'shipment' => new \Symfony\Component\Validator\Constraints\Required([new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.')])], allowExtraFields: true)];
    }
}