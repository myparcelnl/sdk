<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\TrackTrace;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Client;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesTracktraces;
use MyParcelNL\Sdk\Concerns\HasUserAgent;
use MyParcelNL\Sdk\Model\TrackTrace\TrackTrace;
use MyParcelNL\Sdk\Services\CoreApi\ShipmentApiFactory;

/**
 * Track & trace service for shipments using generated Core API endpoints.
 *
 * The service adds little to the Jane client, but it keeps the API of v11. So v11 code that calls
 * it keeps working.
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
     * Retrieve track & trace information for the given shipment IDs.
     *
     * @param int[] $shipmentIds
     * @return array<int, TrackTrace> Keyed by shipment ID.
     */
    public function fetchTrackTraceData(array $shipmentIds): array
    {
        if (empty($shipmentIds)) {
            throw new InvalidArgumentException('At least one shipment ID is required');
        }

        /** @var ShipmentResponsesTracktraces|null $response */
        $response = $this->api->getTrackTracesByIds(
            implode(';', $shipmentIds),
            [],
            ['User-Agent' => $this->getUserAgentHeader()]
        );

        $result = [];

        foreach ($response?->data->tracktraces ?? [] as $tracktrace) {
            if (isset($tracktrace->shipmentId)) {
                $result[$tracktrace->shipmentId] = new TrackTrace($tracktrace);
            }
        }

        return $result;
    }
}
