<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\TrackTrace;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Api\ShipmentApi;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentDefsTrackTrace;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentResponsesTracktraces;
use MyParcelNL\Sdk\Concerns\HasUserAgent;
use MyParcelNL\Sdk\Services\CoreApi\ShipmentApiFactory;

final class ShipmentTrackTraceService
{
    use HasUserAgent;

    private ShipmentApi $api;

    public function __construct(string $apiKey, ?ShipmentApi $api = null, ?string $baseUri = null)
    {
        $this->api = $api ?? ShipmentApiFactory::make($apiKey, $baseUri);
    }

    /**
     * @param int[] $shipmentIds
     * @return array<int, ShipmentDefsTrackTrace>
     */
    public function fetchTrackTraceData(array $shipmentIds): array
    {
        if (empty($shipmentIds)) {
            throw new InvalidArgumentException('At least one shipment ID is required');
        }

        $response = $this->api->getTrackTracesByIds(
            implode(';', $shipmentIds),
            $this->getUserAgentHeader()
        );

        return $this->mapTrackTracesByShipmentId($response);
    }

    /**
     * @return array<int, ShipmentDefsTrackTrace>
     */
    private function mapTrackTracesByShipmentId(ShipmentResponsesTracktraces $response): array
    {
        $data = $response->getData();

        if (null === $data || ! is_array($data->getTracktraces())) {
            return [];
        }

        $result = [];

        foreach ($data->getTracktraces() as $trackTrace) {
            if (! $trackTrace instanceof ShipmentDefsTrackTrace) {
                continue;
            }

            $result[(int) $trackTrace->getShipmentId()] = $trackTrace;
        }

        return $result;
    }
}
