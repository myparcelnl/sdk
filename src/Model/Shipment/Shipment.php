<?php

namespace MyParcelNL\Sdk\Model\Shipment;

class Shipment
{
    /** @var string */
    private $countryCode;

    /** @var int|null */
    private $shopId;

    /** @var string|null */
    private $deliveryType;

    /** @var string|null */
    private $packageType;

    /** @var string|null */
    private $carrier;

    /** @var string|null */
    private $direction;

    /** @var array|null */
    private $sender;

    /** @var array|null */
    private $options;

    /** @var array|null */
    private $physicalProperties;

    public function __construct($countryCode)
    {
        $this->countryCode = $countryCode;
    }

    public function forCountry(string $countryCode): self
    {
        return new self($countryCode);
    }

    public function withShopId(string $shopId): self
    {
        $clone = clone $this;
        $clone->shopId = $shopId;
        return $clone;
    }

    public function withDeliveryType(string $deliveryType): self
    {
        $clone = clone $this;
        $clone->deliveryType = $deliveryType;
        return $clone;
    }

    public function withPackageType(string $packageType): self
    {
        $clone = clone $this;
        $clone->packageType = $packageType;
        return $clone;
    }

    public function withCarrier(string $carrier): self
    {
        $clone = clone $this;
        $clone->carrier = $carrier;
        return $clone;
    }

    public function withDirection(string $direction): self
    {
        $clone = clone $this;
        $clone->direction = $direction;
        return $clone;
    }

    public function withSender(array $sender): self
    {
        $clone = clone $this;
        $clone->sender = $sender;
        return $clone;
    }

    public function withOptions(array $options): self
    {
        $clone = clone $this;
        $clone->options = $options;
        return $clone;
    }

    public function withPhysicalProperties(array $physicalProperties): self
    {
        $clone = clone $this;
        $clone->physicalProperties = $physicalProperties;
        return $clone;
    }

    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    public function getShopId(): ?int
    {
        return $this->shopId;
    }

    public function getDeliveryType(): ?string
    {
        return $this->deliveryType;
    }

    public function getPackageType(): ?string
    {
        return $this->packageType;
    }

    public function getCarrier(): ?string
    {
        return $this->carrier;
    }

    public function getDirection(): ?string
    {
        return $this->direction;
    }

    public function getSender(): ?array
    {
        return $this->sender;
    }

    public function getOptions(): ?array
    {
        return $this->options;
    }

    public function getPhysicalProperties(): ?array
    {
        return $this->physicalProperties;
    }
}
