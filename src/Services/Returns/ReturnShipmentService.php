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
use MyParcelNL\Sdk\Client\Jane\PostReturnShipments;
use MyParcelNL\Sdk\Concerns\HasUserAgent;
use MyParcelNL\Sdk\Services\CoreApi\ShipmentApiFactory;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Creates return shipments, for existing shipments or without one.
 *
 * A row has the API field names, such as `parent` and `email`. The generated client checks the
 * request before it sends it, and picks the content type from the request body.
 */
final class ReturnShipmentService
{
    use HasUserAgent;

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
        $this->api = $api ?? ShipmentApiFactory::client($apiKey, $host, null, [], $httpClient);
    }

    /**
     * Create return shipments linked to existing parent shipment ids.
     *
     * @param array<int, array<string, mixed>> $returnShipments
     * @return array<int, string|null> Mapping shipment id => reference identifier.
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

        return $this->mapCreatedShipments($this->api->executeEndpoint(new PostReturnShipments(
            $request,
            $sendMail,
            ['User-Agent' => $this->getUserAgentHeader()]
        )));
    }

    /**
     * Create unrelated return shipments.
     *
     * @param array<int, array<string, mixed>> $returnShipments
     * @return array<int, string|null> Mapping shipment id => reference identifier.
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

        return $this->mapCreatedShipments(
            $this->api->postShipments($request, [], ['User-Agent' => $this->getUserAgentHeader()])
        );
    }

    /**
     * @return array<int, string|null>
     */
    private function mapCreatedShipments(mixed $response): array
    {
        if (! $response instanceof ShipmentResponsesPostShipmentsV12) {
            throw new InvalidArgumentException('Unexpected response type returned while parsing return shipment response.');
        }

        $mapping = [];

        foreach ($response->data->shipments ?? [] as $shipment) {
            if (! isset($shipment->id)) {
                continue;
            }

            $mapping[$shipment->id] = null === $shipment->referenceIdentifier ? null : (string) $shipment->referenceIdentifier;
        }

        return $mapping;
    }
}
