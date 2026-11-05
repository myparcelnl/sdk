<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Capabilities;

use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesMapper;
use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesRequest;
use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesResponse;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItem as ShipmentItem;
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
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\ApiException For an error response.
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Normalizer\ValidationException For a value that the spec does not know, such as a carrier. No request goes out.
     * @throws \InvalidArgumentException For a physical property that is not a whole number.
     * @throws \UnexpectedValueException For a v1 response, which the request does not ask for.
     */
    public function get(CapabilitiesRequest $request): CapabilitiesResponse
    {
        return $this->client->getCapabilities($request);
    }

    public function fromShipment(ShipmentItem $shipment): CapabilitiesResponse
    {
        return $this->get(CapabilitiesRequest::fromShipment($shipment));
    }
}
