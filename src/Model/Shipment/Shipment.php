<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Shipment;

use MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\ShipmentRequest;
use MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\ShipmentPostShipmentsRequestV11DataShipmentsInnerRecipient as RecipientModel;
use MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\ShipmentPostShipmentsRequestV11DataShipmentsInnerPhysicalProperties as PhysicalPropertiesModel;
use MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\RefShipmentShipmentOptions as GeneratedShipmentOptionsModel;

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

        $recipient = $this->getRecipient();
        if (is_array($recipient)) {
            $this->setRecipient($recipient);
        }

        $physicalProperties = $this->getPhysicalProperties();
        if (is_array($physicalProperties)) {
            $this->setPhysicalProperties($physicalProperties);
        }

        $options = $this->getOptions();
        if (is_array($options) || $options instanceof GeneratedShipmentOptionsModel) {
            $this->setOptions($options);
        }

        if (null !== $this->getCarrier()) {
            $this->setCarrier($this->getCarrier());
        }
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self($data);
    }

    /**
     * TEMP WORKAROUND (remove after CoreAPI spec/codegen fix):
     * Force known shipment fields to scalar types expected by API validation.
     *
     * @return array<string, string>
     */
    public static function openAPITypes()
    {
        $types = parent::openAPITypes();
        $types['reference_identifier'] = 'string';
        $types['carrier'] = 'int';

        return $types;
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
     * Accept both generated model or array, but always store as SDK ShipmentOptions wrapper.
     *
     * TEMP WORKAROUND: wrapper exists to normalize package_type serialization for
     * current generated-client behavior. Remove when upstream/generated types are stable.
     *
     * @param mixed $options
     * @return self
     */
    public function setOptions($options)
    {
        if ($options instanceof GeneratedShipmentOptionsModel && ! $options instanceof ShipmentOptions) {
            $optionsData = json_decode(json_encode($options), true);
            $options = new ShipmentOptions(is_array($optionsData) ? $optionsData : []);
        } elseif (is_array($options)) {
            $options = new ShipmentOptions($options);
        }

        return parent::setOptions($options);
    }

    /**
     * Normalize numeric-string carrier ids to integers before serialization.
     *
     * TEMP WORKAROUND: generated enums currently expose numeric ids as strings.
     *
     * @param int|string $carrier
     * @return self
     */
    public function setCarrier($carrier)
    {
        if (is_string($carrier)) {
            if (ctype_digit($carrier)) {
                $carrier = (int) $carrier;
            } elseif (Carrier::isValid($carrier)) {
                $carrier = Carrier::toId($carrier);
            }
        }

        return parent::setCarrier($carrier);
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
        return $this->setCarrier(Carrier::toId($carrier));
    }

    /**
     * Convenience helper to set package type using SDK-level constant.
     * Package type is nested under shipment options.
     */
    public function withPackageType(string $packageType): self
    {
        $options = $this->getOptions();

        if (null === $options) {
            $options = new ShipmentOptions();
        } elseif (! $options instanceof ShipmentOptions) {
            $optionsData = json_decode(json_encode($options), true);
            $options = new ShipmentOptions(is_array($optionsData) ? $optionsData : []);
        }

        $options->setPackageType(PackageType::toId($packageType));

        $this->setOptions($options);

        return $this;
    }
}
