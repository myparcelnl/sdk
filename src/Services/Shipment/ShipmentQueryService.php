<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Shipment;

use BackedEnum;
use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipment;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesShipments;
use MyParcelNL\Sdk\Concerns\CreatesCoreApiClient;

/**
 * Queries shipments through the Core API client.
 */
final class ShipmentQueryService
{
    use CreatesCoreApiClient;

    private const DEFAULT_QUERY_PAGE_SIZE = 300;

    /**
     * Query shipments by the query parameters of `GET /shipments`.
     *
     * A key with a null value or an empty string leaves the filter out. An enum case, such as
     * `RefTypesCarrier::Postnl`, goes as its value. A list of integers, such as two statuses, goes as
     * a `;`-separated string. Every other value goes as it is: `false` goes as `0`.
     *
     * @param array<string, mixed> $parameters The query parameters, such as `barcode`, `carrier_id`, `created` and `status`.
     *
     * @return ShipmentDefsShipment[]
     *
     * @throws InvalidArgumentException When a key is not a query parameter of `GET /shipments`, or a
     *                                  value has a type that the spec does not allow, such as `'page' => '2'`.
     */
    public function query(array $parameters = []): array
    {
        return $this->extractShipments($this->api()->getShipments(queryParameters: $this->queryParameters($parameters)));
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

        $ids = self::idList($shipmentIds);

        return $this->extractShipments($this->api()->getShipmentsById(ids: $ids));
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
     * Convert the query parameters to the values that the generated client sends.
     *
     * The generated client rejects a key that the spec does not have, and a wrong type for a key
     * that the spec types, such as `page`.
     *
     * @param  array<string, mixed> $parameters
     * @return array<string, mixed>
     */
    private function queryParameters(array $parameters): array
    {
        if (! array_key_exists('size', $parameters)) {
            $parameters['size'] = self::DEFAULT_QUERY_PAGE_SIZE;
        }

        // A null value or an empty string means "no filter". Without this filter, the
        // generated client sends an empty value, such as `q=`, for a key that allows null, and throws
        // for the other keys. The spec does not document the page size that the API uses without `size`.
        $parameters = array_filter($parameters, static fn (mixed $value): bool => null !== $value && '' !== $value);

        return array_map([self::class, 'queryValue'], $parameters);
    }

    /**
     * The generated client sends a scalar as it is and refuses an object. An enum case goes as its
     * value. A list of integers goes as the `;`-separated string that the spec documents for a list
     * of ids. Another array goes to the generated client as it is, which refuses it for a key that
     * takes one value, such as `barcode`.
     */
    private static function queryValue(mixed $value): mixed
    {
        if ($value instanceof BackedEnum) {
            return $value->value;
        }

        if (is_array($value)) {
            $values = array_map([self::class, 'queryValue'], $value);

            return array_filter($values, 'is_int') === $values ? implode(';', $values) : $value;
        }

        return $value;
    }

    /**
     * Extract the shipments from the generated response model.
     *
     * @return ShipmentDefsShipment[]
     */
    private function extractShipments(?ShipmentResponsesShipments $response): array
    {
        return $response?->data->shipments ?? [];
    }
}
