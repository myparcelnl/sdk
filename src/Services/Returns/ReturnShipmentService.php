<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Returns;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Client;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostReturnShipmentsRequest;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostReturnShipmentsRequestData;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostReturnShipmentsRequestDataReturnShipmentsItem;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostUnrelatedReturnShipmentsRequest;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostUnrelatedReturnShipmentsRequestData;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostUnrelatedReturnShipmentsRequestDataReturnShipmentsItem;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesPostShipmentsV12;
use MyParcelNL\Sdk\Client\Jane\CoreApiModels;
use MyParcelNL\Sdk\Concerns\HasUserAgent;
use MyParcelNL\Sdk\Services\CoreApi\ShipmentApiFactory;

/**
 * Creates return shipments through the Core API client, for existing shipments or without one.
 *
 * Each return shipment in `$returnShipments` is an array with the API field names, such as `parent`
 * and `email`. The generated client checks the request before it sends it, and picks the content
 * type from the request body. A null value throws a TypeError: leave the field out. A key that the
 * API does not know goes to the API.
 */
final class ReturnShipmentService
{
    use HasUserAgent;

    private ?Client $api;

    private string $apiKey;

    private ?string $host;

    /**
     * Create the service with an API key, or with a client that keeps its own User-Agent.
     */
    public function __construct(string $apiKey, ?Client $api = null, ?string $host = null)
    {
        $this->apiKey = $apiKey;
        $this->api    = $api;
        $this->host   = $host;
    }

    /**
     * Create return shipments for existing shipments.
     *
     * @param array<int, array<string, mixed>> $returnShipments The rows, with the API field names such as `parent`, `carrier`, `email` and `name`.
     * @param bool                             $sendMail        Whether the API sends the return email to the consumer.
     *
     * @return array<int, string|null> The reference identifier of each new shipment, by shipment id.
     *
     * @throws InvalidArgumentException For an empty list, or a response without shipments.
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Normalizer\ValidationException For a row that does not match the spec. No request goes out.
     */
    public function createRelated(array $returnShipments, bool $sendMail = false): array
    {
        if (empty($returnShipments)) {
            throw new InvalidArgumentException('At least one related return shipment is required');
        }

        $data                  = new ShipmentPostReturnShipmentsRequestData();
        $data->returnShipments = CoreApiModels::toModels($returnShipments, ShipmentPostReturnShipmentsRequestDataReturnShipmentsItem::class);

        $request       = new ShipmentPostReturnShipmentsRequest();
        $request->data = CoreApiModels::withoutNullUntypedProperties($data);

        // The spec types `send_return_mail` as an integer, 0 or 1.
        return $this->mapCreatedShipments($this->api()->postShipments(
            requestBody: $request,
            queryParameters: ['send_return_mail' => $sendMail ? 1 : 0]
        ));
    }

    /**
     * Create return shipments without an existing shipment.
     *
     * @param array<int, array<string, mixed>> $returnShipments The rows, with the API field names such as `carrier`, `email` and `name`.
     *
     * @return array<int, string|null> The reference identifier of each new shipment, by shipment id.
     *
     * @throws InvalidArgumentException For an empty list, or a response without shipments.
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Normalizer\ValidationException For a row that does not match the spec. No request goes out.
     */
    public function createUnrelated(array $returnShipments): array
    {
        if (empty($returnShipments)) {
            throw new InvalidArgumentException('At least one unrelated return shipment is required');
        }

        $data                  = new ShipmentPostUnrelatedReturnShipmentsRequestData();
        $data->returnShipments = CoreApiModels::toModels($returnShipments, ShipmentPostUnrelatedReturnShipmentsRequestDataReturnShipmentsItem::class);

        $request       = new ShipmentPostUnrelatedReturnShipmentsRequest();
        $request->data = CoreApiModels::withoutNullUntypedProperties($data);

        return $this->mapCreatedShipments($this->api()->postShipments(requestBody: $request));
    }

    /**
     * @return array<int, string|null>
     */
    private function mapCreatedShipments(mixed $response): array
    {
        if (! $response instanceof ShipmentResponsesPostShipmentsV12) {
            throw new InvalidArgumentException('Unexpected response type returned while parsing return shipment response.');
        }

        // The generated normalizer turns `"data": null` into an empty data model, and the spec
        // requires `shipments`.
        if (! isset($response->data->shipments)) {
            throw new InvalidArgumentException('The return shipment response has no shipments.');
        }

        $mapping = [];

        foreach ($response->data->shipments as $shipment) {
            if (! isset($shipment->id)) {
                continue;
            }

            $referenceIdentifier = $shipment->referenceIdentifier ?? null;

            $mapping[$shipment->id] = null === $referenceIdentifier ? null : (string) $referenceIdentifier;
        }

        return $mapping;
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
