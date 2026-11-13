<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Capabilities;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2PhysicalPropertiesHeightUnit as HeightUnit;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2PhysicalPropertiesLengthUnit as LengthUnit;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2PhysicalPropertiesWeightUnit as WeightUnit;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2PhysicalPropertiesWidthUnit as WidthUnit;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesResponsesCapabilitiesV2;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItem as ShipmentItem;
use MyParcelNL\Sdk\Client\Jane\CoreApiModels;
use MyParcelNL\Sdk\Services\CoreApi\CapabilitiesClientInterface;
use MyParcelNL\Sdk\Services\CoreApi\HttpCapabilitiesClient;

/**
 * Gets the capabilities of the API for a request or a shipment.
 *
 * The response is the generated model: one result per carrier contract in `$response->results`, with
 * its package types, delivery types, options, physical limits and the maximum number of colli.
 *
 * Build the request with the generated models and enums. `CoreApiModels::toModel()` fills one from
 * input that is already an array.
 */
final class CapabilitiesService implements CapabilitiesServiceInterface
{
    /**
     * The unit of each physical property of a shipment. The shipment model has no unit.
     */
    private const PHYSICAL_PROPERTY_UNITS = [
        'weight' => WeightUnit::G,
        'height' => HeightUnit::Cm,
        'length' => LengthUnit::Cm,
        'width'  => WidthUnit::Cm,
    ];

    private CapabilitiesClientInterface $client;

    public function __construct(?CapabilitiesClientInterface $client = null)
    {
        $this->client = $client ?? new HttpCapabilitiesClient();
    }

    /**
     * Get the capabilities for a request.
     *
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\ApiException For an error response.
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Normalizer\ValidationException For a value that the spec does not know, such as a carrier. No request goes out.
     * @throws \UnexpectedValueException For a v1 response, which the request does not ask for.
     */
    public function get(CapabilitiesPostCapabilitiesRequestV2 $request): CapabilitiesResponsesCapabilitiesV2
    {
        return $this->client->getCapabilities($request);
    }

    /**
     * Get the capabilities for a shipment, from the country code of its recipient and its physical
     * properties. To filter on a carrier or a package type, build the request yourself and call `get()`.
     *
     * @throws InvalidArgumentException When the recipient has no country code.
     */
    public function fromShipment(ShipmentItem $shipment): CapabilitiesResponsesCapabilitiesV2
    {
        $countryCode = $shipment->recipient->cc ?? null;

        if (! $countryCode) {
            throw new InvalidArgumentException('Recipient with country code (cc) is required for capabilities.');
        }

        $physicalProperties = [];

        foreach (self::PHYSICAL_PROPERTY_UNITS as $name => $unit) {
            if (isset($shipment->physicalProperties->{$name})) {
                $physicalProperties[$name] = ['value' => $shipment->physicalProperties->{$name}, 'unit' => $unit->value];
            }
        }

        return $this->get(CoreApiModels::toModel(array_filter([
            'recipient'          => ['countryCode' => (string) $countryCode],
            'physicalProperties' => $physicalProperties,
        ]), CapabilitiesPostCapabilitiesRequestV2::class));
    }
}
