<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\TrackTrace;

use InvalidArgumentException;
use MyParcelNL\Sdk\Concerns\HasUserAgent;
use MyParcelNL\Sdk\Model\MyParcelRequest;

final class ShipmentTrackTraceService
{
    use HasUserAgent;

    private string $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @param int[] $shipmentIds
     * @return array<int, array<string, mixed>>
     */
    public function fetchTrackTraceData(array $shipmentIds): array
    {
        if (empty($shipmentIds)) {
            throw new InvalidArgumentException('At least one shipment ID is required');
        }

        $uri = 'tracktraces/' . implode(';', $shipmentIds);

        $request = (new MyParcelRequest())
            ->setUserAgents($this->getUserAgent())
            ->setRequestParameters($this->apiKey)
            ->sendRequest('GET', $uri);

        $trackTraces = $request->getResult('data.tracktraces') ?? [];

        if (! is_array($trackTraces)) {
            return [];
        }

        $result = [];

        foreach ($trackTraces as $trackTrace) {
            if (! is_array($trackTrace) || ! isset($trackTrace['shipment_id'])) {
                continue;
            }

            $result[(int) $trackTrace['shipment_id']] = $trackTrace;
        }

        return $result;
    }
}
