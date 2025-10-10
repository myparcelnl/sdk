<?php
declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Capabilities;

/**
 * SDK-side request object for capabilities lookup.
 *
 * Required: countryCode
 * Optional: shopId, carrier, deliveryType, packageType, direction, sender, options, physicalProperties
 */
class CapabilitiesRequest
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

    /**
     * Keep constructor minimal: only the required field for immutability & clarity.
     */
    public function __construct(string $countryCode)
    {
        $this->countryCode = $countryCode;
    }

    /**
     * Convenience named constructor.
     */
    public static function forCountry(string $countryCode): self
    {
        return new self($countryCode);
    }

    // -------- immutable withers (with normalization) --------

    public function withShopId(?int $shopId): self
    {
        $clone = clone $this;
        $clone->shopId = $shopId;
        return $clone;
    }

    public function withDeliveryType(?string $deliveryType): self
    {
        $clone = clone $this;
        $clone->deliveryType = $deliveryType; // validation in CapabilitiesMapper
        return $clone;
    }

    public function withPackageType(?string $packageType): self
    {
        $clone = clone $this;
        $clone->packageType = $packageType; // validation in CapabilitiesMapper with generated allowable values
        return $clone;
    }

    public function withCarrier(?string $carrier): self
    {
        $clone = clone $this;
        $clone->carrier = $carrier; // validation in CapabilitiesMapper with RefTypesCarrierV2
        return $clone;
    }

    public function withDirection(?string $direction): self
    {
        $clone = clone $this;
        $clone->direction = $direction; // validation in CapabilitiesMapper with generated allowable values
        return $clone;
    }

    public function withSender(?array $sender): self
    {
        $clone = clone $this;
        $clone->sender = $sender; // validation and object creation in CapabilitiesMapper
        return $clone;
    }

    public function withOptions(?array $options): self
    {
        $clone = clone $this;
        $clone->options = $options; // validation and object creation in CapabilitiesMapper
        return $clone;
    }

    public function withPhysicalProperties(?array $physicalProperties): self
    {
        $clone = clone $this;
        $clone->physicalProperties = $physicalProperties; // validation and object creation in CapabilitiesMapper
        return $clone;
    }

    // -------- getters --------

    public function getCountryCode(): ?string
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
