<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Capabilities;

use MyParcelNL\Sdk\Model\Shipment\Shipment;

/**
 * Request DTO used for capabilities lookups.
 *
 * @see \MyParcelNL\Sdk\Mapper\CapabilitiesMapper
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
     * @return array|null
     */
    public function getPhysicalProperties(): ?array
    {
        return $this->physicalProperties;
    }

    public static function fromShipment(Shipment $shipment): self
    {
        $recipient = $shipment->getRecipient();
        $cc = $recipient ? $recipient->getCc() : null;

        if (! $cc) {
            throw new \InvalidArgumentException(
                'Recipient with country code (cc) is required for capabilities.'
            );
        }

        $request = self::forCountry((string) $cc);

        $pp = $shipment->getPhysicalProperties();
        if (! $pp) {
            return $request;
        }

        $physical = [];

        if (null !== $pp->getWeight()) {
            $physical['weight'] = ['value' => (float) $pp->getWeight(), 'unit' => 'g'];
        }
        if (null !== $pp->getHeight()) {
            $physical['height'] = ['value' => (float) $pp->getHeight(), 'unit' => 'cm'];
        }
        if (null !== $pp->getLength()) {
            $physical['length'] = ['value' => (float) $pp->getLength(), 'unit' => 'cm'];
        }
        if (null !== $pp->getWidth()) {
            $physical['width']  = ['value' => (float) $pp->getWidth(),  'unit' => 'cm'];
        }

        return $physical ? $request->withPhysicalProperties($physical) : $request;
    }

}
