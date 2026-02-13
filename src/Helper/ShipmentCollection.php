<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Helper;

use BadMethodCallException;
use InvalidArgumentException;
use MyParcelNL\Sdk\Concerns\HasUserAgent;
use MyParcelNL\Sdk\CoreApi\Generated\Shipments\Api\ShipmentApi;
use MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\InlineObject;
use MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\ShipmentPostShipmentsRequestV11;
use MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\ShipmentPostShipmentsRequestV11Data;
use MyParcelNL\Sdk\Model\MyParcelRequest;
use MyParcelNL\Sdk\Model\Shipment\Shipment;
use MyParcelNL\Sdk\Services\CoreApi\ShipmentApiFactory;

final class ShipmentCollection
{
    use HasUserAgent;

    private const MAX_SHIPMENTS_PER_CALL = 100;

    /**
     * @var Shipment[]
     */
    private array $shipments = [];

    private ShipmentApi $api;

    private string $apiKey;

    public function __construct(
        string $apiKey,
        ?ShipmentApi $api = null,
        ?string $baseUri = null
    ) {
        $this->apiKey = $apiKey;
        $this->api = $api ?? ShipmentApiFactory::make($apiKey, $baseUri);
    }

    public function addShipment(Shipment $shipment): self
    {
        $this->shipments[] = $shipment;

        return $this;
    }

    /**
     * @param Shipment[] $shipments
     */
    public function addShipments(array $shipments): self
    {
        foreach ($shipments as $shipment) {
            if (! $shipment instanceof Shipment) {
                throw new InvalidArgumentException(
                    'All items must be instances of ' . Shipment::class
                );
            }

            $this->shipments[] = $shipment;
        }

        return $this;
    }

    /**
     * @param bool $keepKeys
     *
     * @return Shipment[]
     */
    public function getShipments(bool $keepKeys = true): array
    {
        if ($keepKeys) {
            return $this->shipments;
        }

        return array_values($this->shipments);
    }

    /**
     * @return Shipment
     * @throws BadMethodCallException
     */
    public function getOneShipment(): Shipment
    {
        if ($this->count() > 1) {
            throw new BadMethodCallException('Can\'t run getOneShipment(): Multiple items found');
        }

        $first = reset($this->shipments);

        if (false === $first) {
            throw new BadMethodCallException('Can\'t run getOneShipment(): No items found');
        }

        return $first;
    }

    /**
     * Filter shipments by exact reference identifier.
     *
     * @return Shipment[]
     */
    public function getShipmentsByReferenceId(string $id): array
    {
        return array_values(array_filter(
            $this->shipments,
            static fn (Shipment $s) => $s->getReferenceIdentifier() === $id
        ));
    }

    /**
     * Filter shipments whose reference identifier starts with the given prefix.
     *
     * @return Shipment[]
     */
    public function getShipmentsByReferenceIdGroup(string $prefix): array
    {
        return array_values(array_filter(
            $this->shipments,
            static fn (Shipment $s) => $s->getReferenceIdentifier() !== null
                && str_starts_with($s->getReferenceIdentifier(), $prefix)
        ));
    }

    public function clearShipmentsCollection(): self
    {
        $this->shipments = [];

        return $this;
    }

    public function count(): int
    {
        return count($this->shipments);
    }

    /**
     * Create shipments through the generated ShipmentApi client.
     *
     * @param mixed $format
     * @param mixed $positions
     * @return array<int, string|null> Mapping shipment ID => reference identifier.
     */
    public function createConcepts($format = null, $positions = null): array
    {
        $this->validateBeforeCreate();
        $this->ensureReferenceIds();

        $request = $this->buildCreateRequest();
        $userAgentHeader = $this->getUserAgentHeader();

        $response = $this->api->postShipments(
            $userAgentHeader,
            $request,
            $format,
            $positions,
            ShipmentApi::contentTypes['postShipments'][0]
        );

        return $this->parseCreateResponse($response);
    }

    /**
     * Create shipments and send labels directly to a printer group.
     *
     * @return array<int, string|null> Mapping shipment ID => reference identifier.
     */
    public function printDirect(string $printerGroupId): array
    {
        $this->validateBeforeCreate();
        $this->ensureReferenceIds();

        $body = $this->buildRequestBody();

        $headers = MyParcelRequest::HEADER_CONTENT_TYPE_SHIPMENT;
        $headers['Accept'] = MyParcelRequest::getDirectPrintAcceptHeader($printerGroupId)['Accept'];

        $request = (new MyParcelRequest())
            ->setUserAgents($this->getUserAgent())
            ->setRequestParameters($this->apiKey, $body, $headers)
            ->sendRequest();

        return $this->parseMyParcelResponse($request);
    }

