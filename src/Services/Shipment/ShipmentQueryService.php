<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Shipment;

use GuzzleHttp\Client as GuzzleClient;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Api\ShipmentApi;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentDefsShipment;
use MyParcelNL\Sdk\Concerns\HasUserAgent;
use MyParcelNL\Sdk\Services\CoreApi\ModelHydrator;
use MyParcelNL\Sdk\Services\CoreApi\ShipmentApiFactory;
use Psr\Http\Client\ClientInterface as PsrClientInterface;
use Psr\Http\Message\RequestInterface;

/**
 * Query/fetch service for shipments using generated Core API endpoints.
 *
 * Uses hybrid approach (request builder + manual send + constructor-based deserialization)
 * to work around a broken preg_match pattern in the generated ShipmentDefsShipmentRecipient::setStreet().
 * Model constructors write directly to the internal container via setIfExists(), bypassing setter validation.
 *
 * @todo revert to direct generated API methods (getShipments/getShipmentsById) once the
 *       upstream spec/codegen fix for the street pattern is available and the client is regenerated.
 */
final class ShipmentQueryService
{
    use HasUserAgent;

    /**
     * Default page size for query results.
     * Originates from the legacy MyParcelCollection::setLatestData($size = 300) default.
     */
    private const DEFAULT_QUERY_PAGE_SIZE = 300;

    private ShipmentApi $api;

    private PsrClientInterface $httpClient;

    public function __construct(
        string $apiKey,
        ?ShipmentApi $api = null,
        ?PsrClientInterface $httpClient = null,
        ?string $host = null
    ) {
        $this->api = $api ?? ShipmentApiFactory::make($apiKey, $host);
        $this->httpClient = $httpClient ?? new GuzzleClient(['timeout' => ShipmentApiFactory::DEFAULT_HTTP_TIMEOUT]);
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
        $request = $this->buildGetShipmentsRequest($parameters);

        return $this->sendAndParseShipments($request);
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
        $request = $this->api->getShipmentsByIdRequest($ids, $this->getUserAgentHeader());

        return $this->sendAndParseShipments($request);
    }

    /**
     * Find a single shipment by its reference identifier.
     *
     * @return ShipmentDefsShipment|null The shipment, or null if not found.
     */
    public function findByReferenceId(string $referenceIdentifier): ?ShipmentDefsShipment
    {
        $shipments = $this->findManyByReferenceId([$referenceIdentifier]);

        return $shipments[0] ?? null;
    }

    /**
     * Find shipments by multiple reference identifiers.
     *
     * Warning: the MyParcel API only supports filtering by a single reference_identifier per request.
     * This method therefore issues one API call per identifier (N+1). Be mindful of rate limits
     * when passing large arrays.
     *
     * @param string[] $referenceIdentifiers
     * @return ShipmentDefsShipment[]
     */
    public function findManyByReferenceId(array $referenceIdentifiers): array
    {
        $result = [];

        foreach ($referenceIdentifiers as $referenceIdentifier) {
            $referenceIdentifier = trim($referenceIdentifier);

            if ('' === $referenceIdentifier) {
                continue;
            }

            $shipments = $this->query(['reference_identifier' => $referenceIdentifier]);
            $result = $this->mergeUniqueById($result, $shipments);
        }

        return array_values($result);
    }

    /**
     * Send request and parse the shipments response using constructor-based deserialization.
     *
     * @return ShipmentDefsShipment[]
     */
    private function sendAndParseShipments(RequestInterface $request): array
    {
        $response = $this->httpClient->sendRequest($request);
        $decoded = json_decode((string) $response->getBody(), true);

        if (! is_array($decoded) || ! isset($decoded['data']['shipments']) || ! is_array($decoded['data']['shipments'])) {
            return [];
        }

        return array_map([$this, 'hydrateShipment'], $decoded['data']['shipments']);
    }

    /**
     * Build the getShipments request from a parameter array.
     *
     * Maps the associative $parameters array to the generated getShipmentsRequest()
     * positional argument list. PHP 7.4 does not support named arguments, so the
     * generated method signature (22 positional params) dictates this mapping.
     */
    private function buildGetShipmentsRequest(array $parameters): RequestInterface
    {
        $size = array_key_exists('size', $parameters)
            ? $parameters['size']
            : self::DEFAULT_QUERY_PAGE_SIZE;

        return $this->api->getShipmentsRequest(
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
     * Recursively hydrate a ShipmentDefsShipment from raw array data.
     *
     * Uses ModelHydrator to construct all nested typed models (recipient, sender,
     * status, options, etc.) via their constructors, bypassing setter validation.
     */
    private function hydrateShipment(array $data): ShipmentDefsShipment
    {
        /** @var ShipmentDefsShipment */
        return ModelHydrator::hydrate(ShipmentDefsShipment::class, $data);
    }

    /**
     * @param ShipmentDefsShipment[] $existing
     * @param ShipmentDefsShipment[] $incoming
     * @return array<int|string, ShipmentDefsShipment>
     */
    private function mergeUniqueById(array $existing, array $incoming): array
    {
        $merged = $existing;

        foreach ($incoming as $shipment) {
            $id = $shipment->getId();

            if (null === $id) {
                $merged[] = $shipment;
                continue;
            }

            $merged[(int) $id] = $shipment;
        }

        return $merged;
    }
}
