<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Fulfilment;

use MyParcelNL\Sdk\Model\Shipment\Mapping\DeliveryTypeApiMapping;
use MyParcelNL\Sdk\Model\Shipment\Mapping\PackageTypeApiMapping;

/**
 * Compact value object for shipment options on fulfilment orders.
 *
 * Replaces the legacy AbstractDeliveryOptionsAdapter + AbstractShipmentOptionsAdapter
 * hierarchy with a single flat class that covers everything OrderCollection::save() needs.
 *
 * Uses the existing ApiMapping classes to translate v2 enum strings (from the generated
 * client) to v1 integer IDs (required by the Order v1 fulfilment API). When the spec adds
 * new delivery/package types and the client is regenerated, only the mapping needs a new
 * entry — this class does not hardcode any IDs or names.
 */
class OrderShipmentOptions
{
    /** @var int|null */
    private $carrierId;

    /** @var string|null */
    private $date;

    /** @var string|null v2 enum value (e.g. 'STANDARD_DELIVERY') or v1 name (e.g. 'standard') */
    private $deliveryType;

    /** @var string|null v2 enum value (e.g. 'PACKAGE') or v1 name (e.g. 'package') */
    private $packageType;

    /** @var bool|null */
    private $signature;

    /** @var bool|null */
    private $collect;

    /** @var bool|null */
    private $receiptCode;

    /** @var bool|null */
    private $onlyRecipient;

    /** @var bool|null */
    private $ageCheck;

    /** @var bool|null */
    private $largeFormat;

    /** @var bool|null */
    private $return;

    /** @var bool|null */
    private $priorityDelivery;

    /** @var string|null */
    private $labelDescription;

    /** @var int|null */
    private $insurance;

    /**
     * Create from an API response shipment array (as returned by the orders endpoint).
     */
    public static function fromOrderResponse(array $shipmentData): self
    {
        $options = $shipmentData['shipment_options'] ?? [];
        $self    = new self();

        $self->carrierId        = isset($shipmentData['carrier_id']) ? (int) $shipmentData['carrier_id'] : null;
        $self->date             = $options['date'] ?? null;
        $self->deliveryType     = $options['delivery_type'] ?? null;
        $self->packageType      = $options['package_type'] ?? null;
        $self->signature        = isset($options['signature']) ? (bool) $options['signature'] : null;
        $self->collect          = isset($options['collect']) ? (bool) $options['collect'] : null;
        $self->receiptCode      = isset($options['receipt_code']) ? (bool) $options['receipt_code'] : null;
        $self->onlyRecipient    = isset($options['only_recipient']) ? (bool) $options['only_recipient'] : null;
        $self->ageCheck         = isset($options['age_check']) ? (bool) $options['age_check'] : null;
        $self->largeFormat      = isset($options['large_format']) ? (bool) $options['large_format'] : null;
        $self->return           = isset($options['return']) ? (bool) $options['return'] : null;
        $self->priorityDelivery = isset($options['priority_delivery']) ? (bool) $options['priority_delivery'] : null;
        $self->labelDescription = $options['label_description'] ?? null;
        $self->insurance        = isset($options['insurance']) ? (int) $options['insurance'] : null;

        return $self;
    }

    public function getCarrierId(): ?int
    {
        return $this->carrierId;
    }

