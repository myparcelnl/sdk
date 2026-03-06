<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Returns;

use GuzzleHttp\Client as GuzzleClient;
use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Api\ShipmentApi;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentPostReturnShipmentsRequest;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentPostReturnShipmentsRequestData;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentPostReturnShipmentsRequestDataReturnShipmentsInner;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentPostUnrelatedReturnShipmentsRequest;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentPostUnrelatedReturnShipmentsRequestData;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentPostUnrelatedReturnShipmentsRequestDataReturnShipmentsInner;
use MyParcelNL\Sdk\Concerns\HasUserAgent;
use MyParcelNL\Sdk\Services\CoreApi\ShipmentApiFactory;
use Psr\Http\Client\ClientInterface as PsrClientInterface;
use Psr\Http\Message\RequestInterface;

/**
 * Return shipment creation service.
 *
 * Uses generated request models and request builder as source of truth.
 *
 * @todo switch to direct generated methods once send_return_mail and unrelated return contracts
 *       are fully represented by the generated operation signatures.
 */
final class ReturnShipmentService
{
    use HasUserAgent;

    private ShipmentApi $api;

    private PsrClientInterface $httpClient;

    public function __construct(
        string $apiKey,
        ?ShipmentApi $api = null,
        ?PsrClientInterface $httpClient = null,
        ?string $host = null
    ) {
        $this->api = $api ?? ShipmentApiFactory::make($apiKey, $host);
        $this->httpClient = $httpClient ?? new GuzzleClient(['timeout' => 10]);
    }

    /**
     * Create return shipments linked to existing parent shipment ids.
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
            ShipmentApi::contentTypes['postShipments'][2]
        );

        $request = $this->withQueryParameter($request, 'send_return_mail', $sendMail ? '1' : '0');

        return $this->sendAndParseIdsResponse($request);
    }

    /**
     * Create unrelated return shipments.
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
            ShipmentApi::contentTypes['postShipments'][3]
        );

        return $this->sendAndParseIdsResponse($request);
    }

    /**
     * @return array<int, string|null>
     */
    private function sendAndParseIdsResponse(RequestInterface $request): array
    {
        $response = $this->httpClient->sendRequest($request);

        $body = (string) $response->getBody();
        $decoded = json_decode($body, true);
        $ids = is_array($decoded) && isset($decoded['data']['ids']) && is_array($decoded['data']['ids'])
            ? $decoded['data']['ids']
            : [];

        $mapping = [];
        foreach ($ids as $item) {
            if (! is_array($item) || ! isset($item['id'])) {
                continue;
            }

            $mapping[(int) $item['id']] = isset($item['reference_identifier'])
                ? (string) $item['reference_identifier']
                : null;
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
