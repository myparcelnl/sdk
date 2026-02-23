<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Shipment;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Api\ShipmentApi;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\InlineObject;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentPostShipmentsRequestV11;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentPostShipmentsRequestV11Data;
use MyParcelNL\Sdk\Concerns\HasUserAgent;
use MyParcelNL\Sdk\Collection\ShipmentCollection;
use MyParcelNL\Sdk\Model\Shipment\Shipment;
use MyParcelNL\Sdk\Services\CoreApi\ShipmentApiFactory;

final class ShipmentCreateService
{
    use HasUserAgent;

    private const MAX_SHIPMENTS_PER_CALL = 100;

    private ShipmentApi $api;

    public function __construct(
        string $apiKey,
        ?ShipmentApi $api = null,
        ?string $baseUri = null
    ) {
        $this->api = $api ?? ShipmentApiFactory::make($apiKey, $baseUri);
    }

    /**
     * Create shipments through the generated ShipmentApi client.
     *
     * @param ShipmentCollection $collection Collection of shipments to create.
     * @param mixed              $format     Label format (e.g. "A4", "A6") or generated format reference.
     * @param mixed              $positions  Label position(s) for A4 (e.g. "1;2;3;4") or generated position reference.
     *
     * @return array<int, string|null> Mapping shipment id => reference identifier.
     */
    public function create(ShipmentCollection $collection, $format = null, $positions = null): array
    {
        $shipments = $collection->getShipments(false);

        $this->validateBeforeCreate($shipments);
        $this->ensureReferenceIds($shipments);

        $request = $this->buildCreateRequest($shipments);
        $userAgentHeader = $this->getUserAgentHeader();

        $response = $this->api->postShipments(
            $userAgentHeader,
            $request,
            $format,
            $positions,
            null,
            null,
            ShipmentApi::contentTypes['postShipments'][0]
        );

        return $this->parseCreateResponse($shipments, $response);
    }

    /**
     * @param Shipment[] $shipments
     */
    private function validateBeforeCreate(array $shipments): void
    {
        if (empty($shipments)) {
            throw new InvalidArgumentException('At least one shipment must be added before calling create().');
        }

        // Mirrors generated model validation in ShipmentPostShipmentsRequestV11Data::setShipments().
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
     * @param Shipment[] $shipments
     *
     * @return array<int, string|null>
     */
    private function parseCreateResponse(array $shipments, $response): array
    {
        if (! $response instanceof InlineObject) {
            throw new InvalidArgumentException('Unexpected response type returned by ShipmentApi::postShipments()');
        }

        $responseData = $response->getData();
        $requestReferences = array_values(array_map(
            static fn (Shipment $shipment): ?string => $shipment->getReferenceIdentifier(),
            $shipments
        ));

        $mapping = [];
        foreach ($responseData->getIds() as $index => $idObject) {
            $referenceIdentifier = $this->normalizeReferenceIdentifier($idObject->getReferenceIdentifier());

            // TEMP WORKAROUND (remove after CoreAPI spec/codegen fix):
            // Generated client may deserialize reference_identifier as wrapper object.
            if (null === $referenceIdentifier && isset($requestReferences[$index])) {
                $referenceIdentifier = $requestReferences[$index];
            }

            $mapping[(int) $idObject->getId()] = $referenceIdentifier;
        }

        return $mapping;
    }

    /**
     * TEMP WORKAROUND (remove after CoreAPI spec/codegen fix):
     * Normalize generated reference_identifier values to scalar strings.
     */
    private function normalizeReferenceIdentifier($referenceIdentifier): ?string
    {
        if (null === $referenceIdentifier) {
            return null;
        }

        if (is_string($referenceIdentifier) || is_int($referenceIdentifier) || is_float($referenceIdentifier)) {
            return (string) $referenceIdentifier;
        }

        if (! is_object($referenceIdentifier)) {
            return null;
        }

        if (method_exists($referenceIdentifier, '__toString')) {
            try {
                $asString = (string) $referenceIdentifier;
                if ('' !== $asString && '{}' !== $asString) {
                    return $asString;
                }
            } catch (\Throwable $e) {
                // ignore and continue
            }
        }

        $decoded = json_decode(json_encode($referenceIdentifier), true);

        if (is_string($decoded) || is_int($decoded) || is_float($decoded)) {
            return (string) $decoded;
        }

        return null;
    }
}
