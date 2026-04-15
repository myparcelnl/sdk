<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Shipment;

use GuzzleHttp\Client as GuzzleClient;
use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Api\ShipmentApi;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentPostShipmentsRequestV11;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentPostShipmentsRequestV11Data;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentResponsesShipmentIds;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentResponsesShipmentIdsDataIdsInner;
use MyParcelNL\Sdk\Client\Generated\CoreApi\ObjectSerializer;
use MyParcelNL\Sdk\Concerns\HasUserAgent;
use MyParcelNL\Sdk\Collection\ShipmentCollection;
use MyParcelNL\Sdk\Model\Shipment\Shipment;
use MyParcelNL\Sdk\Services\CoreApi\ShipmentApiFactory;
use MyParcelNL\Sdk\Services\Shipment\Concerns\EnsuresShipmentReferenceIds;
use Psr\Http\Client\ClientInterface as PsrClientInterface;
use Psr\Http\Message\RequestInterface;

/**
 * Direct print flow for shipments.
 *
 * Uses hybrid approach (request builder + manual send) so we can override the Accept header
 * with the direct-print printer-group-id header. The response is still deserialized through
 * the generated shipment ids model so the generated client remains the contract source of truth.
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
     * Hybrid note: the only manual part is the custom Accept header that carries printer-group-id.
     * Request and response contracts still come from the generated client.
     *
     * @return array<int, string|null> Mapping shipment id => reference identifier.
     */
    public function print(ShipmentCollection $collection, string $printerGroupId): array
    {
        $shipments = $collection->values()->all();

        $this->validateBeforePrint($shipments);
        $this->ensureReferenceIds($shipments);

        $requestModel = $this->buildCreateRequest($shipments);
        $request = $this->createDirectPrintRequest($requestModel, $printerGroupId);

        return $this->sendAndParseIdsResponse($request);
    }

    /**
     * Build the generated shipment create request and then override Accept for direct print.
     */
    private function createDirectPrintRequest(
        ShipmentPostShipmentsRequestV11 $requestModel,
        string $printerGroupId
    ): RequestInterface {
        return $this->api->postShipmentsRequest(
            $this->getUserAgentHeader(),
            $requestModel,
            null,
            null,
            null,
            null,
            $this->resolveShipmentCreateContentType()
        )->withHeader('Accept', sprintf(self::DIRECT_PRINT_ACCEPT_TEMPLATE, $printerGroupId));
    }

    /**
     * Resolve the generated shipment create content type by prefix instead of array index.
     */
    private function resolveShipmentCreateContentType(): string
    {
        foreach (ShipmentApi::contentTypes['postShipments'] as $contentType) {
            if (0 === strpos($contentType, 'application/vnd.shipment+json')) {
                return $contentType;
            }
        }

        throw new \RuntimeException('No shipment create content type configured in generated ShipmentApi client.');
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
     * Send the prepared hybrid request and deserialize it with the generated ids response model.
     *
     * We intentionally let parse and contract errors bubble so hybrid transport does not invent
     * its own fallback error semantics.
     *
     * @return array<int, string|null>
     */
    private function sendAndParseIdsResponse(RequestInterface $request): array
    {
        $response = $this->httpClient->sendRequest($request);
        $decoded = json_decode((string) $response->getBody(), false, 512, JSON_THROW_ON_ERROR);
        $responseModel = ObjectSerializer::deserialize(
            $decoded,
            ShipmentResponsesShipmentIds::class,
            []
        );

        if (! $responseModel instanceof ShipmentResponsesShipmentIds || null === $responseModel->getData()) {
            throw new InvalidArgumentException('Unexpected response type returned while parsing direct print response.');
        }

        return $this->mapCreatedShipmentIds($responseModel->getData()->getIds() ?? []);
    }

    /**
     * @param ShipmentResponsesShipmentIdsDataIdsInner[] $ids
     *
     * @return array<int, string|null>
     */
    private function mapCreatedShipmentIds(array $ids): array
    {
        $mapping = [];

        foreach ($ids as $item) {
            $shipmentId = $item->getId();
            if (null === $shipmentId) {
                continue;
            }

            $mapping[(int) $shipmentId] = $item->getReferenceIdentifier();
        }

        return $mapping;
    }
}
