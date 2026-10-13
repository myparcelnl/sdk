<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Capabilities;

use MyParcelNL\Sdk\Model\Shipment\Shipment;
use MyParcelNL\Sdk\Services\Mapping\ShipmentOptionMapper;

/**
 * Request DTO used for capabilities lookups.
 *
 * @see \MyParcelNL\Sdk\Model\Capabilities\CapabilitiesMapper
 */
class CapabilitiesRequest
{
    /**
     * @var string
     */
    private $countryCode;

    /**
     * @var int|null
     */
    private $shopId;

    /**
     * @var string|null
     */
    private $deliveryType;

    /**
     * @var string|null
     */
    private $packageType;

    /**
     * @var string|null
     */
    private $carrier;

    /**
     * @var string|null
     */
    private $direction;

    /**
     * @var array|null
     */
    private $sender;

    /**
     * @var array|null
     */
    private $options;

    /**
     * @var array|null
     */
    private $physicalProperties;

    /**
     * @var array|null
     */
    private $pickup;

    /**
     * @param  string $countryCode
     */
    public function __construct(string $countryCode)
    {
        $this->countryCode = $countryCode;
    }

    /**
     * @param  string $countryCode
     *
     * @return self
     */
    public static function forCountry(string $countryCode): self
    {
        return new self($countryCode);
    }

    /**
     * @param  int|null $shopId
     *
     * @return self
     */
    public function withShopId(?int $shopId): self
    {
        $clone = clone $this;
        $clone->shopId = $shopId;

        return $clone;
    }

    /**
     * @param  string|null $deliveryType
     *
     * @return self
     */
    public function withDeliveryType(?string $deliveryType): self
    {
        $clone = clone $this;
        $clone->deliveryType = $deliveryType;

        return $clone;
    }

    /**
     * @param  string|null $packageType
     *
     * @return self
     */
    public function withPackageType(?string $packageType): self
    {
        $clone = clone $this;
        $clone->packageType = $packageType;

        return $clone;
    }

    /**
     * @param  string|null $carrier
     *
     * @return self
     */
    public function withCarrier(?string $carrier): self
    {
        $clone = clone $this;
        $clone->carrier = $carrier;

        return $clone;
    }

    /**
     * @param  string|null $direction
     *
     * @return self
     */
    public function withDirection(?string $direction): self
    {
        $clone = clone $this;
        $clone->direction = $direction;

        return $clone;
    }

    /**
     * @param  array|null $sender
     *
     * @return self
     */
    public function withSender(?array $sender): self
    {
        $clone = clone $this;
        $clone->sender = $sender;

        return $clone;
    }

    /**
     * Set the requested shipment options. Unknown and unsupported names are omitted when sent.
     * Use getUnsupportedOptions() to decide whether to reject or change the request first.
     *
     * @param  array|null $options
     *
     * @return self
     */
    public function withOptions(?array $options): self
    {
        $clone = clone $this;
        $clone->options = $options;

        return $clone;
    }

    /**
     * @param  array|null $physicalProperties
     *
     * @return self
     */
    public function withPhysicalProperties(?array $physicalProperties): self
    {
        $clone = clone $this;
        $clone->physicalProperties = $physicalProperties;

        return $clone;
    }

    /**
     * @param  array|null $pickup
     *
     * @return self
     */
    public function withPickup(?array $pickup): self
    {
        $clone = clone $this;
        $clone->pickup = $pickup;

        return $clone;
    }

    /**
     * @return string|null
     */
    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    /**
     * @return int|null
     */
    public function getShopId(): ?int
    {
        return $this->shopId;
    }

    /**
     * @return string|null
     */
    public function getDeliveryType(): ?string
    {
        return $this->deliveryType;
    }

    /**
     * @return string|null
     */
    public function getPackageType(): ?string
    {
        return $this->packageType;
    }

    /**
     * @return string|null
     */
    public function getCarrier(): ?string
    {
        return $this->carrier;
    }

    /**
     * @return string|null
     */
    public function getDirection(): ?string
    {
        return $this->direction;
    }

    /**
     * @return array|null
     */
    public function getSender(): ?array
    {
        return $this->sender;
    }

    /**
     * @return array|null
     */
    public function getOptions(): ?array
    {
        return $this->options;
    }

    /**
     * Return option names that cannot be sent in the capabilities v2 request.
     * This includes unknown names and explicitly unsupported options such as tracked.
     * The request is not changed, and option values are not validated by this check.
     *
     * @return string[]
     */
    public function getUnsupportedOptions(): array
    {
        $mapper      = new ShipmentOptionMapper();
        $unsupported = [];

        foreach (array_keys($this->options ?? []) as $name) {
            if (null === $mapper->v2PropertyFromName((string) $name)) {
                $unsupported[] = (string) $name;
            }
        }

        return $unsupported;
    }

    /**
     * @return array|null
     */
    public function getPhysicalProperties(): ?array
    {
        return $this->physicalProperties;
    }

    /** 
     * @return array|null
     */
    public function getPickup(): ?array
    {
        return $this->pickup;
    }

     /**
     * Create a CapabilitiesRequest from a Shipment.
     *
     * Note: Only extracts country code (from recipient) and physical properties from the shipment.
     * Other shipment properties (carrier, package type, options, etc.) are not automatically included.
     * Use the `with*()` methods on the returned instance to add additional properties:
     *
     * Example:
     * $request = CapabilitiesRequest::fromShipment($shipment)
     *     ->withCarrier(\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefCapabilitiesSharedCarrierV2::POSTNL)
     *     ->withPackageType(\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefShipmentPackageTypeV2::PACKAGE);
     *
     * @param  \MyParcelNL\Sdk\Model\Shipment\Shipment $shipment
     *
     * @return self
     * @throws \InvalidArgumentException When recipient country code is missing
     */
    public static function fromShipment(Shipment $shipment): self
    {
        $recipient = $shipment->getRecipient();
        $countryCode = $recipient ? $recipient->getCc() : null;

        if (! $countryCode) {
            throw new \InvalidArgumentException(
                'Recipient with country code (cc) is required for capabilities.'
            );
        }

        $request = self::forCountry((string) $countryCode);

        $physicalProperties = $shipment->getPhysicalProperties();
        if (! $physicalProperties) {
            return $request;
        }

        $projectedPhysicalProperties = [];

        if (null !== $physicalProperties->getWeight()) {
            $projectedPhysicalProperties['weight'] = [
                'value' => (float) $physicalProperties->getWeight(),
                'unit'  => 'g',
            ];
        }

        if (null !== $physicalProperties->getHeight()) {
            $projectedPhysicalProperties['height'] = [
                'value' => (float) $physicalProperties->getHeight(),
                'unit'  => 'cm',
            ];
        }

        if (null !== $physicalProperties->getLength()) {
            $projectedPhysicalProperties['length'] = [
                'value' => (float) $physicalProperties->getLength(),
                'unit'  => 'cm',
            ];
        }

        if (null !== $physicalProperties->getWidth()) {
            $projectedPhysicalProperties['width'] = [
                'value' => (float) $physicalProperties->getWidth(),
                'unit'  => 'cm',
            ];
        }

        return $projectedPhysicalProperties
            ? $request->withPhysicalProperties($projectedPhysicalProperties)
            : $request;
    }
}
