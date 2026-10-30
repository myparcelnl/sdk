<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Shipment;

use BackedEnum;
use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentPackageType;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesMoney;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesPriceEuro;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemOptions as OptionsModel;
use MyParcelNL\Sdk\Client\Jane\CoreApiModels;
use MyParcelNL\Sdk\Model\Shipment\Mapping\DeliveryTypeApiMapping;
use ReflectionProperty;

/**
 * The options of a shipment, with the getters and setters of v11.
 *
 * The class wraps the Jane model and does not extend it, for the same reason as `Shipment`.
 *
 * A package type or delivery type can be an id, a v2 name such as `PACKAGE`, or an enum case. The
 * options store the id that the API expects.
 *
 * A flag, such as `signature`, is 0 or 1. The spec gives it the type `number`, so the Jane model
 * stores a float. The getter returns an int, as in v11.
 *
 * `WriteModelAccessorsTest` fails when the Jane model gets a property without a getter and a setter here.
 */
class ShipmentOptions
{
    private OptionsModel $options;

    /**
     * Unlike v11, an unknown option throws an exception. This shows a typo at once.
     *
     * @param array<string, mixed>|null $data The options with their API names, such as `package_type`.
     *
     * @throws InvalidArgumentException For an option that the shipment options do not have.
     */
    public function __construct(?array $data = null)
    {
        $this->options = new OptionsModel();

        foreach ($data ?? [] as $field => $value) {
            $setter = 'set' . str_replace('_', '', ucwords((string) $field, '_'));

            if (! method_exists($this, $setter)) {
                throw new InvalidArgumentException("Unknown shipment option '{$field}'");
            }

            $this->{$setter}($value);
        }
    }

    /**
     * The clone gets its own options. A change to the clone does not change the original.
     */
    public function __clone()
    {
        $this->options = clone $this->options;
    }

    /**
     * Hydrate shipment options from the legacy Order v1 shipment response shape.
     *
     * Order v1 nests the shared shipment options contract under `shipment_options`
     * and historically used `date` where the shared shipment contract uses
     * `delivery_date`.
     *
     * @param array<string, mixed> $shipmentData
     */
    public static function fromOrderResponse(array $shipmentData): self
    {
        $options = $shipmentData['shipment_options'] ?? $shipmentData['options'] ?? [];

        return new self(array_filter([
            'delivery_date'     => $options['delivery_date'] ?? $options['date'] ?? null,
            'delivery_type'     => $options['delivery_type'] ?? null,
            'package_type'      => $options['package_type'] ?? null,
            'signature'         => self::intBooleanOrNull($options['signature'] ?? null),
            'collect'           => self::intBooleanOrNull($options['collect'] ?? null),
            'receipt_code'      => self::intBooleanOrNull($options['receipt_code'] ?? null),
            'only_recipient'    => self::intBooleanOrNull($options['only_recipient'] ?? null),
            'age_check'         => self::intBooleanOrNull($options['age_check'] ?? null),
            'large_format'      => self::intBooleanOrNull($options['large_format'] ?? null),
            'return'            => self::intBooleanOrNull($options['return'] ?? null),
            'priority_delivery' => self::intBooleanOrNull($options['priority_delivery'] ?? null),
            'label_description' => $options['label_description'] ?? null,
            'insurance'         => $options['insurance'] ?? null,
        ], static function ($value): bool {
            return null !== $value;
        }));
    }

    /**
     * The Jane model that Shipment::toApiModel() puts in the shipment.
     *
     * It is a copy, so a change to the model does not change these options.
     */
    public function toApiModel(): OptionsModel
    {
        return clone $this->options;
    }

