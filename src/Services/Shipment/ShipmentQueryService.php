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

    private ShipmentApi $api;

    private PsrClientInterface $httpClient;

    public function __construct(
        string $apiKey,
        ?ShipmentApi $api = null,
        ?PsrClientInterface $httpClient = null,
        ?string $host = null
    ) {
        $this->api = $api ?? ShipmentApiFactory::make($apiKey, $host);
        $this->httpClient = $httpClient ?? new GuzzleClient(['timeout' => 10]);
    }

    /**
     * Query shipments by available API filters.
     *
     * @param array<string, mixed> $parameters
     * @return ShipmentDefsShipment[]
     */
    public function query(array $parameters = []): array
    {
        $size = array_key_exists('size', $parameters) ? $parameters['size'] : 300;

        $request = $this->api->getShipmentsRequest(
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

        return $this->sendAndParseShipments($request);
    }

    public function find(int $shipmentId): ?ShipmentDefsShipment
    {
        $shipments = $this->findMany([$shipmentId]);

        return $shipments[0] ?? null;
    }

    /**
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

    public function findByReferenceId(string $referenceIdentifier): ?ShipmentDefsShipment
    {
        $shipments = $this->findManyByReferenceId([$referenceIdentifier]);

        return $shipments[0] ?? null;
    }

    /**
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
