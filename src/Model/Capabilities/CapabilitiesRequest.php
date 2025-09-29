<?php

namespace MyParcelNL\Sdk\Model\Capabilities;

class CapabilitiesRequest
{

    /** @var int|null */    private $shopId;
    /** @var string|null */ private $shippingMethod;
    /** @var string|null */ private $carrier;
    /** @var string|null */ private $countryCode;

    public function __construct(
        ?int $shopId = null,
        ?string $shippingMethod = null,
        ?string $carrier = null,
        ?string $countryCode = null
    ) {
        $this->shopId = $shopId;
        $this->shippingMethod = $shippingMethod;
        $this->carrier = $carrier;
        $this->countryCode = $countryCode;
    }

    public function getShopId(): ?int
    {
        return $this->shopId;
    }

    public function getShippingMethod(): ?string
    {
        return $this->shippingMethod;
    }

    public function getCarrier(): ?string
    {
        return $this->carrier;
    }
    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

}
