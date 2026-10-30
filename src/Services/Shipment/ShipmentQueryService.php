<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Shipment;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Client;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipment;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesShipments;
use MyParcelNL\Sdk\Concerns\HasUserAgent;
use MyParcelNL\Sdk\Model\Shipment\FoundShipment;
use MyParcelNL\Sdk\Services\CoreApi\ShipmentApiFactory;

/**
 * Query/fetch service for shipments using generated Core API endpoints.
 */
final class ShipmentQueryService
{
    use HasUserAgent;

    private const DEFAULT_QUERY_PAGE_SIZE = 300;

    private Client $api;

    public function __construct(
        string $apiKey,
        ?Client $api = null,
        ?string $host = null
    ) {
        $this->api = $api ?? ShipmentApiFactory::make($apiKey, $host);
    }

    /**
     * Query shipments by available API filters.
     *
     * @param array<string, mixed> $parameters Supported keys match the generated getShipments query
     *                                         parameters (barcode, carrier_id, created, status, etc.).
     * @return FoundShipment[]
     * @throws InvalidArgumentException When a key is not a getShipments query parameter, or a value
     *                                  has the wrong type.
     */
    public function query(array $parameters = []): array
    {
        return $this->extractShipments($this->api->getShipments($this->queryParameters($parameters), $this->headerParameters()));
    }

    /**
     * Find a single shipment by its ID.
     *
     * @return FoundShipment|null The shipment, or null if not found.
     */
    public function find(int $shipmentId): ?FoundShipment
    {
        $shipments = $this->findMany([$shipmentId]);

        return $shipments[0] ?? null;
    }

    /**
     * Find multiple shipments by their IDs in a single API call.
     *
     * @param int[] $shipmentIds
     * @return FoundShipment[]
     */
    public function findMany(array $shipmentIds): array
    {
        if (empty($shipmentIds)) {
            return [];
        }

        $ids = implode(';', array_map('intval', $shipmentIds));

        return $this->extractShipments($this->api->getShipmentsById($ids, [], $this->headerParameters()));
    }

    /**
     * Find all shipments that match a single reference identifier.
     *
     * @return FoundShipment[]
     */
    public function findByReferenceId(string $referenceIdentifier): array
    {
        // Explicitly pass size=null to use the API default; the API returns 0 results
        // when size >= 200 is combined with reference_identifier.
        return $this->query(['reference_identifier' => $referenceIdentifier, 'size' => null]);
    }

    /**
     * Add the page size to the query parameters.
     *
     * The generated client checks the keys and the types. An unknown key or a wrong type throws an
     * InvalidArgumentException.
     *
     * @param  array<string, mixed> $parameters
     * @return array<string, mixed>
     */
    private function queryParameters(array $parameters): array
    {
        if (! array_key_exists('size', $parameters)) {
            $parameters['size'] = self::DEFAULT_QUERY_PAGE_SIZE;
        } elseif (null === $parameters['size']) {
            // The generated client accepts only an int. Without the key, the API uses its default.
            unset($parameters['size']);
        }

        return $parameters;
    }

    /**
     * Read the User-Agent per call: an integrator can call setUserAgentForProposition() after the
     * constructor.
     *
     * @return array<string, string>
     */
    private function headerParameters(): array
    {
        return ['User-Agent' => $this->getUserAgentHeader()];
    }

    /**
     * Extract the shipments array from the generated response model.
     *
     * @return FoundShipment[]
     */
    private function extractShipments(?ShipmentResponsesShipments $response): array
    {
        return array_map(
            static fn (ShipmentDefsShipment $shipment): FoundShipment => new FoundShipment($shipment),
            $response?->data->shipments ?? []
        );
    }
}
