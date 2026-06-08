<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Capabilities;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Api\ShipmentApi;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CapabilitiesPostContractDefinitionsRequestV2;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CapabilitiesResponsesContractDefinitionsV2;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefCapabilitiesContractDefinitionsResponseContractDefinitionsV2;
use MyParcelNL\Sdk\Concerns\HasUserAgent;
use MyParcelNL\Sdk\Services\CoreApi\ShipmentApiFactory;

/**
 * Fetch account-level carrier contract definitions through the generated Core API client.
 *
 * Use this service when integrations need to build carrier settings or discover the
 * carriers/contracts available for an account. Use CapabilitiesService for
 * shipment-specific capability checks based on address, weight and shipment options.
 */
final class CarrierContractDefinitionsService
{
    use HasUserAgent;

    private ShipmentApi $api;

    public function __construct(
        string $apiKey,
        ?ShipmentApi $api = null,
        ?string $host = null
    ) {
        $this->api = $api ?? ShipmentApiFactory::make($apiKey, $host);
    }

    /**
     * Fetch all contract definitions available for the configured API key.
     *
     * @return RefCapabilitiesContractDefinitionsResponseContractDefinitionsV2[]
     * @throws \MyParcelNL\Sdk\Client\Generated\CoreApi\ApiException
     */
    public function getAll(): array
    {
        $response = $this->api->postCapabilitiesContractDefinitions(
            $this->getUserAgentHeader(),
            new CapabilitiesPostContractDefinitionsRequestV2()
        );

        if (! $response instanceof CapabilitiesResponsesContractDefinitionsV2) {
            throw new InvalidArgumentException(
                'Unexpected response type returned by ShipmentApi::postCapabilitiesContractDefinitions().'
            );
        }

        return $response->getItems() ?? [];
    }

    /**
     * Fetch contract definitions for a carrier.
     *
     * The generated response is the source of truth. Filtering is done locally so
     * newly generated response carriers keep working even when request filtering
     * is narrower than the response contract.
     *
     * @return RefCapabilitiesContractDefinitionsResponseContractDefinitionsV2[]
     * @throws \MyParcelNL\Sdk\Client\Generated\CoreApi\ApiException
     */
    public function getByCarrier(string $carrier): array
    {
        $normalizedCarrier = $this->normalizeCarrier($carrier);

        return array_values(array_filter(
            $this->getAll(),
            fn (RefCapabilitiesContractDefinitionsResponseContractDefinitionsV2 $definition): bool => $normalizedCarrier ===
                $this->normalizeCarrier((string) $definition->getCarrier())
        ));
    }

    private function normalizeCarrier(string $carrier): string
    {
        return strtoupper((string) preg_replace('/[^a-z0-9]/i', '', $carrier));
    }
}
