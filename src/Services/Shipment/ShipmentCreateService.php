<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Shipment;

use BackedEnum;
use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Client;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11Data;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesPostShipmentsV12;
use MyParcelNL\Sdk\Collection\ShipmentCollection;
use MyParcelNL\Sdk\Concerns\HasUserAgent;
use MyParcelNL\Sdk\Model\Shipment\Shipment;
use MyParcelNL\Sdk\Services\CoreApi\ShipmentApiFactory;
use MyParcelNL\Sdk\Services\Shipment\Concerns\EnsuresShipmentReferenceIds;

final class ShipmentCreateService
{
    use HasUserAgent;
    use EnsuresShipmentReferenceIds;

    private Client $api;

    public function __construct(
        string $apiKey,
        ?Client $api = null,
        ?string $host = null
    ) {
        $this->api = $api ?? ShipmentApiFactory::make($apiKey, $host);
    }

    /**
     * Create shipments through the generated Core API client.
     *
     * Builds the generated request model, applies SDK defaults such as reference identifiers,
     * and maps the typed generated success response back to the SDK's
     * id => reference_identifier return shape.
     *
     * @param ShipmentCollection     $collection Collection of shipments to create.
     * @param string|BackedEnum|null $format     Label format, such as "A4" or "A6".
     * @param mixed                  $positions  Label position(s) for A4, such as "1;2;3;4".
     *
     * @return array<int, string|null> Mapping shipment id => reference identifier.
     */
    public function create(ShipmentCollection $collection, $format = null, $positions = null): array
    {
        $shipments = $collection->values()->all();

        $this->validateBeforeCreate($shipments);
        $this->ensureReferenceIds($shipments);

        $response = $this->api->postShipments(
            $this->buildCreateRequest($shipments),
            array_filter(
                ['format' => self::scalar($format), 'positions' => self::scalar($positions)],
                static fn ($value): bool => null !== $value
            ),
            ['User-Agent' => $this->getUserAgentHeader()]
        );

        return $this->parseCreateResponse($shipments, $response);
    }

    /**
     * @param Shipment[] $shipments
     */
    private function validateBeforeCreate(array $shipments): void
    {
        if (empty($shipments)) {
            throw new InvalidArgumentException('At least one shipment must be added before calling create().');
        }
    }

    /**
     * @param Shipment[] $shipments
     */
    private function buildCreateRequest(array $shipments): ShipmentPostShipmentsRequestV11
    {
        $data            = new ShipmentPostShipmentsRequestV11Data();
        $data->shipments = array_map(static fn (Shipment $shipment) => $shipment->toApiModel(), $shipments);
        $data->userAgent = $this->getUserAgentHeader();

        $request       = new ShipmentPostShipmentsRequestV11();
        $request->data = $data;

        return $request;
    }

    /**
     * Read only the current generated success model and translate it to the SDK convenience shape.
     *
     * @param Shipment[] $shipments
     *
     * @return array<int, string|null>
     */
    private function parseCreateResponse(array $shipments, mixed $response): array
    {
        if (! $response instanceof ShipmentResponsesPostShipmentsV12) {
            throw new InvalidArgumentException('Unexpected response type returned by postShipments()');
        }

        if (! isset($response->data)) {
            throw new InvalidArgumentException('postShipments() returned a response without data.');
        }

        $requestReferences = array_values(array_map(
            static fn (Shipment $shipment) => $shipment->getReferenceIdentifier(),
            $shipments
        ));

        $mapping = [];
        foreach ($response->data->shipments ?? [] as $index => $createdShipment) {
            if (! isset($createdShipment->id)) {
                continue;
            }

            $referenceIdentifier = $createdShipment->referenceIdentifier ?? $requestReferences[$index] ?? null;

            $mapping[$createdShipment->id] = null === $referenceIdentifier ? null : (string) $referenceIdentifier;
        }

        return $mapping;
    }

    /**
     * The query string needs the value of an enum case, such as a label format.
     */
    private static function scalar(mixed $value): mixed
    {
        return $value instanceof BackedEnum ? $value->value : $value;
    }
}