    private function validateBeforeCreate(): void
    {
        if (empty($this->shipments)) {
            throw new InvalidArgumentException('At least one shipment must be added before calling createConcepts()');
        }

        if (count($this->shipments) > self::MAX_SHIPMENTS_PER_CALL) {
            throw new InvalidArgumentException(
                sprintf('Maximum %d shipments per call', self::MAX_SHIPMENTS_PER_CALL)
            );
        }
    }

    /**
     * Ensure every shipment has a reference identifier.
     */
    private function ensureReferenceIds(): void
    {
        foreach ($this->shipments as $shipment) {
            if (! $shipment->getReferenceIdentifier()) {
                $shipment->setReferenceIdentifier('sdk_' . uniqid('', true));
            }
        }
    }

    /**
     * Build JSON request body for the MyParcel shipments endpoint.
     */
    private function buildRequestBody(): string
    {
        $shipmentsData = array_map(static function (Shipment $shipment): array {
            $encoded = json_decode(json_encode($shipment), true);

            return is_array($encoded) ? $encoded : [];
        }, $this->shipments);

        $body = json_encode(['data' => [
            'shipments'  => $shipmentsData,
            'user_agent' => $this->getUserAgentHeader(),
        ]]);

        if (! is_string($body)) {
            throw new InvalidArgumentException('Unable to encode shipment payload for direct printing.');
        }

        return $body;
    }

    private function buildCreateRequest(): ShipmentPostShipmentsRequestV11
    {
        $data = new ShipmentPostShipmentsRequestV11Data();
        $data->setShipments($this->shipments);
        $data->setUserAgent($this->getUserAgentHeader());

        $request = new ShipmentPostShipmentsRequestV11();
        $request->setData($data);

        return $request;
    }

    /**
     * @return array<int, string|null>
     */
    private function parseCreateResponse($response): array
    {
        if (! $response instanceof InlineObject) {
            throw new InvalidArgumentException('Unexpected response type returned by ShipmentApi::postShipments()');
        }

        $responseData = $response->getData();
        $requestReferences = array_values(array_map(
            static fn (Shipment $shipment): ?string => $shipment->getReferenceIdentifier(),
            $this->shipments
        ));

        $mapping = [];
        foreach ($responseData->getIds() as $index => $idObject) {
            $referenceIdentifier = $this->normalizeReferenceIdentifier($idObject->getReferenceIdentifier());

            // TEMP WORKAROUND (remove after CoreAPI spec/codegen fix):
            // The generated client may deserialize reference_identifier as a wrapper
            // object for the current union schema instead of a scalar string.
            // In that case we fallback to the request-side reference identifier.
            if (null === $referenceIdentifier && isset($requestReferences[$index])) {
                $referenceIdentifier = $requestReferences[$index];
            }

            $mapping[(int) $idObject->getId()] = $referenceIdentifier;
        }

        return $mapping;
    }

    /**
     * Normalize generated reference_identifier values to scalar strings.
     *
     * TEMP WORKAROUND (remove after CoreAPI spec/codegen fix):
     * OpenAPI generator may deserialize this field as an empty wrapper object
     * for the current union schema. In that case we return null and let caller
     * fallback to the request-side reference identifier.
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
                // Ignore and fallback to json extraction.
            }
        }

        $decoded = json_decode(json_encode($referenceIdentifier), true);

        if (is_string($decoded) || is_int($decoded) || is_float($decoded)) {
            return (string) $decoded;
        }

        if (is_array($decoded)) {
            foreach (['reference_identifier', 'value', 'id'] as $key) {
                if (isset($decoded[$key]) && (is_string($decoded[$key]) || is_int($decoded[$key]) || is_float($decoded[$key]))) {
                    return (string) $decoded[$key];
                }
            }
        }

        return null;
    }

    /**
     * Parse the standard MyParcel API response (data.ids) into a mapping.
     *
     * @return array<int, string|null>
     */
    private function parseMyParcelResponse(MyParcelRequest $request): array
    {
        $responseIds = $request->getResult('data.ids') ?? [];

        if (! is_array($responseIds)) {
            return [];
        }

        $mapping = [];

        foreach ($responseIds as $responseShipment) {
            if (! is_array($responseShipment) || ! array_key_exists('id', $responseShipment)) {
                continue;
            }

            $mapping[(int) $responseShipment['id']] = $responseShipment['reference_identifier'] ?? null;
        }

        return $mapping;
    }
}
