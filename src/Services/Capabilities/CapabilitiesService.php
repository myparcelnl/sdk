<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Capabilities;

use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesMapper;
use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesRequest;
use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesResponse;
use MyParcelNL\Sdk\Model\Shipment\Shipment;
use MyParcelNL\Sdk\Services\CoreApi\CapabilitiesClientInterface;
use MyParcelNL\Sdk\Services\CoreApi\HttpCapabilitiesClient;


/**
 * Thin service facade for capabilities lookup.
 *
 * Future enhancements (caching, retries, feature flags) can be added here.
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
     * @param CapabilitiesRequest $request
     * @return CapabilitiesResponse
     * @throws \MyParcelNL\Sdk\Exception\ApiException
     */
    public function get(CapabilitiesRequest $request): CapabilitiesResponse
    {
        return $this->client->getCapabilities($request);
    }

    public function fromShipment(Shipment $shipment): CapabilitiesResponse
    {
        return $this->get(CapabilitiesRequest::fromShipment($shipment));
    }
}
