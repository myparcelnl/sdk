<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Shipment;

use JsonSerializable;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\AccountDefsContact;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\BillingDefsCurrency;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentCustomsDeclaration;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentGeneralSettings;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentTransactionStatus;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentType;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesCarrier;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesMoneyNullableAmount;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsExternalProviderPropertiesDisplayName;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipment;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentDropOffPoint;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentOptions;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentPaymentStatus;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentPhysicalProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentPickup;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentRecipient;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentSender;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentStatus;
use MyParcelNL\Sdk\Client\Jane\CoreApiModels;

/**
 * A shipment that `ShipmentQueryService` returns, with the getters of v11.
 *
 * A Jane model has public properties and no getters. This class keeps the getters, so v11 code
 * that reads a found shipment keeps working.
 *
 * A getter returns null for a field that the API did not send. `ReadModelGettersTest` fails when
 * the Jane model `ShipmentDefsShipment` gets a property without a getter here. `json_encode()`
 * gives the fields with their API names, as in v11.
 */
final class FoundShipment implements JsonSerializable
{
    public function __construct(private readonly ShipmentDefsShipment $shipment)
    {
    }

    /**
     * The generated Jane model, for a field that has no getter yet.
     */
    public function toApiModel(): ShipmentDefsShipment
    {
        return $this->shipment;
    }

    /**
     * @return array<string, mixed> The fields that the API sent, with their API names.
     */
    public function jsonSerialize(): array
    {
        return CoreApiModels::toArray($this->shipment);
    }

    public function getId(): ?int
    {
        return $this->shipment->id ?? null;
    }

    public function getParentId(): ?int
    {
        return $this->shipment->parentId ?? null;
    }

    public function getAccountId(): ?int
    {
        return $this->shipment->accountId ?? null;
    }

    public function getShopId(): ?int
    {
        return $this->shipment->shopId ?? null;
    }

    public function getShipmentType(): RefShipmentType|int|null
    {
        return $this->shipment->shipmentType ?? null;
    }

    public function getRecipient(): ?ShipmentDefsShipmentRecipient
    {
        return $this->shipment->recipient ?? null;
    }

    public function getSender(): ?ShipmentDefsShipmentSender
    {
        return $this->shipment->sender ?? null;
    }

    public function getStatus(): ShipmentDefsShipmentStatus|int|null
    {
        return $this->shipment->status ?? null;
    }

    public function getOptions(): ?ShipmentDefsShipmentOptions
    {
        return $this->shipment->options ?? null;
    }

    public function getGeneralSettings(): ?RefShipmentGeneralSettings
    {
        return $this->shipment->generalSettings ?? null;
    }

    public function getPickup(): ?ShipmentDefsShipmentPickup
    {
        return $this->shipment->pickup ?? null;
    }

    public function getCustomsDeclaration(): ?RefShipmentCustomsDeclaration
    {
        return $this->shipment->customsDeclaration ?? null;
    }

    public function getPhysicalProperties(): ?ShipmentDefsShipmentPhysicalProperties
    {
        return $this->shipment->physicalProperties ?? null;
    }

    /**
     * @return null|int|string
     */
    public function getReferenceIdentifier(): mixed
    {
        return $this->shipment->referenceIdentifier ?? null;
    }

    public function getTransactionStatus(): RefShipmentTransactionStatus|string|null
    {
        return $this->shipment->transactionStatus ?? null;
    }

    public function getDropOffPoint(): ?ShipmentDefsShipmentDropOffPoint
    {
        return $this->shipment->dropOffPoint ?? null;
    }

    public function getHidden(): ?float
    {
        return $this->shipment->hidden ?? null;
    }

    public function getPrice(): ?RefTypesMoneyNullableAmount
    {
        return $this->shipment->price ?? null;
    }

    public function getBarcode(): ?string
    {
        return $this->shipment->barcode ?? null;
    }

    /**
     * @return mixed
     */
    public function getRegion(): mixed
    {
        return $this->shipment->region ?? null;
    }

    public function getExternalProvider(): ShipmentDefsExternalProviderPropertiesDisplayName|string|null
    {
        return $this->shipment->externalProvider ?? null;
    }

    public function getExternalProviderId(): ?int
    {
        return $this->shipment->externalProviderId ?? null;
    }

    public function getPaymentStatus(): ShipmentDefsShipmentPaymentStatus|string|null
    {
        return $this->shipment->paymentStatus ?? null;
    }

    public function getCarrierId(): RefTypesCarrier|int|null
    {
        return $this->shipment->carrierId ?? null;
    }

    public function getPlatformId(): ?int
    {
        return $this->shipment->platformId ?? null;
    }

    public function getOrigin(): ?string
    {
        return $this->shipment->origin ?? null;
    }

    public function getUserAgent(): ?string
    {
        return $this->shipment->userAgent ?? null;
    }

    /**
     * @return list<ShipmentDefsShipmentSecondaryShipmentsItem>|null
     */
    public function getSecondaryShipments(): ?array
    {
        return $this->shipment->secondaryShipments ?? null;
    }

    public function getCollectionContact(): ?AccountDefsContact
    {
        return $this->shipment->collectionContact ?? null;
    }

    public function getMultiColloMainShipmentId(): ?int
    {
        return $this->shipment->multiColloMainShipmentId ?? null;
    }

    public function getExternalIdentifier(): ?string
    {
        return $this->shipment->externalIdentifier ?? null;
    }

    public function getDelayed(): bool
    {
        return $this->shipment->delayed ?? null;
    }

    public function getDelivered(): bool
    {
        return $this->shipment->delivered ?? null;
    }

    public function getAmount(): ?int
    {
        return $this->shipment->amount ?? null;
    }

    public function getCurrency(): BillingDefsCurrency|string|null
    {
        return $this->shipment->currency ?? null;
    }

    public function getContractId(): ?int
    {
        return $this->shipment->contractId ?? null;
    }

    /**
     * @return mixed|null
     */
    public function getLinkConsumerPortal(): mixed
    {
        return $this->shipment->linkConsumerPortal ?? null;
    }

    /**
     * @return list<ShipmentDefsShipmentPartnerTracktracesItem>|null
     */
    public function getPartnerTracktraces(): ?array
    {
        return $this->shipment->partnerTracktraces ?? null;
    }

    public function getPickupRequestNumber(): ?string
    {
        return $this->shipment->pickupRequestNumber ?? null;
    }

    public function getLastMileCarrierName(): ?string
    {
        return $this->shipment->lastMileCarrierName ?? null;
    }

    public function getOrderShipmentIdentifier(): ?string
    {
        return $this->shipment->orderShipmentIdentifier ?? null;
    }

    /**
     * @return list<ShipmentDefsShipmentShippedItem>|null
     */
    public function getShippedItems(): ?array
    {
        return $this->shipment->shippedItems ?? null;
    }

    public function getCreated(): ?string
    {
        return $this->shipment->created ?? null;
    }

    public function getModified(): ?string
    {
        return $this->shipment->modified ?? null;
    }

    /**
     * @return mixed|null
     */
    public function getCreatedBy(): mixed
    {
        return $this->shipment->createdBy ?? null;
    }

    /**
     * @return mixed|null
     */
    public function getModifiedBy(): mixed
    {
        return $this->shipment->modifiedBy ?? null;
    }
}
