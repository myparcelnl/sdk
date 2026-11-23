<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Validator;

class OrderShippingDeliveryOptionsConstraint extends \Symfony\Component\Validator\Constraints\Compound
{
    protected function getConstraints($options): array
    {
        return [new \Symfony\Component\Validator\Constraints\Count(min: 1, minMessage: 'This array has not enough properties. It should have {{ limit }} properties or more.'), new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.'), new \Symfony\Component\Validator\Constraints\Collection(fields: ['notifyOnDelivery' => new \Symfony\Component\Validator\Constraints\Optional([new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.')]), 'preferredDate' => new \Symfony\Component\Validator\Constraints\Optional([new \Symfony\Component\Validator\Constraints\NotNull(message: 'This value should not be null.')])], allowExtraFields: true)];
    }
}