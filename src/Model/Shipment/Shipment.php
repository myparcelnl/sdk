<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Shipment;

use InvalidArgumentException;
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
     * Lazily normalize recipient after generated model hydration.
     *
     * @return RecipientModel|null
     */
    public function getRecipient()
    {
        $recipient = parent::getRecipient();

        if (is_array($recipient)) {
            $this->setRecipient($recipient);

            return parent::getRecipient();
        }

        return $recipient;
    }

    /**
     * Lazily normalize physical properties after generated model hydration.
     *
     * @return PhysicalPropertiesModel|null
     */
    public function getPhysicalProperties()
    {
        $physicalProperties = parent::getPhysicalProperties();

        if (is_array($physicalProperties)) {
            $this->setPhysicalProperties($physicalProperties);

            return parent::getPhysicalProperties();
        }

        return $physicalProperties;
    }

    /**
     * Lazily normalize options after generated model hydration.
     *
     * @return ShipmentOptions
     */
    public function getOptions()
    {
        $options = parent::getOptions();

        if (is_array($options) || ($options instanceof ShipmentOptionsModel && ! $options instanceof ShipmentOptions)) {
            $this->setOptions($options);

            return parent::getOptions();
        }

        return $options;
    }

    /**
     * Lazily normalize carrier after generated model hydration.
     *
     * @return int|string|null
     */
    public function getCarrier()
    {
        $carrier = parent::getCarrier();

        if (is_string($carrier) && (ctype_digit($carrier) || Carrier::isValid($carrier))) {
            $this->setCarrier($carrier);

            return parent::getCarrier();
        }

        return $carrier;
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
     * @param array<string, mixed>|RecipientModel $recipient
     * @return self
     */
    public function setRecipient($recipient)
    {
        if (is_array($recipient)) {
            $recipient = new RecipientModel($recipient);
        } elseif (! $recipient instanceof RecipientModel) {
            throw new InvalidArgumentException(
                sprintf(
                    'Recipient must be an array or %s, got %s',
                    RecipientModel::class,
                    is_object($recipient) ? get_class($recipient) : gettype($recipient)
                )
            );
        }

        return parent::setRecipient($recipient);
    }

    /**
     * Accept both generated model or array, but ALWAYS store as generated model.
     *
     * @param array<string, mixed>|PhysicalPropertiesModel $physicalProperties
     * @return self
     */
    public function setPhysicalProperties($physicalProperties)
    {
        if (is_array($physicalProperties)) {
            $physicalProperties = new PhysicalPropertiesModel($physicalProperties);
        } elseif (! $physicalProperties instanceof PhysicalPropertiesModel) {
            throw new InvalidArgumentException(
                sprintf(
                    'Physical properties must be an array or %s, got %s',
                    PhysicalPropertiesModel::class,
                    is_object($physicalProperties) ? get_class($physicalProperties) : gettype($physicalProperties)
                )
            );
        }

        return parent::setPhysicalProperties($physicalProperties);
    }

    /**
     * Accept both generated model or array, but always store as SDK ShipmentOptions wrapper.
     *
     * TEMP WORKAROUND: wrapper exists to normalize package_type serialization for
     * current generated-client behavior. Remove when upstream/generated types are stable.
     *
     * @param array<string, mixed>|ShipmentOptionsModel|ShipmentOptions $options
     * @return self
     */
    public function setOptions($options)
    {
        if ($options instanceof ShipmentOptionsModel && ! $options instanceof ShipmentOptions) {
            $optionsData = json_decode(json_encode($options), true);
            $options = new ShipmentOptions(is_array($optionsData) ? $optionsData : []);
        } elseif (is_array($options)) {
            $options = new ShipmentOptions($options);
        } elseif (! $options instanceof ShipmentOptions) {
            throw new InvalidArgumentException(
                sprintf(
                    'Options must be an array, %s or %s, got %s',
                    ShipmentOptions::class,
                    ShipmentOptionsModel::class,
                    is_object($options) ? get_class($options) : gettype($options)
                )
            );
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
     * Updates only `cc` and keeps any existing recipient fields intact.
     */
    public function withRecipientCountryCode(string $countryCode): self
    {
        $recipient = $this->getRecipient();
        $recipientData = is_array($recipient) ? $recipient : json_decode(json_encode($recipient), true);

        if (! is_array($recipientData)) {
            $recipientData = [];
        }

        $recipientData['cc'] = $countryCode;

        return $this->setRecipient($recipientData);
    }

    /**
     * Convenience helper to set weight in grams.
     * Updates only `weight` and keeps any existing physical properties intact.
     *
     * @param int $grams.
     */
    public function withWeight(int $grams): self
    {
        $physicalProperties = $this->getPhysicalProperties();
        $physicalPropertiesData = is_array($physicalProperties) ? $physicalProperties : json_decode(json_encode($physicalProperties), true);

        if (! is_array($physicalPropertiesData)) {
            $physicalPropertiesData = [];
        }

        $physicalPropertiesData['weight'] = $grams;

        return $this->setPhysicalProperties($physicalPropertiesData);
    }

    /**
     * Convenience helper to set shop id.
     */
    public function withShopId(int $shopId): self
    {
        return $this->setShopId($shopId);
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
        }

        $packageTypeRef = PackageType::toApiRef($packageType);
        $options->setPackageType($packageTypeRef);

        // Re-assign explicitly to make the mutation flow obvious for maintainers.
        $this->setOptions($options);

        return $this;
    }
}
