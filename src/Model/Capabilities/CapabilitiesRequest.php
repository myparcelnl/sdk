<?php
declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Capabilities;

use MyParcelNL\Sdk\Model\Capabilities\Enum\Carrier as CarrierEnum;
use MyParcelNL\Sdk\Model\Capabilities\Enum\DeliveryType as DeliveryEnum;
use MyParcelNL\Sdk\Model\Capabilities\Enum\Direction as DirectionEnum;
use MyParcelNL\Sdk\Model\Capabilities\Enum\PackageType as PackageEnum;

/**
 * SDK-side request object for capabilities lookup.
 *
 * Required: countryCode
 * Optional: shopId, carrier, deliveryType, packageType, direction
 *
 * NOTE: Request 'options', 'sender' and 'physical_properties' are intentionally not included yet.
 *       We'll add them once the SDK modeling is decided (see mapper TODOs).
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
        $clone->deliveryType = DeliveryEnum::normalize($deliveryType);
        return $clone;
    }

    public function withPackageType(?string $packageType): self
    {
        $clone = clone $this;
        $clone->packageType = PackageEnum::normalize($packageType);
        return $clone;
    }

    public function withCarrier(?string $carrier): self
    {
        $clone = clone $this;
        $clone->carrier = CarrierEnum::normalize($carrier);
        return $clone;
    }

    public function withDirection(?string $direction): self
    {
        $clone = clone $this;
        $clone->direction = DirectionEnum::normalize($direction);
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
}
