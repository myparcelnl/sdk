<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Shipment;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Client;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItem as ShipmentItem;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesShipmentIds;
use MyParcelNL\Sdk\Client\Jane\PostShipmentsDirectPrint;
use MyParcelNL\Sdk\Concerns\HasUserAgent;
use MyParcelNL\Sdk\Services\CoreApi\ShipmentApiFactory;
use MyParcelNL\Sdk\Services\Shipment\Concerns\PreparesShipments;

/**
 * Creates shipments and prints their labels on a printer group, through the Core API client.
 */
final class ShipmentPrintService
{
    use HasUserAgent;
    use PreparesShipments;

    private Client $api;

    public function __construct(
        string $apiKey,
        ?Client $api = null,
        ?string $host = null
    ) {
        $this->api = $api ?? ShipmentApiFactory::client($apiKey, $host);
    }

    /**
     * Create shipments and send their labels to the printer group.
     *
     * A shipment without a reference identifier gets one that starts with `sdk_`. The generated
     * client checks the shipments against the spec before it sends them.
     *
     * @param ShipmentItem[] $shipments The shipments. CoreApiModels::toModel() fills one from an array.
     *
     * @return array<int, string|null> Shipment id => reference identifier.
     *
     * @throws InvalidArgumentException For an empty list, or an item that is not a ShipmentItem.
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Normalizer\ValidationException For a shipment that does not match the spec. No request goes out.
     */
    public function print(array $shipments, string $printerGroupId): array
    {
        $request = $this->createRequest($shipments, 'print');

        $response = $this->api->executeEndpoint(new PostShipmentsDirectPrint($request, $printerGroupId, $this->headerParameters()));

        if (! $response instanceof ShipmentResponsesShipmentIds || ! isset($response->data)) {
            throw new InvalidArgumentException('Unexpected response type returned while parsing direct print response.');
        }

        return $this->mapCreatedIds($request->data->shipments, $response->data->ids ?? []);
    }
}
