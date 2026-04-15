<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Shipment;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Api\ShipmentApi;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentPostShipmentsRequestV11;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentPostShipmentsRequestV11Data;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentResponsesPostShipmentsV12;
use MyParcelNL\Sdk\Concerns\HasUserAgent;
use MyParcelNL\Sdk\Collection\ShipmentCollection;
use MyParcelNL\Sdk\Model\Shipment\Shipment;
use MyParcelNL\Sdk\Services\CoreApi\ShipmentApiFactory;
use MyParcelNL\Sdk\Services\CoreApi\Concerns\ResolvesPostShipmentsContentType;
use MyParcelNL\Sdk\Services\Shipment\Concerns\EnsuresShipmentReferenceIds;

final class ShipmentCreateService
{
    use HasUserAgent;
    use EnsuresShipmentReferenceIds;
    use ResolvesPostShipmentsContentType;

    private ShipmentApi $api;

    public function __construct(
        string $apiKey,
        ?ShipmentApi $api = null,
        ?string $host = null
    ) {
        $this->api = $api ?? ShipmentApiFactory::make($apiKey, $host);
    }

    /**
     * Create shipments through the generated ShipmentApi client.
     *
     * Builds the generated request model, applies SDK defaults such as reference identifiers,
     * and maps the typed generated success response back to the SDK's
     * id => reference_identifier return shape.
     *
     * @param ShipmentCollection $collection Collection of shipments to create.
     * @param mixed              $format     Label format (e.g. "A4", "A6") or generated format reference.
     * @param mixed              $positions  Label position(s) for A4 (e.g. "1;2;3;4") or generated position reference.
     *
     * @return array<int, string|null> Mapping shipment id => reference identifier.
     */
    public function create(ShipmentCollection $collection, $format = null, $positions = null): array
    {
        $shipments = $collection->values()->all();

        $this->validateBeforeCreate($shipments);
        $this->ensureReferenceIds($shipments);

        $request = $this->buildCreateRequest($shipments);
        $userAgentHeader = $this->getUserAgentHeader();

        $response = $this->api->postShipments(
            $userAgentHeader,
            $request,
            $format,
            $positions,
            null,
            null,
            $this->resolvePostShipmentsContentType('application/vnd.shipment+json')
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
        $data = new ShipmentPostShipmentsRequestV11Data();
        $data->setShipments($shipments);
        $data->setUserAgent($this->getUserAgentHeader());

        $request = new ShipmentPostShipmentsRequestV11();
        $request->setData($data);

        return $request;
    }

    /**
     * @param Shipment[] $shipments
     *
     * Read only the current generated success model and translate it to the SDK convenience shape.
     *
     * @return array<int, string|null>
     */
    private function parseCreateResponse(array $shipments, $response): array
    {
        if (! $response instanceof ShipmentResponsesPostShipmentsV12) {
            throw new InvalidArgumentException('Unexpected response type returned by ShipmentApi::postShipments()');
        }

        $responseData = $response->getData();
        if (null === $responseData) {
            throw new InvalidArgumentException('ShipmentApi::postShipments() returned a response without data.');
        }

        $requestReferences = array_values(array_map(
            static fn (Shipment $shipment): ?string => $shipment->getReferenceIdentifier(),
            $shipments
        ));

        $mapping = [];
        foreach (($responseData->getShipments() ?? []) as $index => $createdShipment) {
            $referenceIdentifier = $createdShipment->getReferenceIdentifier();

            if (null === $referenceIdentifier && isset($requestReferences[$index])) {
                $referenceIdentifier = $requestReferences[$index];
            }

            $shipmentId = $createdShipment->getId();
            if (null === $shipmentId) {
                continue;
            }

            $mapping[(int) $shipmentId] = $referenceIdentifier;
        }

        return $mapping;
    }
}
