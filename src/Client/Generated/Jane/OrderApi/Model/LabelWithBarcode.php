<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class LabelWithBarcode implements AdditionalPropertiesInterface
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
    /**
     * The barcode of the label.
     *
     * @var string
     */
    public string $barcode;
    /**
     * Whether the shipment is delayed.
     *
     * @var bool
     */
    public bool $delayed;
    /**
     * Additional shipment identifiers provided by the carrier.
     *
     * @var list<string>
     */
    public array $additionalIdentifiers;
    /**
     * The partner barcodes when the parcel is taken over by a partner carrier.
     *
     * @var list<string>
     */
    public array $partnerBarcodes;
    public function definedProperties(): array
    {
        return ['credited' => 'credited', 'createdBy' => 'createdBy', 'paymentStatus' => 'paymentStatus', 'barcode' => 'barcode', 'delayed' => 'delayed', 'additionalIdentifiers' => 'additionalIdentifiers', 'partnerBarcodes' => 'partnerBarcodes'];
    }
}