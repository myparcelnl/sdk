<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Capabilities;

use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesMapper;
use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesRequest;
use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesResponse;
use MyParcelNL\Sdk\Services\CoreApi\CapabilitiesClientInterface;
use MyParcelNL\Sdk\Services\CoreApi\HttpCapabilitiesClient;

/**
 * Thin service facade for capabilities lookup.
 * 
 * Provides a stable API for fetching carrier capabilities while hiding 
 * low-level client/mapper details. Future enhancements (caching, retries, 
 * feature flags) can be added here without breaking consumer code.
 */
final class CapabilitiesService implements CapabilitiesServiceInterface
{
    private CapabilitiesClientInterface $client;

    public function __construct(?CapabilitiesClientInterface $client = null)
    {
        $this->client = $client ?? new HttpCapabilitiesClient(new CapabilitiesMapper());
    }

    /**
     * Fetch capabilities for the given request.
     *
     * @param CapabilitiesRequest $request The capabilities request
     * @return CapabilitiesResponse The capabilities response
     * @throws \MyParcelNL\Sdk\Exception\ApiException If the API request fails
     */
    public function get(CapabilitiesRequest $request): CapabilitiesResponse
    {
        return $this->client->getCapabilities($request);
    }
}