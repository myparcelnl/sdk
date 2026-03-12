<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\TrackTrace;

use GuzzleHttp\Client as GuzzleClient;
use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Api\ShipmentApi;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentDefsTrackTrace;
use MyParcelNL\Sdk\Concerns\HasUserAgent;
use MyParcelNL\Sdk\Services\CoreApi\ModelHydrator;
use MyParcelNL\Sdk\Services\CoreApi\ShipmentApiFactory;
use Psr\Http\Client\ClientInterface as PsrClientInterface;

/**
 * Track & trace service for shipments using generated Core API endpoints.
 *
 * Uses hybrid approach (request builder + manual send + constructor-based deserialization)
 * to work around a broken preg_match pattern in the generated ShipmentDefsShipmentRecipient::setStreet().
 * The tracktrace response includes nested recipient/sender objects that trigger the same crash.
 *
 * @todo revert to direct generated API method (getTrackTracesByIds) once the
 *       upstream spec/codegen fix for the street pattern is available and the client is regenerated.
 */
final class ShipmentTrackTraceService
{
    use HasUserAgent;

    private ShipmentApi $api;

    private PsrClientInterface $httpClient;

    public function __construct(
        string $apiKey,
        ?ShipmentApi $api = null,
        ?PsrClientInterface $httpClient = null,
        ?string $baseUri = null
    ) {
        $this->api = $api ?? ShipmentApiFactory::make($apiKey, $baseUri);
        $this->httpClient = $httpClient ?? new GuzzleClient(['timeout' => ShipmentApiFactory::DEFAULT_HTTP_TIMEOUT]);
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

        $request = $this->api->getTrackTracesByIdsRequest(
            implode(';', $shipmentIds),
            $this->getUserAgentHeader()
        );

        $response = $this->httpClient->sendRequest($request);
        $decoded = json_decode((string) $response->getBody(), true);

        if (! is_array($decoded) || ! isset($decoded['data']['tracktraces']) || ! is_array($decoded['data']['tracktraces'])) {
            return [];
        }

        $result = [];

        foreach ($decoded['data']['tracktraces'] as $trackTraceData) {
            if (! is_array($trackTraceData) || ! isset($trackTraceData['shipment_id'])) {
                continue;
            }

            $result[(int) $trackTraceData['shipment_id']] = $this->hydrateTrackTrace($trackTraceData);
        }

        return $result;
    }

    /**
     * Recursively hydrate a ShipmentDefsTrackTrace from raw array data.
     *
     * Uses ModelHydrator to construct all nested typed models (recipient, sender,
     * status, history, location, etc.) via their constructors, bypassing setter validation.
     */
    private function hydrateTrackTrace(array $data): ShipmentDefsTrackTrace
    {
        /** @var ShipmentDefsTrackTrace */
        return ModelHydrator::hydrate(ShipmentDefsTrackTrace::class, $data);
    }
}
