<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Shipment;

use MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\ShipmentPostShipmentsRequestV11DataShipmentsInner as GeneratedShipment;

/**
 * SDK-facing Shipment model.
 *
 * This class intentionally extends the generated Core API Shipment model, so:
 * - the SDK stays aligned with the OpenAPI spec (fields/enums/validation)
 * - we avoid maintaining a parallel DTO that would drift over time
 *
 * If we ever need SDK-specific helpers, we can add them here without breaking existing imports.
 */
class Shipment extends GeneratedShipment
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
}
