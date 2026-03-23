<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Shipment;

use MyParcelNL\Sdk\Client\Generated\CoreApi\Api\ShipmentApi;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentDefsShipment;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentResponsesShipments;
use MyParcelNL\Sdk\Concerns\HasUserAgent;
use MyParcelNL\Sdk\Services\CoreApi\ShipmentApiFactory;

/**
 * Query/fetch service for shipments using generated Core API endpoints.
 */
final class ShipmentQueryService
{
    use HasUserAgent;

    private const DEFAULT_QUERY_PAGE_SIZE = 300;

    private ShipmentApi $api;

    public function __construct(
        string $apiKey,
        ?ShipmentApi $api = null,
        ?string $host = null
    ) {
        $this->api = $api ?? ShipmentApiFactory::make($apiKey, $host);
    }

    /**
     * Query shipments by available API filters.
     *
     * @param array<string, mixed> $parameters Supported keys match the generated getShipments query
     *                                         parameters (barcode, carrier_id, created, status, etc.).
     * @return ShipmentDefsShipment[]
     */
    public function query(array $parameters = []): array
    {
        $response = $this->buildGetShipmentsQuery($parameters);

        return $this->extractShipments($response);
    }

    /**
     * Find a single shipment by its ID.
     *
     * @return ShipmentDefsShipment|null The shipment, or null if not found.
     */
    public function find(int $shipmentId): ?ShipmentDefsShipment
    {
        $shipments = $this->findMany([$shipmentId]);

        return $shipments[0] ?? null;
    }

    /**
     * Find multiple shipments by their IDs in a single API call.
     *
     * @param int[] $shipmentIds
     * @return ShipmentDefsShipment[]
     */
    public function findMany(array $shipmentIds): array
    {
        if (empty($shipmentIds)) {
            return [];
        }

        $ids = implode(';', array_map('intval', $shipmentIds));

        /** @var ShipmentResponsesShipments $response */
        $response = $this->api->getShipmentsById($ids, $this->getUserAgentHeader());

        return $this->extractShipments($response);
    }

    /**
     * Find all shipments that match a single reference identifier.
     *
     * @return ShipmentDefsShipment[]
     */
    public function findByReferenceId(string $referenceIdentifier): array
    {

        // Explicitly pass size=null to use the API default; the API returns 0 results
        // when size >= 200 is combined with reference_identifier.
        return $this->query(['reference_identifier' => $referenceIdentifier, 'size' => null]);

    }

    /**
     * Build and execute the getShipments query from a parameter array.
     *
     * Maps the associative $parameters array to the generated getShipments()
     * positional argument list. PHP 7.4 does not support named arguments, so the
     * generated method signature (22 positional params) dictates this mapping.
     */
    private function buildGetShipmentsQuery(array $parameters): ShipmentResponsesShipments
    {
        $size = array_key_exists('size', $parameters)
            ? $parameters['size']
            : self::DEFAULT_QUERY_PAGE_SIZE;

        return $this->api->getShipments(
            $this->getUserAgentHeader(),
            $parameters['barcode'] ?? null,
            $parameters['carrier_id'] ?? null,
            $parameters['created'] ?? null,
            $parameters['delayed'] ?? null,
            $parameters['delivered'] ?? null,
            $parameters['dropoff_today'] ?? null,
            $parameters['filter_hidden_shops'] ?? null,
            $parameters['hidden'] ?? null,
            $parameters['link_consumer_portal'] ?? null,
            $parameters['order'] ?? null,
            $parameters['package_type'] ?? null,
            $parameters['page'] ?? null,
            $parameters['q'] ?? null,
            $parameters['reference_identifier'] ?? null,
            $parameters['region'] ?? null,
            $parameters['shipment_type'] ?? null,
            $parameters['shop_id'] ?? null,
            $size,
            $parameters['sort'] ?? null,
            $parameters['status'] ?? null,
            $parameters['transaction_status'] ?? null
        );
    }

    /**
     * Extract the shipments array from the generated response model.
     *
     * @return ShipmentDefsShipment[]
     */
    private function extractShipments(ShipmentResponsesShipments $response): array
    {
        $data = $response->getData();

        if (null === $data) {
            return [];
        }

        return $data->getShipments() ?? [];
    }

}
