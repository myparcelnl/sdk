<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Shipment;

use InvalidArgumentException;
use MyParcelNL\Sdk\Concerns\HasUserAgent;
use MyParcelNL\Sdk\Collection\ShipmentCollection;
use MyParcelNL\Sdk\Model\MyParcelRequest;
use MyParcelNL\Sdk\Model\Shipment\Shipment;

final class ShipmentPrintService
{
    use HasUserAgent;

    private const MAX_SHIPMENTS_PER_CALL = 100;

    private string $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Create shipments and send labels directly to the given printer group.
     *
     * @return array<int, string|null> Mapping shipment id => reference identifier.
     */
    public function print(ShipmentCollection $collection, string $printerGroupId): array
    {
        $shipments = $collection->getShipments(false);

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

        if (count($shipments) > self::MAX_SHIPMENTS_PER_CALL) {
            throw new InvalidArgumentException(
                sprintf('Maximum %d shipments per call', self::MAX_SHIPMENTS_PER_CALL)
            );
        }
    }

    /**
     * @param Shipment[] $shipments
     */
    private function ensureReferenceIds(array $shipments): void
    {
        foreach ($shipments as $shipment) {
            if (! $shipment->getReferenceIdentifier()) {
                $shipment->setReferenceIdentifier('sdk_' . uniqid('', true));
            }
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
