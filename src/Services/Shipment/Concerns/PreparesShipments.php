<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Shipment\Concerns;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11Data;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItem as ShipmentItem;
use MyParcelNL\Sdk\Client\Jane\CoreApiModels;

/**
 * Builds the request of `POST /shipments` for ShipmentCreateService and ShipmentPrintService.
 */
trait PreparesShipments
{
    /**
     * Give each shipment without a reference identifier one that starts with `sdk_`, and build the
     * request from copies of the shipments.
     *
     * The services return the created ids as id => reference identifier, so each shipment needs
     * one. The copies leave out an unset field without a type, such as the email of a recipient,
     * which the spec would reject as null.
     *
     * @param ShipmentItem[] $shipments
     * @param string         $method    The name of the calling method, for the exception message.
     *
     * @throws InvalidArgumentException For an empty list, or an item that is not a ShipmentItem.
     */
    private function createRequest(array $shipments, string $method): ShipmentPostShipmentsRequestV11
    {
        if ([] === $shipments) {
            throw new InvalidArgumentException(sprintf('At least one shipment must be added before calling %s().', $method));
        }

        foreach ($shipments as $shipment) {
            if (! $shipment instanceof ShipmentItem) {
                throw new InvalidArgumentException(sprintf('Each shipment must be a %s, got %s.', ShipmentItem::class, get_debug_type($shipment)));
            }

            if (! isset($shipment->referenceIdentifier) || '' === $shipment->referenceIdentifier) {
                $shipment->referenceIdentifier = 'sdk_' . uniqid('', true);
            }
        }

        $data            = new ShipmentPostShipmentsRequestV11Data();
        $data->shipments = array_map(
            static fn (ShipmentItem $shipment): ShipmentItem => CoreApiModels::copy($shipment),
            array_values($shipments)
        );
        $data->userAgent = $this->getUserAgentHeader();

        $request       = new ShipmentPostShipmentsRequestV11();
        $request->data = $data;

        return $request;
    }

    /**
     * Map the created shipments to id => reference identifier. A reference identifier that the
     * response leaves out comes from the request.
     *
     * @param ShipmentItem[]   $shipments The shipments of the request, in order.
     * @param iterable<object> $created   The shipments or the ids of the response, with `id` and `referenceIdentifier`.
     *
     * @return array<int, string|null>
     */
    private function mapCreatedIds(array $shipments, iterable $created): array
    {
        $requestReferences = array_values(array_map(
            static fn (ShipmentItem $shipment): mixed => $shipment->referenceIdentifier ?? null,
            $shipments
        ));

        $mapping = [];

        foreach ($created as $index => $item) {
            if (! isset($item->id)) {
                continue;
            }

            $referenceIdentifier = $item->referenceIdentifier ?? $requestReferences[$index] ?? null;

            $mapping[$item->id] = null === $referenceIdentifier ? null : (string) $referenceIdentifier;
        }

        return $mapping;
    }
}
