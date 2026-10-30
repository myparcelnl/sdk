<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Shipment;

use BackedEnum;
use InvalidArgumentException;
use JsonSerializable;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentCustomsDeclaration;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentPickup;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentSender;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesCarrier;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItem as ShipmentModel;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemCollectionContact as CollectionContact;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemDelivered as Delivered;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemDropOffPoint as DropOffPoint;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemGeneralSettings as GeneralSettings;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemHidden as Hidden;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemOptions as OptionsModel;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemPhysicalProperties as PhysicalProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemRecipient as Recipient;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItem as SecondaryShipment;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemShippedItemsItem as ShippedItem;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemStatus as Status;
use MyParcelNL\Sdk\Client\Jane\CoreApiModels;
use ReflectionProperty;

/**
 * A shipment to create, with the getters and setters of v11.
 *
 * The class wraps the generated Jane model. A Jane model has public properties and no getters or
 * setters, so this class keeps the v11 methods for integrators. When a regeneration changes the
 * Jane model, only this class must change, not the code of an integrator.
 *
 * The class does not extend the Jane model. The Jane normalizer accepts only the exact model
 * class, so it would not send a subclass.
 *
 * A setter of a nested object, such as setRecipient(), takes an array with the API field names or
 * the Jane model. A carrier can be an id, a v2 name such as `POSTNL`, or an enum case.
 * `WriteModelAccessorsTest` fails when the Jane model gets a property without a getter and a setter here.
 */
class Shipment implements JsonSerializable
{
    private ShipmentModel $shipment;

    private ?ShipmentOptions $options = null;

    /**
     * Unlike v11, an unknown field throws an exception. This shows a typo at once.
     *
     * @param array<string, mixed>|null $data The fields with their API names, such as `reference_identifier`.
     *
     * @throws InvalidArgumentException For a field that the shipment does not have.
     */
    public function __construct(?array $data = null)
    {
        $this->shipment = new ShipmentModel();

        foreach ($data ?? [] as $field => $value) {
            $setter = 'set' . str_replace('_', '', ucwords((string) $field, '_'));

            if (! method_exists($this, $setter)) {
                throw new InvalidArgumentException("Unknown shipment field '{$field}'");
            }

            $this->{$setter}($value);
        }
    }

