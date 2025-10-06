<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Capabilities;

use MyParcel\CoreApi\Generated\Capabilities\Model\CapabilitiesPostCapabilitiesRequestV2 as CoreRequestV2;
use MyParcel\CoreApi\Generated\Capabilities\Model\CapabilitiesPostCapabilitiesRequestV2Recipient as CoreRecipientV2;
use MyParcel\CoreApi\Generated\Capabilities\Model\CapabilitiesResponsesCapabilitiesV2 as CoreResponseV2;

class CapabilitiesMapper
{
    /**
     * Map the SDK request to the generated Core API request model.
     *
     * Minimal required input is recipient.country_code.
     * Optional fields (if provided): shop_id, carrier, delivery_type, package_type, direction.
     *
     * NOTE: Request 'options' and 'physical_properties' are intentionally out of scope for now.
     *       We'll add them in a follow-up (see TODO markers below).
     */
    public function mapToCoreApi(CapabilitiesRequest $request): CoreRequestV2
    {
        $core = new CoreRequestV2();

        // Required: recipient with country code
        $recipient = new CoreRecipientV2();
        $recipient->setCountryCode($request->getCountryCode());
        $core->setRecipient($recipient);

        // Optional: shop id
        if (null !== $request->getShopId()) {
            $core->setShopId($request->getShopId());
        }

        // Optional: delivery type (SDK naming matches Core 'delivery_type')
        if ($request->getDeliveryType()) {
            $core->setDeliveryType($request->getDeliveryType());
        }

        // Optional: carrier
        if ($request->getCarrier()) {
            $core->setCarrier($request->getCarrier());
        }

        // Optional: package type
        if ($request->getPackageType()) {
            $core->setPackageType($request->getPackageType());
        }

        // Optional: direction (e.g. outward / return)
        if ($request->getDirection()) {
            $core->setDirection($request->getDirection());
        }

        // TODO [Capabilities][Follow-up]:
        // - map request options once SDK exposes a structured options object
        //   $core->setOptions($coreOptions);
        // - map request physical_properties when we decide on SDK modeling
        //   $core->setPhysicalProperties($corePhysicalProps);
        // - map sender if/when needed:
        //   $core->setSender($coreSender);

        return $core;
    }

    /**
     * Map the Core API response to the immutable SDK response model.
     *
     * We merge across ALL 'results' instead of taking just the first one
     * to avoid silently dropping capabilities.
     *
     * NOTE: Response 'physical_properties' and rich option details are intentionally not mapped yet.
     *       For now we expose option *keys* to signal presence. Physical properties will follow later.
     */
    public function mapFromCoreApi(CoreResponseV2 $core): CapabilitiesResponse
    {
        $results = $core->getResults() ?? [];

        if (empty($results)) {
            return new CapabilitiesResponse([], [], [], null, [], null);
        }

        $packageTypes     = [];
        $deliveryTypes    = [];
        $optionKeys       = [];
        $transactionTypes = [];
        $carrier          = null; // keep only if consistent across results
        $colloMax         = null; // keep the highest max found

        foreach ($results as $res) {
            // Merge & dedupe package/delivery types
            $packageTypes  = array_values(array_unique(array_merge($packageTypes,  (array) $res->getPackageTypes())));
            $deliveryTypes = array_values(array_unique(array_merge($deliveryTypes, (array) $res->getDeliveryTypes())));

            // Collect option keys (presence only)
            $optionsObj  = (array) $res->getOptions();
            $optionKeys  = array_values(array_unique(array_merge($optionKeys, array_keys($optionsObj))));

            // Carrier: ensure consistency across results, otherwise null
            $resCarrier = $res->getCarrier();
            if (null === $carrier) {
                $carrier = $resCarrier;
            } elseif ($carrier !== $resCarrier) {
                $carrier = null;
            }

            // Transaction types: merge & dedupe
            $transactionTypes = array_values(array_unique(array_merge(
                $transactionTypes,
                (array) $res->getTransactionTypes()
            )));

            // Collo max: take the highest value found
            $collo = $res->getCollo();
            if ($collo && method_exists($collo, 'getMax')) {
                $colloMax = max($colloMax ?? 0, (int) $collo->getMax());
            }

            // TODO [Capabilities][Follow-up]:
            // - physical_properties: decide on modeling + merge policy (min/max/unit)
            //   For now we omit it to avoid premature API decisions in the SDK.
        }

        return new CapabilitiesResponse(
            $packageTypes,
            $deliveryTypes,
            $optionKeys,
            $carrier,
            $transactionTypes,
            $colloMax
        );
    }
}
