<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\TrackTrace;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Client;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsTrackTrace;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesTracktraces;
use MyParcelNL\Sdk\Concerns\HasUserAgent;
use MyParcelNL\Sdk\Services\CoreApi\ShipmentApiFactory;

/**
 * Fetches track & trace data through the Core API client.
 */
final class ShipmentTrackTraceService
{
    use HasUserAgent;

    private Client $api;

    public function __construct(
        string $apiKey,
        ?Client $api = null,
        ?string $baseUri = null
    ) {
        $this->api = $api ?? ShipmentApiFactory::client($apiKey, $baseUri);
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
        $response = $this->api->getTrackTracesByIds(
            ids: implode(';', $shipmentIds),
            headerParameters: $this->headerParameters()
        );

        $result = [];

        foreach ($response?->data->tracktraces ?? [] as $tracktrace) {
            if (isset($tracktrace->shipmentId)) {
                $result[$tracktrace->shipmentId] = $tracktrace;
            }
        }

        return $result;
    }
}
