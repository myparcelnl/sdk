<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\TrackTrace;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Client;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsTrackTrace;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesTracktraces;
use MyParcelNL\Sdk\Concerns\CreatesCoreApiClient;

/**
 * Fetches track & trace data through the Core API client.
 */
final class ShipmentTrackTraceService
{
    use CreatesCoreApiClient;

    /**
     * Create the service with an API key, or with a client that keeps its own User-Agent.
     *
     * The service keeps its own constructor, because a caller can pass the host as the named argument `baseUri`.
     */
    public function __construct(
        string $apiKey,
        ?Client $api = null,
        ?string $baseUri = null
    ) {
        $this->apiKey = $apiKey;
        $this->api    = $api;
        $this->host   = $baseUri;
    }

    /**
     * Retrieve the track & trace data of the given shipment IDs.
     *
     * @param int[] $shipmentIds
     *
     * @return array<int, ShipmentDefsTrackTrace> Keyed by shipment ID.
     *
     * @throws InvalidArgumentException For an empty list of IDs.
     */
    public function fetchTrackTraceData(array $shipmentIds): array
    {
        if (empty($shipmentIds)) {
            throw new InvalidArgumentException('At least one shipment ID is required');
        }

        /** @var ShipmentResponsesTracktraces|null $response */
        $response = $this->api()->getTrackTracesByIds(ids: self::idList($shipmentIds));

        $result = [];

        foreach ($response?->data->tracktraces ?? [] as $tracktrace) {
            if (isset($tracktrace->shipmentId)) {
                $result[$tracktrace->shipmentId] = $tracktrace;
            }
        }

        return $result;
    }
}