    /**
     * The clone gets its own fields and options. A change to the clone does not change the original.
     */
    public function __clone()
    {
        $this->shipment = clone $this->shipment;

        if (null !== $this->options) {
            $this->options = clone $this->options;
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
     * The Jane model that ShipmentCreateService and ShipmentPrintService send.
     *
     * The Jane client accepts only its own model class, not this class. The method returns a copy
     * with the options in it, so a send does not change this shipment. The copy leaves out the
     * fields without a type that nobody set, see CoreApiModels::withoutNullUntypedProperties().
     */
    public function toApiModel(): ShipmentModel
    {
        $shipment = clone $this->shipment;

        if (null !== $this->options) {
            $shipment->options = $this->options->toApiModel();
        }

        return CoreApiModels::withoutNullUntypedProperties($shipment);
    }

    /**
     * @return array<string, mixed> The fields that are set, with their API names.
     */
    public function jsonSerialize(): array
    {
        return CoreApiModels::toArray($this->toApiModel());
    }

    /**
     * Set the country code of the recipient, and keep the other recipient fields.
     */
    public function withRecipientCountryCode(string $countryCode): self
    {
        $recipient     = $this->getRecipient() ?? new Recipient();
        $recipient->cc = $countryCode;

        return $this->setRecipient($recipient);
    }

    /**
     * Set the weight in grams, and keep the other physical properties.
     */
    public function withWeight(int $grams): self
    {
        $physicalProperties         = $this->getPhysicalProperties() ?? new PhysicalProperties();
        $physicalProperties->weight = $grams;

        return $this->setPhysicalProperties($physicalProperties);
    }

    public function withShopId(int $shopId): self
    {
        return $this->setShopId($shopId);
    }

    /**
     * Set the package type in the shipment options.
     *
     * @param int|string|BackedEnum $packageType An id, a v2 name such as `PACKAGE`, or an enum case.
     */
    public function withPackageType(int|string|BackedEnum $packageType): self
    {
        return $this->setOptions(($this->getOptions() ?? new ShipmentOptions())->setPackageType($packageType));
    }

    public function getAccountId(): ?int
    {
        return $this->shipment->accountId ?? null;
    }

    public function setAccountId(?int $accountId): self
    {
        return $this->set('accountId', $accountId);
    }

    public function getShopId(): ?int
    {
        return $this->shipment->shopId ?? null;
    }

    public function setShopId(?int $shopId): self
    {
        return $this->set('shopId', $shopId);
    }

    public function getContractId(): ?int
    {
        return $this->shipment->contractId ?? null;
    }

    public function setContractId(?int $contractId): self
    {
        return $this->set('contractId', $contractId);
    }

    public function getReferenceIdentifier(): int|string|null
    {
        return $this->shipment->referenceIdentifier ?? null;
    }

    public function setReferenceIdentifier(int|string|null $referenceIdentifier): self
    {
        return $this->set('referenceIdentifier', $referenceIdentifier);
    }

    public function getRecipient(): ?Recipient
    {
        return $this->shipment->recipient ?? null;
    }

    /**
     * @param array<string, mixed>|Recipient|null $recipient Such as `['cc' => 'NL', 'postal_code' => '2132JE']`.
     */
    public function setRecipient(array|Recipient|null $recipient): self
    {
        return $this->set('recipient', CoreApiModels::toModel($recipient, Recipient::class));
    }

    public function getSender(): ?RefShipmentSender
    {
        return $this->shipment->sender ?? null;
    }

    /**
     * @param array<string, mixed>|RefShipmentSender|null $sender
     */
    public function setSender(array|RefShipmentSender|null $sender): self
    {
        return $this->set('sender', CoreApiModels::toModel($sender, RefShipmentSender::class));
    }

    /**
     * @return list<float>|null
     */
    public function getRecipients(): ?array
    {
        return $this->shipment->recipients ?? null;
    }

    /**
     * @param list<float>|null $recipients
     */
    public function setRecipients(?array $recipients): self
    {
        return $this->set('recipients', $recipients);
    }

    public function getPhysicalProperties(): ?PhysicalProperties
    {
        return $this->shipment->physicalProperties ?? null;
    }

    /**
     * @param array<string, mixed>|PhysicalProperties|null $physicalProperties Such as `['weight' => 1500]`.
     */
    public function setPhysicalProperties(array|PhysicalProperties|null $physicalProperties): self
    {
        return $this->set('physicalProperties', CoreApiModels::toModel($physicalProperties, PhysicalProperties::class));
    }

    public function getOptions(): ?ShipmentOptions
    {
        return $this->options;
    }

    /**
     * @param array<string, mixed>|ShipmentOptions|OptionsModel|null $options
     */
    public function setOptions(array|ShipmentOptions|OptionsModel|null $options): self
    {
        if ($options instanceof OptionsModel) {
            $options = CoreApiModels::toArray($options);
        }

        $this->options = is_array($options) ? new ShipmentOptions($options) : $options;

        return $this;
    }

    public function getCustomsDeclaration(): ?RefShipmentCustomsDeclaration
    {
        return $this->shipment->customsDeclaration ?? null;
    }

    /**
     * @param array<string, mixed>|RefShipmentCustomsDeclaration|null $customsDeclaration
     */
    public function setCustomsDeclaration(array|RefShipmentCustomsDeclaration|null $customsDeclaration): self
    {
        return $this->set('customsDeclaration', CoreApiModels::toModel($customsDeclaration, RefShipmentCustomsDeclaration::class));
    }

    /**
     * @return int|null The carrier id.
     */
    public function getCarrier(): ?int
    {
        $carrier = $this->shipment->carrier ?? null;

        return $carrier instanceof BackedEnum ? (int) $carrier->value : $carrier;
    }

    /**
     * @param int|string|BackedEnum|null $carrier An id, a v2 name such as `POSTNL`, or an enum case.
     */
    public function setCarrier(int|string|BackedEnum|null $carrier): self
    {
        if ($carrier instanceof BackedEnum) {
            $carrier = $carrier->value;
        }

        if (is_string($carrier)) {
            if (ctype_digit($carrier)) {
                $carrier = (int) $carrier;
            } elseif (Carrier::isValid($carrier)) {
                $carrier = Carrier::toId($carrier);
            } else {
                throw new InvalidArgumentException("Unknown carrier '{$carrier}'");
            }
        }

        return $this->set('carrier', null === $carrier ? null : (RefTypesCarrier::tryFrom($carrier) ?? $carrier));
    }

    public function getPickup(): ?RefShipmentPickup
    {
        return $this->shipment->pickup ?? null;
    }

    /**
     * @param array<string, mixed>|RefShipmentPickup|null $pickup
     */
    public function setPickup(array|RefShipmentPickup|null $pickup): self
    {
        return $this->set('pickup', CoreApiModels::toModel($pickup, RefShipmentPickup::class));
    }

    public function getDropOffPoint(): ?DropOffPoint
    {
        return $this->shipment->dropOffPoint ?? null;
    }

    /**
     * @param array<string, mixed>|DropOffPoint|null $dropOffPoint
     */
    public function setDropOffPoint(array|DropOffPoint|null $dropOffPoint): self
    {
        return $this->set('dropOffPoint', CoreApiModels::toModel($dropOffPoint, DropOffPoint::class));
    }

    public function getNote(): ?string
    {
        return $this->shipment->note ?? null;
    }

    public function setNote(?string $note): self
    {
        return $this->set('note', $note);
    }

    public function getStatus(): Status|int|null
    {
        return $this->shipment->status ?? null;
    }

    public function setStatus(Status|int|null $status): self
    {
        return $this->set('status', $status);
    }

    public function getDelivered(): Delivered|int|null
    {
        return $this->shipment->delivered ?? null;
    }

    public function setDelivered(Delivered|int|null $delivered): self
    {
        return $this->set('delivered', $delivered);
    }

    public function getGeneralSettings(): ?GeneralSettings
    {
        return $this->shipment->generalSettings ?? null;
    }

    /**
     * @param array<string, mixed>|GeneralSettings|null $generalSettings
     */
    public function setGeneralSettings(array|GeneralSettings|null $generalSettings): self
    {
        return $this->set('generalSettings', CoreApiModels::toModel($generalSettings, GeneralSettings::class));
    }

    /**
     * @return list<SecondaryShipment>|null
     */
    public function getSecondaryShipments(): ?array
    {
        return $this->shipment->secondaryShipments ?? null;
    }

    /**
     * @param array<array<string, mixed>|SecondaryShipment>|null $secondaryShipments
     */
    public function setSecondaryShipments(?array $secondaryShipments): self
    {
        return $this->set('secondaryShipments', CoreApiModels::toModels($secondaryShipments, SecondaryShipment::class));
    }

    public function getCollectionContact(): ?CollectionContact
    {
        return $this->shipment->collectionContact ?? null;
    }

    /**
     * @param array<string, mixed>|CollectionContact|null $collectionContact
     */
    public function setCollectionContact(array|CollectionContact|null $collectionContact): self
    {
        return $this->set('collectionContact', CoreApiModels::toModel($collectionContact, CollectionContact::class));
    }

    public function getHidden(): Hidden|int|null
    {
        return $this->shipment->hidden ?? null;
    }

    public function setHidden(Hidden|int|null $hidden): self
    {
        return $this->set('hidden', $hidden);
    }

    /**
     * @return list<ShippedItem>|null
     */
    public function getShippedItems(): ?array
    {
        return $this->shipment->shippedItems ?? null;
    }

    /**
     * @param array<array<string, mixed>|ShippedItem>|null $shippedItems
     */
    public function setShippedItems(?array $shippedItems): self
    {
        return $this->set('shippedItems', CoreApiModels::toModels($shippedItems, ShippedItem::class));
    }

    /**
     * Set a property of the Jane model. Null leaves out a field that the API does not accept as null.
     */
    private function set(string $property, mixed $value): self
    {
        if (null === $value && ! (new ReflectionProperty($this->shipment, $property))->getType()?->allowsNull()) {
            unset($this->shipment->{$property});
        } else {
            $this->shipment->{$property} = $value;
        }

        return $this;
    }
}
