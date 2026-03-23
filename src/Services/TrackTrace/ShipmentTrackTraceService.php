<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\TrackTrace;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Api\ShipmentApi;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentDefsTrackTrace;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentResponsesTracktraces;
use MyParcelNL\Sdk\Concerns\HasUserAgent;
use MyParcelNL\Sdk\Services\CoreApi\ShipmentApiFactory;

/**
 * Track & trace service for shipments using generated Core API endpoints.
 */
final class ShipmentTrackTraceService
{
    use HasUserAgent;

    private ShipmentApi $api;

    public function __construct(
        string $apiKey,
        ?ShipmentApi $api = null,
        ?string $baseUri = null
    ) {
        $this->api = $api ?? ShipmentApiFactory::make($apiKey, $baseUri);
    }

    /**
     * Retrieve track & trace information for the given shipment IDs.
     *
     * @param int[] $shipmentIds
     * @return array<int, ShipmentDefsTrackTrace> Keyed by shipment ID.
     */
    public function fetchTrackTraceData(array $shipmentIds): array
    {
        if (empty($shipmentIds)) {
            throw new InvalidArgumentException('At least one shipment ID is required');
        }

        /** @var ShipmentResponsesTracktraces $response */
        $response = $this->api->getTrackTracesByIds(
            implode(';', $shipmentIds),
            $this->getUserAgentHeader()
        );

        $data = $response->getData();

        if (null === $data) {
            return [];
        }

        $tracktraces = $data->getTracktraces() ?? [];
        $result = [];

        foreach ($tracktraces as $tracktrace) {
            $shipmentId = $tracktrace->getShipmentId();

            if (null !== $shipmentId) {
                $result[(int) $shipmentId] = $tracktrace;
            }
        }

        return $result;
    }
}
