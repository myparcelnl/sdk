<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Shipment;

use BackedEnum;
use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Client;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItem as ShipmentItem;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesPostShipmentsV12;
use MyParcelNL\Sdk\Concerns\HasUserAgent;
use MyParcelNL\Sdk\Services\CoreApi\ShipmentApiFactory;
use MyParcelNL\Sdk\Services\Shipment\Concerns\PreparesShipments;

/**
 * Creates shipments through the Core API client.
 */
final class ShipmentCreateService
{
    use HasUserAgent;
    use PreparesShipments;

    private ?Client $api;

    private string $apiKey;

    private ?string $host;

    /**
     * Create the service with an API key, or with a client that keeps its own User-Agent.
     */
    public function __construct(
        string $apiKey,
        ?Client $api = null,
        ?string $host = null
    ) {
        $this->apiKey = $apiKey;
        $this->api    = $api;
        $this->host   = $host;
    }

    /**
     * Create shipments.
     *
     * A shipment without a reference identifier gets one that starts with `sdk_`. The generated
     * client checks the shipments against the spec before it sends them.
     *
     * @param ShipmentItem[]         $shipments The shipments. CoreApiModels::toModel() fills one from an array.
     * @param string|BackedEnum|null $format    The label format, such as `A4` or `A6`.
     * @param int|string|null        $positions The label positions on an A4 sheet, such as `1;2;3;4`.
     *
     * @return array<int, string|null> Shipment id => reference identifier.
     *
     * @throws InvalidArgumentException For an empty list, or an item that is not a ShipmentItem.
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Normalizer\ValidationException For a shipment that does not match the spec. No request goes out.
     */
    public function create(array $shipments, string|BackedEnum|null $format = null, int|string|null $positions = null): array
    {
        $request = $this->createRequest($shipments, 'create');

        $response = $this->api()->postShipments(
            requestBody: $request,
            queryParameters: array_filter(
                ['format' => $format instanceof BackedEnum ? $format->value : $format, 'positions' => $positions],
                static fn (mixed $value): bool => null !== $value
            )
        );

        if (! $response instanceof ShipmentResponsesPostShipmentsV12) {
            throw new InvalidArgumentException('Unexpected response type returned by postShipments()');
        }

        // The generated normalizer turns `"data": null` into an empty data model, so check the shipments.
        if (! isset($response->data->shipments)) {
            throw new InvalidArgumentException('postShipments() returned a response without data.');
        }

        return $this->mapCreatedIds($request->data->shipments, $response->data->shipments);
    }

    /**
     * The first request creates the client with the User-Agent of the service, so a proposition that
     * is set after the constructor reaches the API.
     */
    private function api(): Client
    {
        return $this->api ??= ShipmentApiFactory::client($this->apiKey, $this->host, $this->getUserAgentHeader());
    }
}
