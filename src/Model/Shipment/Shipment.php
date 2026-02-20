<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Shipment;

use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentRequest;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsInnerRecipient as RecipientModel;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsInnerPhysicalProperties as PhysicalPropertiesModel;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefShipmentShipmentOptions as ShipmentOptionsModel;

/**
 * SDK-facing Shipment model.
 *
 * This class intentionally extends the generated Core API Shipment model, so:
 * - the SDK stays aligned with the OpenAPI spec (fields/enums/validation)
 * - we avoid maintaining a parallel DTO that would drift over time
 *
 * If we ever need SDK-specific helpers, we can add them here without breaking existing imports.
 */
class Shipment extends ShipmentRequest
{
    /**
     * @param array<string, mixed>|null $data
     */
    public function __construct(?array $data = null)
    {
        parent::__construct($data);
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self($data);
    }

    /**
     * Accept both generated model or array, but ALWAYS store as generated model.
     * Keeps the Shipment instance type-stable so downstream code can rely on
     * generated getters without array handling.
     *
     * @param  mixed $recipient
     * @return self
     */
    public function setRecipient($recipient)
    {
        if (is_array($recipient)) {
            $recipient = new RecipientModel($recipient);
        }

        return parent::setRecipient($recipient);
    }

    /**
     * Accept both generated model or array, but ALWAYS store as generated model.
     *
     * @param  mixed $physicalProperties
     * @return self
     */
    public function setPhysicalProperties($physicalProperties)
    {
        if (is_array($physicalProperties)) {
            $physicalProperties = new PhysicalPropertiesModel($physicalProperties);
        }

        return parent::setPhysicalProperties($physicalProperties);
    }

    /**
     * Convenience helper to set recipient country code.
     */
    public function withRecipientCountryCode(string $countryCode): self
    {
        return $this->setRecipient(['cc' => $countryCode]);
    }

    /**
     * Convenience helper to set weight in grams.
     * @param int $grams.
     */
    public function withWeight(int $grams): self
    {
        return $this->setPhysicalProperties(['weight' => $grams]);
    }

    /**
     * Convenience helper to set shop id.
     */
    public function withShopId(int $shopId): self
    {
        return $this->setShopId($shopId);
    }

    /**
     * Convenience helper to set carrier using SDK-level constant.
     */
    public function withCarrier(string $carrier): self
    {
        // Map to API id constant; annotate for IDEs expecting RefTypesCarrier.
        /** @var \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefTypesCarrier $ref */
        $ref = Carrier::toApiRef($carrier);

        return $this->setCarrier($ref);
    }

    /**
     * Convenience helper to set package type using SDK-level constant.
     * Package type is nested under shipment options.
     */
    public function withPackageType(string $packageType): self
    {
        $options = $this->getOptions();

        if (null === $options) {
            $options = new ShipmentOptionsModel();
        }

        $packageTypeRef = PackageType::toApiRef($packageType);
        $options->setPackageType($packageTypeRef);

        // Re-assign explicitly to make the mutation flow obvious for maintainers.
        $this->setOptions($options);

        return $this;
    }
}
