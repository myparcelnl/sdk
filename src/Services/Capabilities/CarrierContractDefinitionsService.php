<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Capabilities;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Client;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostContractDefinitionsRequestV2;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostContractDefinitionsRequestV2Carrier;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesResponsesContractDefinitionsV2;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesContractDefinitionsResponseContractDefinitionsV2;
use MyParcelNL\Sdk\Concerns\CreatesCoreApiClient;

/**
 * Fetch account-level carrier contract definitions through the Jane Core API client.
 *
 * Use this service when integrations need to build carrier settings or discover the
 * carriers/contracts available for an account. The generated client models remain
 * the source of truth for request validation and response shape.
 *
 * Use CapabilitiesService for shipment-specific capability checks based on
 * address, weight and shipment options.
 *
 * The generated client reads a response without validation, so a new carrier in the API does not
 * break the call.
 */
final class CarrierContractDefinitionsService
{
    use CreatesCoreApiClient;

    /**
     * Create the service with an API key, or with a client that keeps its own User-Agent.
     *
     * @param string      $apiKey The API key of the client that the service creates.
     * @param string|null $host   The host of the client that the service creates.
     * @param Client|null $api    A client with its own API key, host, headers or User-Agent, such as one
     *                            from ShipmentApiFactory. With a client, `$apiKey` and `$host` are not used.
     */
    public function __construct(
        string $apiKey,
        ?string $host = null,
        ?Client $api = null
    ) {
        $this->apiKey = $apiKey;
        $this->host   = $host;
        $this->api    = $api;
    }

    /**
     * Fetch contract definitions available for the configured API key.
     *
     * Pass a carrier, such as `CapabilitiesPostContractDefinitionsRequestV2Carrier::Postnl`, to filter the
     * API request. Omit $carrier to fetch all contract definitions for the account.
     *
     * The results are the Jane models. `carrier`, `packageTypes`, `deliveryTypes` and `transactionTypes`
     * hold an enum case, or the raw string for a value that the SDK does not know. So a new carrier in
     * the API does not break the call. Compare with the enum case, because `->value` fails on a raw
     * string.
     *
     * @param CapabilitiesPostContractDefinitionsRequestV2Carrier|null $carrier Optional carrier.
     * @return RefCapabilitiesContractDefinitionsResponseContractDefinitionsV2[]
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\ApiException For an error response.
     * @throws InvalidArgumentException When the generated client returns the v1 model, which the request does not ask for.
     */
    public function getContractDefinitions(?CapabilitiesPostContractDefinitionsRequestV2Carrier $carrier = null): array
    {
        $request = new CapabilitiesPostContractDefinitionsRequestV2();

        if (null !== $carrier) {
            $request->carrier = $carrier;
        }

        $response = $this->api()->postCapabilitiesContractDefinitions(requestBody: $request);

        // The generated method declares the v1 and the v2 response. The request asks for v2, so this
        // check only narrows the type.
        if (! $response instanceof CapabilitiesResponsesContractDefinitionsV2) {
            throw new InvalidArgumentException(
                'Unexpected response type returned by Client::postCapabilitiesContractDefinitions().'
            );
        }

        return $response->items ?? [];
    }
}
