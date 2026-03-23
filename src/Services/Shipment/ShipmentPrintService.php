<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Shipment;

use GuzzleHttp\Client as GuzzleClient;
use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Api\ShipmentApi;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentPostShipmentsRequestV11;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentPostShipmentsRequestV11Data;
use MyParcelNL\Sdk\Concerns\HasUserAgent;
use MyParcelNL\Sdk\Collection\ShipmentCollection;
use MyParcelNL\Sdk\Model\Shipment\Shipment;
use MyParcelNL\Sdk\Services\CoreApi\ShipmentApiFactory;
use MyParcelNL\Sdk\Services\Shipment\Concerns\EnsuresShipmentReferenceIds;
use Psr\Http\Client\ClientInterface as PsrClientInterface;

/**
 * Direct print flow for shipments.
 *
 * Uses hybrid approach (request builder + manual send) so we can override the Accept header
 * with the direct-print printer-group-id header.
 *
 * @todo revert to direct generated API method once the direct-print Accept header
 *       is supported as a first-class parameter in the OpenAPI spec/client.
 */
final class ShipmentPrintService
{
    use HasUserAgent;
    use EnsuresShipmentReferenceIds;

    private const DIRECT_PRINT_ACCEPT_TEMPLATE = 'application/vnd.shipment_label+json+print;printer-group-id=%s';

    private ShipmentApi $api;

    private PsrClientInterface $httpClient;

    public function __construct(
        string $apiKey,
        ?ShipmentApi $api = null,
        ?PsrClientInterface $httpClient = null,
        ?string $host = null
    ) {
        $this->api = $api ?? ShipmentApiFactory::make($apiKey, $host);
        $this->httpClient = $httpClient ?? new GuzzleClient(['timeout' => ShipmentApiFactory::DEFAULT_HTTP_TIMEOUT]);
    }

    /**
     * Create shipments and send labels directly to the given printer group.
     *
     * @return array<int, string|null> Mapping shipment id => reference identifier.
     */
    public function print(ShipmentCollection $collection, string $printerGroupId): array
    {
        $shipments = $collection->values()->all();

        $this->validateBeforePrint($shipments);
        $this->ensureReferenceIds($shipments);

        $requestModel = $this->buildCreateRequest($shipments);

        $request = $this->api->postShipmentsRequest(
            $this->getUserAgentHeader(),
            $requestModel,
            null,
            null,
            null,
            null,
            ShipmentApi::contentTypes['postShipments'][0]
        )->withHeader('Accept', sprintf(self::DIRECT_PRINT_ACCEPT_TEMPLATE, $printerGroupId));

        $response = $this->httpClient->sendRequest($request);
        $decoded = json_decode((string) $response->getBody(), true);

        return $this->parseIdsResponse($decoded);
    }

    /**
     * @param Shipment[] $shipments
     */
    private function validateBeforePrint(array $shipments): void
    {
        if (empty($shipments)) {
            throw new InvalidArgumentException('At least one shipment must be added before calling print().');
        }
    }

    /**
     * @param Shipment[] $shipments
     */
    private function buildCreateRequest(array $shipments): ShipmentPostShipmentsRequestV11
    {
        $data = new ShipmentPostShipmentsRequestV11Data();
        $data->setShipments($shipments);
        $data->setUserAgent($this->getUserAgentHeader());

        $request = new ShipmentPostShipmentsRequestV11();
        $request->setData($data);

        return $request;
    }

    /**
     * @return array<int, string|null>
     */
    private function parseIdsResponse(?array $decoded): array
    {
        if (! is_array($decoded) || ! isset($decoded['data']['ids']) || ! is_array($decoded['data']['ids'])) {
            return [];
        }

        $mapping = [];

        foreach ($decoded['data']['ids'] as $item) {
            if (! is_array($item) || ! isset($item['id'])) {
                continue;
            }

            $mapping[(int) $item['id']] = isset($item['reference_identifier'])
                ? (string) $item['reference_identifier']
                : null;
        }

        return $mapping;
    }
}
