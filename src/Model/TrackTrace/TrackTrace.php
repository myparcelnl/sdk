<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\TrackTrace;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentShipmentOptions;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesCarrier;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsDeliveryMomentPropertiesTimeFrame;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentPickup;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentRecipient;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentSender;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsTrackTrace;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsTrackTraceStatus;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsTrackingLocation;

/**
 * A track and trace result that `ShipmentTrackTraceService` returns, with the getters of v11.
 *
 * A getter returns null for a field that the API did not send. `ReadModelGettersTest` fails when
 * the Jane model `ShipmentDefsTrackTrace` gets a property without a getter here.
 */
final class TrackTrace
{
    public function __construct(private readonly ShipmentDefsTrackTrace $trackTrace)
    {
    }

    /**
     * The generated Jane model, for a field that has no getter yet.
     */
    public function toApiModel(): ShipmentDefsTrackTrace
    {
        return $this->trackTrace;
    }

    public function getShipmentId(): ?int
    {
        return $this->trackTrace->shipmentId ?? null;
    }

    public function getCarrier(): RefTypesCarrier|int|null
    {
        return $this->trackTrace->carrier ?? null;
    }

    public function getCode(): ?string
    {
        return $this->trackTrace->code ?? null;
    }

    public function getDescription(): ?string
    {
        return $this->trackTrace->description ?? null;
    }

    public function getTime(): ?string
    {
        return $this->trackTrace->time ?? null;
    }

    public function getLinkConsumerPortal(): ?string
    {
        return $this->trackTrace->linkConsumerPortal ?? null;
    }

    public function getLinkTracktrace(): ?string
    {
        return $this->trackTrace->linkTracktrace ?? null;
    }

    /**
     * @return list<ShipmentDefsExternalTrackTraceLink>|null
     */
    public function getPartnerTracktraces(): ?array
    {
        return $this->trackTrace->partnerTracktraces ?? null;
    }

    public function getRecipient(): ?ShipmentDefsShipmentRecipient
    {
        return $this->trackTrace->recipient ?? null;
    }

    public function getSender(): ?ShipmentDefsShipmentSender
    {
        return $this->trackTrace->sender ?? null;
    }

    public function getOptions(): ?RefShipmentShipmentOptions
    {
        return $this->trackTrace->options ?? null;
    }

    public function getPickup(): ?ShipmentDefsShipmentPickup
    {
        return $this->trackTrace->pickup ?? null;
    }

    public function getDelayed(): ?bool
    {
        return $this->trackTrace->delayed ?? null;
    }

    public function getLocation(): ?ShipmentDefsTrackingLocation
    {
        return $this->trackTrace->location ?? null;
    }

    public function getStatus(): ?ShipmentDefsTrackTraceStatus
    {
        return $this->trackTrace->status ?? null;
    }

    /**
     * @return list<ShipmentDefsCarrierTrackTraceEvent>|null
     */
    public function getHistory(): ?array
    {
        return $this->trackTrace->history ?? null;
    }

    public function getDeliveryMomentType(): ?string
    {
        return $this->trackTrace->deliveryMomentType ?? null;
    }

    public function getDeliveryMoment(): ?ShipmentDefsDeliveryMomentPropertiesTimeFrame
    {
        return $this->trackTrace->deliveryMoment ?? null;
    }

    /**
     * @return mixed|null
     */
    public function getSignature(): mixed
    {
        return $this->trackTrace->signature ?? null;
    }

    public function getReturnable(): ?bool
    {
        return $this->trackTrace->returnable ?? null;
    }

    public function getLastMileCarrierName(): ?string
    {
        return $this->trackTrace->lastMileCarrierName ?? null;
    }
}