    /**
     * @return array<string, mixed> The options that are set, with their API names.
     */
    public function toArray(): array
    {
        return CoreApiModels::toArray($this->options);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayWithoutNull(): array
    {
        return array_filter($this->toArray(), static function ($value): bool {
            return null !== $value;
        });
    }

    public function getInsurance(): ?RefTypesPriceEuro
    {
        return $this->options->insurance ?? null;
    }

    /**
     * @param array<string, mixed>|RefTypesPriceEuro|null $insurance Such as `['amount' => 50000, 'currency' => 'EUR']`.
     */
    public function setInsurance(array|RefTypesPriceEuro|null $insurance): static
    {
        return $this->set('insurance', CoreApiModels::toModel($insurance, RefTypesPriceEuro::class));
    }

    public function getCashOnDelivery(): ?RefTypesMoney
    {
        return $this->options->cashOnDelivery ?? null;
    }

    /**
     * @param array<string, mixed>|RefTypesMoney|null $cashOnDelivery
     */
    public function setCashOnDelivery(array|RefTypesMoney|null $cashOnDelivery): static
    {
        return $this->set('cashOnDelivery', CoreApiModels::toModel($cashOnDelivery, RefTypesMoney::class));
    }

    public function getPackageType(): ?int
    {
        $packageType = $this->options->packageType ?? null;

        return $packageType instanceof BackedEnum ? (int) $packageType->value : $packageType;
    }

    /**
     * @param int|string|BackedEnum|null $packageType An id, a v2 name such as `PACKAGE`, or an enum case.
     */
    public function setPackageType(int|string|BackedEnum|null $packageType): static
    {
        if (null === $packageType) {
            return $this->set('packageType', null);
        }

        $id = self::toId($packageType, static function (string $name): int {
            if (! PackageType::isValid($name)) {
                throw new InvalidArgumentException("Unknown package type '{$name}'");
            }

            return PackageType::toId($name);
        });

        return $this->set('packageType', RefShipmentPackageType::tryFrom($id) ?? $id);
    }

    public function getDeliveryType(): ?int
    {
        return $this->options->deliveryType ?? null;
    }

    /**
     * @param int|string|BackedEnum|null $deliveryType An id, a v2 name such as `STANDARD_DELIVERY`, or an enum case.
     */
    public function setDeliveryType(int|string|BackedEnum|null $deliveryType): static
    {
        if (null === $deliveryType) {
            return $this->set('deliveryType', null);
        }

        return $this->set('deliveryType', self::toId(
            $deliveryType,
            static fn (string $name): int => (new DeliveryTypeApiMapping())->enumToId($name)
        ));
    }

    public function getDeliveryDate(): ?string
    {
        return $this->options->deliveryDate ?? null;
    }

    public function setDeliveryDate(?string $deliveryDate): static
    {
        return $this->set('deliveryDate', $deliveryDate);
    }

    public function getLabelDescription(): ?string
    {
        return $this->options->labelDescription ?? null;
    }

    public function setLabelDescription(?string $labelDescription): static
    {
        return $this->set('labelDescription', $labelDescription);
    }

    public function getAgeCheck(): ?int
    {
        return isset($this->options->ageCheck) ? (int) $this->options->ageCheck : null;
    }

    public function setAgeCheck(int|bool|null $ageCheck): static
    {
        return $this->set('ageCheck', null === $ageCheck ? null : (int) $ageCheck);
    }

    public function getCollect(): ?int
    {
        return isset($this->options->collect) ? (int) $this->options->collect : null;
    }

    public function setCollect(int|bool|null $collect): static
    {
        return $this->set('collect', null === $collect ? null : (int) $collect);
    }

    public function getCooledDelivery(): ?int
    {
        return isset($this->options->cooledDelivery) ? (int) $this->options->cooledDelivery : null;
    }

    public function setCooledDelivery(int|bool|null $cooledDelivery): static
    {
        return $this->set('cooledDelivery', null === $cooledDelivery ? null : (int) $cooledDelivery);
    }

    public function getDropOffAtPostalPoint(): ?int
    {
        return isset($this->options->dropOffAtPostalPoint) ? (int) $this->options->dropOffAtPostalPoint : null;
    }

    public function setDropOffAtPostalPoint(int|bool|null $dropOffAtPostalPoint): static
    {
        return $this->set('dropOffAtPostalPoint', null === $dropOffAtPostalPoint ? null : (int) $dropOffAtPostalPoint);
    }

    public function getExtraAssurance(): ?int
    {
        return isset($this->options->extraAssurance) ? (int) $this->options->extraAssurance : null;
    }

    public function setExtraAssurance(int|bool|null $extraAssurance): static
    {
        return $this->set('extraAssurance', null === $extraAssurance ? null : (int) $extraAssurance);
    }

    public function getFreshFood(): ?int
    {
        return isset($this->options->freshFood) ? (int) $this->options->freshFood : null;
    }

    public function setFreshFood(int|bool|null $freshFood): static
    {
        return $this->set('freshFood', null === $freshFood ? null : (int) $freshFood);
    }

    public function getFrozen(): ?int
    {
        return isset($this->options->frozen) ? (int) $this->options->frozen : null;
    }

    public function setFrozen(int|bool|null $frozen): static
    {
        return $this->set('frozen', null === $frozen ? null : (int) $frozen);
    }

    public function getHideSender(): ?int
    {
        return isset($this->options->hideSender) ? (int) $this->options->hideSender : null;
    }

    public function setHideSender(int|bool|null $hideSender): static
    {
        return $this->set('hideSender', null === $hideSender ? null : (int) $hideSender);
    }

    public function getLargeFormat(): ?int
    {
        return isset($this->options->largeFormat) ? (int) $this->options->largeFormat : null;
    }

    public function setLargeFormat(int|bool|null $largeFormat): static
    {
        return $this->set('largeFormat', null === $largeFormat ? null : (int) $largeFormat);
    }

    public function getNoTracking(): ?int
    {
        return isset($this->options->noTracking) ? (int) $this->options->noTracking : null;
    }

    public function setNoTracking(int|bool|null $noTracking): static
    {
        return $this->set('noTracking', null === $noTracking ? null : (int) $noTracking);
    }

    public function getOnlyRecipient(): ?int
    {
        return isset($this->options->onlyRecipient) ? (int) $this->options->onlyRecipient : null;
    }

    public function setOnlyRecipient(int|bool|null $onlyRecipient): static
    {
        return $this->set('onlyRecipient', null === $onlyRecipient ? null : (int) $onlyRecipient);
    }

    public function getPriorityDelivery(): ?int
    {
        return isset($this->options->priorityDelivery) ? (int) $this->options->priorityDelivery : null;
    }

    public function setPriorityDelivery(int|bool|null $priorityDelivery): static
    {
        return $this->set('priorityDelivery', null === $priorityDelivery ? null : (int) $priorityDelivery);
    }

    public function getReceiptCode(): ?int
    {
        return isset($this->options->receiptCode) ? (int) $this->options->receiptCode : null;
    }

    public function setReceiptCode(int|bool|null $receiptCode): static
    {
        return $this->set('receiptCode', null === $receiptCode ? null : (int) $receiptCode);
    }

    public function getReturn(): ?int
    {
        return isset($this->options->return) ? (int) $this->options->return : null;
    }

    public function setReturn(int|bool|null $return): static
    {
        return $this->set('return', null === $return ? null : (int) $return);
    }

    public function getSameDayDelivery(): ?int
    {
        return isset($this->options->sameDayDelivery) ? (int) $this->options->sameDayDelivery : null;
    }

    public function setSameDayDelivery(int|bool|null $sameDayDelivery): static
    {
        return $this->set('sameDayDelivery', null === $sameDayDelivery ? null : (int) $sameDayDelivery);
    }

    public function getSaturdayDelivery(): ?int
    {
        return isset($this->options->saturdayDelivery) ? (int) $this->options->saturdayDelivery : null;
    }

    public function setSaturdayDelivery(int|bool|null $saturdayDelivery): static
    {
        return $this->set('saturdayDelivery', null === $saturdayDelivery ? null : (int) $saturdayDelivery);
    }

    public function getSignature(): ?int
    {
        return isset($this->options->signature) ? (int) $this->options->signature : null;
    }

    public function setSignature(int|bool|null $signature): static
    {
        return $this->set('signature', null === $signature ? null : (int) $signature);
    }

    public function getTracked(): ?int
    {
        return isset($this->options->tracked) ? (int) $this->options->tracked : null;
    }

    public function setTracked(int|bool|null $tracked): static
    {
        return $this->set('tracked', null === $tracked ? null : (int) $tracked);
    }

    /**
     * Set a property of the Jane model. Null leaves out a field that the API does not accept as null.
     */
    private function set(string $property, mixed $value): static
    {
        if (null === $value && ! (new ReflectionProperty($this->options, $property))->getType()?->allowsNull()) {
            unset($this->options->{$property});
        } else {
            $this->options->{$property} = $value;
        }

        return $this;
    }

    /**
     * @param callable(string): int $nameToId
     */
    private static function toId(int|string|BackedEnum $value, callable $nameToId): int
    {
        if ($value instanceof BackedEnum) {
            $value = $value->value;
        }

        if (is_int($value) || ctype_digit($value)) {
            return (int) $value;
        }

        return $nameToId($value);
    }

    private static function intBooleanOrNull($value): ?int
    {
        return null === $value ? null : (int) (bool) $value;
    }
}
