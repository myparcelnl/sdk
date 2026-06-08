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

    /**
     * @param string           $apiKey API key used when creating the default generated client.
     * @param string|null      $host   Optional API host override for the default generated client.
     * @param ShipmentApi|null $api    Optional preconfigured generated client, primarily for tests or custom transports.
     *                                 When provided, the client is used as-is.
     */
    public function __construct(
        string $apiKey,
        ?string $host = null,
        ?ShipmentApi $api = null
    ) {
        $this->api = $api ?? ShipmentApiFactory::make($apiKey, $host, $this->getUserAgentHeader());
    }

    /**
     * Fetch contract definitions available for the configured API key.
     *
     * Pass a generated carrier enum value to let the generated request model
     * validate and filter the API request.
     *
     * @return RefCapabilitiesContractDefinitionsResponseContractDefinitionsV2[]
     * @throws \MyParcelNL\Sdk\Client\Generated\CoreApi\ApiException
     */
    public function getContractDefinitions(?string $carrier = null): array
    {
        $request = new CapabilitiesPostContractDefinitionsRequestV2();

        if (null !== $carrier) {
            $request->setCarrier($carrier);
        }

        $response = $this->api->postCapabilitiesContractDefinitions(
            $this->getUserAgentHeader(),
            $request
        );

        if (! $response instanceof CapabilitiesResponsesContractDefinitionsV2) {
            throw new InvalidArgumentException(
                'Unexpected response type returned by ShipmentApi::postCapabilitiesContractDefinitions().'
            );
        }

        return $response->getItems() ?? [];
    }
}
