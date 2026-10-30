<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Shipment;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Client;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11Data;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesShipmentIds;
use MyParcelNL\Sdk\Client\Jane\PostShipmentsDirectPrint;
use MyParcelNL\Sdk\Collection\ShipmentCollection;
use MyParcelNL\Sdk\Concerns\HasUserAgent;
use MyParcelNL\Sdk\Model\Shipment\Shipment;
use MyParcelNL\Sdk\Services\CoreApi\ShipmentApiFactory;
use MyParcelNL\Sdk\Services\Shipment\Concerns\EnsuresShipmentReferenceIds;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Direct print flow for shipments.
 */
final class ShipmentPrintService
{
    use HasUserAgent;
    use EnsuresShipmentReferenceIds;

    private Client $api;

    /**
     * The third argument has the place of the PSR-18 client of v11, so the host stays the fourth argument.
     *
     * @param HttpClientInterface|null $httpClient Sends the requests when the service builds the client.
     */
    public function __construct(
        string $apiKey,
        ?Client $api = null,
        ?HttpClientInterface $httpClient = null,
        ?string $host = null
    ) {
        $this->api = $api ?? ShipmentApiFactory::make($apiKey, $host, null, [], $httpClient);
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

        $response = $this->api->executeEndpoint(new PostShipmentsDirectPrint(
            $this->buildCreateRequest($shipments),
            $printerGroupId,
            ['User-Agent' => $this->getUserAgentHeader()]
        ));

        if (! $response instanceof ShipmentResponsesShipmentIds || ! isset($response->data)) {
            throw new InvalidArgumentException('Unexpected response type returned while parsing direct print response.');
        }

        $mapping = [];

        foreach ($response->data->ids ?? [] as $item) {
            if (isset($item->id)) {
                $mapping[$item->id] = isset($item->referenceIdentifier) ? (string) $item->referenceIdentifier : null;
            }
        }

        return $mapping;
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
        $data            = new ShipmentPostShipmentsRequestV11Data();
        $data->shipments = array_map(static fn (Shipment $shipment) => $shipment->toApiModel(), $shipments);
        $data->userAgent = $this->getUserAgentHeader();

        $request       = new ShipmentPostShipmentsRequestV11();
        $request->data = $data;

        return $request;
    }
}
