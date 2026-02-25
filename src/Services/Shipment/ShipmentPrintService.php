<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Shipment;

use InvalidArgumentException;
use MyParcelNL\Sdk\Concerns\HasUserAgent;
use MyParcelNL\Sdk\Collection\ShipmentCollection;
use MyParcelNL\Sdk\Model\MyParcelRequest;
use MyParcelNL\Sdk\Model\Shipment\Shipment;
use MyParcelNL\Sdk\Services\Shipment\Concerns\EnsuresShipmentReferenceIds;

/**
 * Direct print flow for shipments.
 *
 * @todo migrate to generated ShipmentApi when direct-print request/headers are supported in OpenAPI spec/client.
 */
final class ShipmentPrintService
{
    use HasUserAgent;
    use EnsuresShipmentReferenceIds;

    private string $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Create shipments and send labels directly to the given printer group.
     *
     * Direct print requires a custom Accept header with printer-group-id.
     * The generated client cannot inject this header per call, so this flow
     * intentionally uses MyParcelRequest.
     *
     * @todo migrate to generated ShipmentApi call once direct-print contract is available in spec/client.
     *
     * @return array<int, string|null> Mapping shipment id => reference identifier.
     */
    public function print(ShipmentCollection $collection, string $printerGroupId): array
    {
        $shipments = $collection->values()->all();

        $this->validateBeforePrint($shipments);
        $this->ensureReferenceIds($shipments);

        $body = $this->buildRequestBody($shipments);

        $headers = MyParcelRequest::HEADER_CONTENT_TYPE_SHIPMENT;
        $headers['Accept'] = MyParcelRequest::getDirectPrintAcceptHeader($printerGroupId)['Accept'];

        $request = (new MyParcelRequest())
            ->setUserAgents($this->getUserAgent())
            ->setRequestParameters($this->apiKey, $body, $headers)
            ->sendRequest();

        return $this->parseResponse($request->getResult('data.ids'));
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
    private function buildRequestBody(array $shipments): string
    {
        $shipmentsData = array_map(static function (Shipment $shipment): array {
            $encoded = json_decode(json_encode($shipment), true);

            return is_array($encoded) ? $encoded : [];
        }, $shipments);

        $body = json_encode(['data' => [
            'shipments'  => $shipmentsData,
            'user_agent' => $this->getUserAgentHeader(),
        ]]);

        if (! is_string($body)) {
            throw new InvalidArgumentException('Unable to encode shipment payload for direct print request.');
        }

        return $body;
    }

    /**
     * @param mixed $responseData
     * @return array<int, string|null>
     */
    private function parseResponse($responseData): array
    {
        if (null === $responseData) {
            return [];
        }

        if (! is_array($responseData)) {
            throw new InvalidArgumentException('Unexpected response payload for direct print request.');
        }

        $mapping = [];
        foreach ($responseData as $item) {
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
