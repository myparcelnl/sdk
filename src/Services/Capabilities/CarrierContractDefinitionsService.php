<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Capabilities;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Client;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostContractDefinitionsRequestV2;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostContractDefinitionsRequestV2Carrier;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesResponsesContractDefinitionsV2;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesContractDefinitionsResponseContractDefinitionsV2;
use MyParcelNL\Sdk\Concerns\HasUserAgent;
use MyParcelNL\Sdk\Services\CoreApi\ShipmentApiFactory;

/**
 * Fetch account-level carrier contract definitions through the Jane Core API client.
 *
 * Use this service when integrations need to build carrier settings or discover the
 * carriers/contracts available for an account. The generated client models remain
 * the source of truth for request validation and response shape.
 *
 * Use CapabilitiesService for shipment-specific capability checks based on
 * address, weight and shipment options.
 */
final class CarrierContractDefinitionsService
{
    use HasUserAgent;

    private Client $api;

    /**
     * @param string      $apiKey API key used when creating the default generated client.
     *                            Ignored when a preconfigured $api is passed.
     * @param string|null $host   Optional host override used when creating the default generated client.
     *                            Ignored when a preconfigured $api is passed.
     * @param Client|null $api    Optional preconfigured generated client, primarily for tests or custom transports.
     *                            When provided, it is used as-is.
     */
    public function __construct(
        string $apiKey,
        ?string $host = null,
        ?Client $api = null
    ) {
        $this->api = $api ?? ShipmentApiFactory::client($apiKey, $host, $this->getUserAgentHeader());
    }

    /**
     * Fetch contract definitions available for the configured API key.
     *
     * Pass a carrier, such as `'POSTNL'` or `CapabilitiesPostContractDefinitionsRequestV2Carrier::Postnl`,
     * to filter the API request. Omit $carrier to fetch all contract definitions for the account.
     *
     * @param CapabilitiesPostContractDefinitionsRequestV2Carrier|string|null $carrier Optional carrier.
     * @return RefCapabilitiesContractDefinitionsResponseContractDefinitionsV2[]
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\ApiException For an error response.
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Normalizer\ValidationException
     *                                  When the spec does not know $carrier. No request goes out.
     * @throws InvalidArgumentException When the generated client returns a non-success model.
     */
    public function getContractDefinitions(CapabilitiesPostContractDefinitionsRequestV2Carrier|string|null $carrier = null): array
    {
        $request = new CapabilitiesPostContractDefinitionsRequestV2();

        if (null !== $carrier) {
            $request->carrier = $carrier;
        }

        $response = $this->api->postCapabilitiesContractDefinitions(
            $request,
            ['User-Agent' => $this->getUserAgentHeader()]
        );

        if (! $response instanceof CapabilitiesResponsesContractDefinitionsV2) {
            throw new InvalidArgumentException(
                'Unexpected response type returned by Client::postCapabilitiesContractDefinitions().'
            );
        }

        return $response->items ?? [];
    }
}