    public function setCarrierId(?int $carrierId): self
    {
        $this->carrierId = $carrierId;
        return $this;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(?string $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function getDeliveryType(): ?string
    {
        return $this->deliveryType;
    }

    public function setDeliveryType(?string $deliveryType): self
    {
        $this->deliveryType = $deliveryType;
        return $this;
    }

    /**
     * Resolve the delivery type to a v1 integer ID.
     *
     * Accepts both v2 enum strings (e.g. 'STANDARD_DELIVERY') and v1 integer IDs
     * passed as strings (e.g. '2' from API responses). Uses DeliveryTypeApiMapping
     * so new types added to the generated client are automatically supported.
     */
    public function getDeliveryTypeId(): ?int
    {
        if (null === $this->deliveryType) {
            return null;
        }

        // Already a numeric ID (from API response)
        if (is_numeric($this->deliveryType)) {
            return (int) $this->deliveryType;
        }

        $mapping = new DeliveryTypeApiMapping();

        if ($mapping->isValid($this->deliveryType)) {
            return $mapping->enumToId($this->deliveryType);
        }

        return null;
    }

    public function getPackageType(): ?string
    {
        return $this->packageType;
    }

    public function setPackageType(?string $packageType): self
    {
        $this->packageType = $packageType;
        return $this;
    }

    /**
     * Resolve the package type to a v1 integer ID.
     *
     * Accepts both v2 enum strings (e.g. 'PACKAGE') and v1 integer IDs
     * passed as strings (e.g. '1' from API responses). Uses PackageTypeApiMapping
     * so new types added to the generated client are automatically supported.
     */
    public function getPackageTypeId(): ?int
    {
        if (null === $this->packageType) {
            return null;
        }

        // Already a numeric ID (from API response)
        if (is_numeric($this->packageType)) {
            return (int) $this->packageType;
        }

        $mapping = new PackageTypeApiMapping();

        if ($mapping->isValid($this->packageType)) {
            return $mapping->enumToId($this->packageType);
        }

        return null;
    }

    public function hasSignature(): ?bool
    {
        return $this->signature;
    }

    public function setSignature(?bool $signature): self
    {
        $this->signature = $signature;
        return $this;
    }

    public function hasCollect(): ?bool
    {
        return $this->collect;
    }

    public function setCollect(?bool $collect): self
    {
        $this->collect = $collect;
        return $this;
    }

    public function hasReceiptCode(): ?bool
    {
        return $this->receiptCode;
    }

    public function setReceiptCode(?bool $receiptCode): self
    {
        $this->receiptCode = $receiptCode;
        return $this;
    }

    public function hasOnlyRecipient(): ?bool
    {
        return $this->onlyRecipient;
    }

    public function setOnlyRecipient(?bool $onlyRecipient): self
    {
        $this->onlyRecipient = $onlyRecipient;
        return $this;
    }

    public function hasAgeCheck(): ?bool
    {
        return $this->ageCheck;
    }

    public function setAgeCheck(?bool $ageCheck): self
    {
        $this->ageCheck = $ageCheck;
        return $this;
    }

    public function hasLargeFormat(): ?bool
    {
        return $this->largeFormat;
    }

    public function setLargeFormat(?bool $largeFormat): self
    {
        $this->largeFormat = $largeFormat;
        return $this;
    }

    public function isReturn(): ?bool
    {
        return $this->return;
    }

    public function setReturn(?bool $return): self
    {
        $this->return = $return;
        return $this;
    }

    public function isPriorityDelivery(): ?bool
    {
        return $this->priorityDelivery;
    }

    public function setPriorityDelivery(?bool $priorityDelivery): self
    {
        $this->priorityDelivery = $priorityDelivery;
        return $this;
    }

    public function getLabelDescription(): ?string
    {
        return $this->labelDescription;
    }

    public function setLabelDescription(?string $labelDescription): self
    {
        $this->labelDescription = $labelDescription;
        return $this;
    }

    public function getInsurance(): ?int
    {
        return $this->insurance;
    }

    public function setInsurance(?int $insurance): self
    {
        $this->insurance = $insurance;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'carrier'           => $this->getCarrierId(),
            'date'              => $this->getDate(),
            'deliveryType'      => $this->getDeliveryType(),
            'packageType'       => $this->getPackageType(),
            'shipmentOptions'   => [
                'signature'         => $this->hasSignature(),
                'collect'           => $this->hasCollect(),
                'receipt_code'      => $this->hasReceiptCode(),
                'insurance'         => $this->getInsurance(),
                'age_check'         => $this->hasAgeCheck(),
                'only_recipient'    => $this->hasOnlyRecipient(),
                'return'            => $this->isReturn(),
                'large_format'      => $this->hasLargeFormat(),
                'label_description' => $this->getLabelDescription(),
                'priority_delivery' => $this->isPriorityDelivery(),
            ],
        ];
    }
}
