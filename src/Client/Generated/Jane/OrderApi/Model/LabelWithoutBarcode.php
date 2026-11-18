<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class LabelWithoutBarcode implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * Whether the label is credited by us.
     *
     * @var bool
     */
    public bool $credited;
    /**
     * Which principal created the label.
     *
     * @var mixed
     */
    public $createdBy;
    /**
     * The current payment status.
     *
     * @var PaymentStatus|string
     */
    public PaymentStatus|string $paymentStatus;
    public function definedProperties(): array
    {
        return ['credited' => 'credited', 'createdBy' => 'createdBy', 'paymentStatus' => 'paymentStatus'];
    }
}