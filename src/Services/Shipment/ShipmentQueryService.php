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
     * The query parameters of `GET /shipments` that the spec documents as a `;`-separated list.
     */
    private const LIST_PARAMETERS = ['carrier_id', 'package_type', 'shipment_type', 'shop_id', 'status'];

    /**
     * Query shipments by the query parameters of `GET /shipments`.
     *
     * A key with a null value, an empty string or an empty list leaves the filter out. An enum case,
     * such as `RefTypesCarrier::Postnl`, goes as its value. `carrier_id`, `package_type`,
     * `shipment_type`, `shop_id` and `status` also take a list of integers, such as two statuses,
     * which goes as a `;`-separated string. Every other value goes as it is: `false` goes as `0`.
     * For a list of shipment ids, use findMany().
     *
     * @param array<string, mixed> $parameters The query parameters, such as `barcode`, `carrier_id`, `created` and `status`.
     *
     * @return ShipmentDefsShipment[]
     *
     * @throws InvalidArgumentException When a key is not a query parameter of `GET /shipments`, a
     *                                  value has a type that the spec does not allow, such as
     *                                  `'page' => '2'`, or an array is not a list of integers for
     *                                  a key that takes a list.
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

        $query = [];

        foreach ($parameters as $name => $value) {
            $value = self::queryValue((string) $name, $value);

            // A null value, an empty string or an empty list means "no filter". Without this check,
            // the generated client sends an empty value, such as `q=`, for a key that allows null, and
            // throws for the other keys. The spec does not document the page size that the API uses
            // without `size`.
            if (null !== $value && '' !== $value) {
                $query[$name] = $value;
            }
        }

        return $query;
    }

    /**
     * Convert a value to what the generated client sends: an enum case to its value, and a list of
     * integers to the `;`-separated string that the spec documents.
     *
     * @throws InvalidArgumentException For an array for a key that takes one value, and for a list
     *                                  that holds another value than an integer.
     */
    private static function queryValue(string $name, mixed $value): mixed
    {
        if ($value instanceof BackedEnum) {
            return $value->value;
        }

        if (! is_array($value)) {
            return $value;
        }

        if (! in_array($name, self::LIST_PARAMETERS, true)) {
            throw new InvalidArgumentException(sprintf('The query parameter "%s" takes one value, got a list.', $name));
        }

        $items = array_map(static fn (mixed $item): mixed => $item instanceof BackedEnum ? $item->value : $item, $value);

        foreach ($items as $item) {
            // The spec types the items of `carrier_id` as strings, so `'1'` counts as an integer.
            if (! is_int($item) && ! (is_string($item) && ctype_digit($item))) {
                throw new InvalidArgumentException(sprintf(
                    'The query parameter "%s" takes one value or a list of integers, got a list with %s.',
                    $name,
                    get_debug_type($item)
                ));
            }
        }

        return implode(';', $items);
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
