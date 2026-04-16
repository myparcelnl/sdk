<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Returns;

use GuzzleHttp\Client as GuzzleClient;
use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Api\ShipmentApi;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentDefsShipment;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentPostReturnShipmentsRequest;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentPostReturnShipmentsRequestData;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentPostReturnShipmentsRequestDataReturnShipmentsInner;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentPostUnrelatedReturnShipmentsRequest;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentPostUnrelatedReturnShipmentsRequestData;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentPostUnrelatedReturnShipmentsRequestDataReturnShipmentsInner;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentResponsesPostShipmentsV12;
use MyParcelNL\Sdk\Client\Generated\CoreApi\ObjectSerializer;
use MyParcelNL\Sdk\Concerns\HasUserAgent;
use MyParcelNL\Sdk\Services\CoreApi\ShipmentApiFactory;
use MyParcelNL\Sdk\Services\CoreApi\Concerns\ResolvesPostShipmentsContentType;
use Psr\Http\Client\ClientInterface as PsrClientInterface;
use Psr\Http\Message\RequestInterface;

/**
 * Return shipment creation service.
 *
 * Uses generated request models and response models as source of truth.
 *
 * This service remains hybrid because the generated operation methods do not yet expose
 * the related return variants we need here, such as send_return_mail and unrelated returns.
 * Until those parameters are modeled directly on the generated operation signatures, we build
 * the request with the generated request builder and deserialize the response with the generated
 * response model ourselves.
 *
 * @todo switch to direct generated methods once send_return_mail and unrelated return contracts
 *       are fully represented by the generated operation signatures.
 */
final class ReturnShipmentService
{
    use HasUserAgent;
    use ResolvesPostShipmentsContentType;

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
     * Create return shipments linked to existing parent shipment ids.
     *
     * Hybrid note: we still use postShipmentsRequest() here so we can append the
     * send_return_mail query parameter without hardcoding the rest of the request contract.
     *
     * @param array<int, array<string, mixed>> $returnShipments
     * @return array<int, string|null>
     */
    public function createRelated(array $returnShipments, bool $sendMail = false): array
    {
        if (empty($returnShipments)) {
            throw new InvalidArgumentException('At least one related return shipment is required');
        }

        $items = array_map(function (array $row): ShipmentPostReturnShipmentsRequestDataReturnShipmentsInner {
            return new ShipmentPostReturnShipmentsRequestDataReturnShipmentsInner($row);
        }, $returnShipments);

        $data = new ShipmentPostReturnShipmentsRequestData();
        $data->setReturnShipments($items);

        $requestModel = new ShipmentPostReturnShipmentsRequest();
        $requestModel->setData($data);

        $request = $this->api->postShipmentsRequest(
            $this->getUserAgentHeader(),
            $requestModel,
            null,
            null,
            null,
            null,
            $this->resolvePostShipmentsContentType('application/vnd.return_shipment+json')
        );

        $request = $this->withQueryParameter($request, 'send_return_mail', $sendMail ? '1' : '0');

        return $this->sendAndParseIdsResponse($request);
    }

    /**
     * Create unrelated return shipments.
     *
     * Hybrid note: unrelated returns currently reuse the generated request builder because
     * this variant is not yet exposed as a dedicated generated operation method.
     *
     * @param array<int, array<string, mixed>> $returnShipments
     * @return array<int, string|null>
     */
    public function createUnrelated(array $returnShipments): array
    {
        if (empty($returnShipments)) {
            throw new InvalidArgumentException('At least one unrelated return shipment is required');
        }

        $items = array_map(function (array $row): ShipmentPostUnrelatedReturnShipmentsRequestDataReturnShipmentsInner {
            return new ShipmentPostUnrelatedReturnShipmentsRequestDataReturnShipmentsInner($row);
        }, $returnShipments);

        $data = new ShipmentPostUnrelatedReturnShipmentsRequestData();
        $data->setReturnShipments($items);

        $requestModel = new ShipmentPostUnrelatedReturnShipmentsRequest();
        $requestModel->setData($data);

        $request = $this->api->postShipmentsRequest(
            $this->getUserAgentHeader(),
            $requestModel,
            null,
            null,
            null,
            null,
            $this->resolvePostShipmentsContentType('application/vnd.unrelated_return_shipment+json')
        );

        return $this->sendAndParseIdsResponse($request);
    }

    /**
     * Send the prepared hybrid request and deserialize it with the generated success model.
     *
     * We intentionally do not swallow parse or contract errors here; hybrid transport should
     * still fail loudly when the generated client contract no longer matches the live response.
     *
     * @return array<int, string|null>
     */
    private function sendAndParseIdsResponse(RequestInterface $request): array
    {
        $response = $this->httpClient->sendRequest($request);
        $decoded = json_decode((string) $response->getBody(), false, 512, JSON_THROW_ON_ERROR);

        $responseModel = ObjectSerializer::deserialize(
            $decoded,
            ShipmentResponsesPostShipmentsV12::class,
            []
        );

        if (! $responseModel instanceof ShipmentResponsesPostShipmentsV12 || null === $responseModel->getData()) {
            throw new InvalidArgumentException('Unexpected response type returned while parsing return shipment response.');
        }

        return $this->mapCreatedShipments($responseModel->getData()->getShipments() ?? []);
    }

    /**
     * @param ShipmentDefsShipment[] $shipments
     *
     * @return array<int, string|null>
     */
    private function mapCreatedShipments(array $shipments): array
    {
        $mapping = [];

        foreach ($shipments as $shipment) {
            $shipmentId = $shipment->getId();
            if (null === $shipmentId) {
                continue;
            }

            $mapping[(int) $shipmentId] = $shipment->getReferenceIdentifier();
        }

        return $mapping;
    }

    private function withQueryParameter(RequestInterface $request, string $key, string $value): RequestInterface
    {
        $uri = $request->getUri();
        $query = [];

        parse_str($uri->getQuery(), $query);
        $query[$key] = $value;

        return $request->withUri($uri->withQuery(http_build_query($query)));
    }

}
