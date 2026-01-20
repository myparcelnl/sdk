<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Capabilities;

use MyParcel\CoreApi\Generated\Shipments\Model\CapabilitiesPostCapabilitiesRequestV2 as CoreRequestV2;
use MyParcel\CoreApi\Generated\Shipments\Model\CapabilitiesRecipient as CoreRecipientV2;
use MyParcel\CoreApi\Generated\Shipments\Model\CapabilitiesSender as CoreSenderV2;
use MyParcel\CoreApi\Generated\Shipments\Model\CapabilitiesOptions as CoreOptionsV2;
use MyParcel\CoreApi\Generated\Shipments\Model\CapabilitiesPhysicalProperties as CorePhysicalPropertiesV2;
use MyParcel\CoreApi\Generated\Shipments\Model\PhysicalPropertiesHeight;
use MyParcel\CoreApi\Generated\Shipments\Model\PhysicalPropertiesLength;
use MyParcel\CoreApi\Generated\Shipments\Model\PhysicalPropertiesWidth;
use MyParcel\CoreApi\Generated\Shipments\Model\PhysicalPropertiesWeight;
use MyParcel\CoreApi\Generated\Shipments\Model\CapabilitiesResponsesCapabilitiesV2 as CoreResponseV2;
use MyParcel\CoreApi\Generated\Shipments\Model\RefShipmentPackageTypeV2;
use MyParcel\CoreApi\Generated\Shipments\Model\RefTypesCarrierV2;
use MyParcel\CoreApi\Generated\Shipments\Model\RefTypesDeliveryTypeV2;

class CapabilitiesMapper
{
    /**
     * Map the SDK request to the generated Core API request model.
     *
     * Minimal required input is recipient.country_code.
     * Optional fields (if provided): shop_id, carrier, delivery_type, package_type, direction, 
     * sender, options, physical_properties.
     *
     * All mapping uses generated models from the Core API spec for type safety and automatic updates.
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

        // Optional: delivery type - validate with RefTypesDeliveryTypeV2
        if ($request->getDeliveryType()) {
            $deliveryType = $request->getDeliveryType();
            $allowedDeliveryTypes = RefTypesDeliveryTypeV2::getAllowableEnumValues();
            if (in_array($deliveryType, $allowedDeliveryTypes, true)) {
                $core->setDeliveryType($deliveryType);
            }
            // Invalid delivery types are ignored (no exception thrown)
        }

        // Optional: carrier - validate with RefTypesCarrierV2
        if ($request->getCarrier()) {
            $carrier = $request->getCarrier();
            $allowedCarriers = RefTypesCarrierV2::getAllowableEnumValues();
            if (in_array($carrier, $allowedCarriers, true)) {
                $core->setCarrier($carrier);
            }
            // Invalid carriers are ignored (no exception thrown)
        }

        // Optional: package type - validate with RefShipmentPackageTypeV2
        if ($request->getPackageType()) {
            $packageType = $request->getPackageType();
            $allowedPackageTypes = RefShipmentPackageTypeV2::getAllowableEnumValues();
            if (in_array($packageType, $allowedPackageTypes, true)) {
                $core->setPackageType($packageType);
            }
            // Invalid package types are ignored (no exception thrown)
        }

        // Optional: direction - validate with generated allowable values
        if ($request->getDirection()) {
            $direction = $request->getDirection();
            $allowedDirections = (new CoreRequestV2())->getDirectionAllowableValues();
            if (in_array($direction, $allowedDirections, true)) {
                $core->setDirection($direction);
            }
            // Invalid directions are ignored (no exception thrown)
        }

        // Optional: sender - map to generated CoreSenderV2
        if ($request->getSender()) {
            $senderData = $request->getSender();
            if (isset($senderData['country_code']) || isset($senderData['is_business'])) {
                $sender = new CoreSenderV2();
                if (isset($senderData['country_code'])) {
                    $sender->setCountryCode($senderData['country_code']);
                }
                if (isset($senderData['is_business'])) {
                    $sender->setIsBusiness($senderData['is_business']);
                }
                $core->setSender($sender);
            }
        }

        // Optional: options - map to generated CoreOptionsV2
        if ($request->getOptions()) {
            $optionsData = $request->getOptions();
            $options = new CoreOptionsV2();
            
            // Map all available options - empty values mean "enabled"
            foreach ($optionsData as $key => $value) {
                switch ($key) {
                    case 'additional_insurance':
                        $options->setAdditionalInsurance($value ?: new \stdClass());
                        break;
                    case 'cash_on_delivery':
                        $options->setCashOnDelivery($value ?: new \stdClass());
                        break;
                    case 'deliver_at_postal_point':
                        $options->setDeliverAtPostalPoint($value ?: new \stdClass());
                        break;
                    case 'hide_sender':
                        $options->setHideSender($value ?: new \stdClass());
                        break;
                    case 'insurance':
                        $options->setInsurance($value ?: new \stdClass());
                        break;
                    case 'no_tracking':
                        $options->setNoTracking($value ?: new \stdClass());
                        break;
                    case 'oversized_package':
                        $options->setOversizedPackage($value ?: new \stdClass());
                        break;
                    case 'recipient_only_delivery':
                        $options->setRecipientOnlyDelivery($value ?: new \stdClass());
                        break;
                    case 'return_on_first_failed_delivery':
                        $options->setReturnOnFirstFailedDelivery($value ?: new \stdClass());
                        break;
                    case 'requires_age_verification':
                        $options->setRequiresAgeVerification($value ?: new \stdClass());
                        break;
                    case 'requires_receipt_code':
                        $options->setRequiresReceiptCode($value ?: new \stdClass());
                        break;
                    case 'requires_signature':
                        $options->setRequiresSignature($value ?: new \stdClass());
                        break;
                    case 'same_day_delivery':
                        $options->setSameDayDelivery($value ?: new \stdClass());
                        break;
                    case 'saturday_delivery':
                        $options->setSaturdayDelivery($value ?: new \stdClass());
                        break;
                    case 'scheduled_collection':
                        $options->setScheduledCollection($value ?: new \stdClass());
                        break;
                }
            }
            $core->setOptions($options);
        }

        // Optional: physical_properties - map to generated CorePhysicalPropertiesV2
        if ($request->getPhysicalProperties()) {
            $physicalData = $request->getPhysicalProperties();
            $physical = new CorePhysicalPropertiesV2();
            
            if (isset($physicalData['height'])) {
                $height = new PhysicalPropertiesHeight();
                $height->setValue($physicalData['height']['value']);
                $height->setUnit($physicalData['height']['unit']);
                $physical->setHeight($height);
            }
            
            if (isset($physicalData['width'])) {
                $width = new PhysicalPropertiesWidth();
                $width->setValue($physicalData['width']['value']);
                $width->setUnit($physicalData['width']['unit']);
                $physical->setWidth($width);
            }
            
            if (isset($physicalData['length'])) {
                $length = new PhysicalPropertiesLength();
                $length->setValue($physicalData['length']['value']);
                $length->setUnit($physicalData['length']['unit']);
                $physical->setLength($length);
            }
            
            if (isset($physicalData['weight'])) {
                $weight = new PhysicalPropertiesWeight();
                $weight->setValue($physicalData['weight']['value']);
                $weight->setUnit($physicalData['weight']['unit']);
                $physical->setWeight($weight);
            }
            
            $core->setPhysicalProperties($physical);
        }

        return $core;
    }

    /**
     * Map the Core API response to the immutable SDK response model.
     *
     *
     * NOTE: Response 'physical_properties' are not mapped in the response as they are input-only fields.
     *       For now we expose option *keys* to signal presence.
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
        $carrierInconsistent = false; // track if carrier inconsistency was found
        $colloMax         = null; // keep the highest max found

        foreach ($results as $res) {
            // Merge & dedupe package/delivery types
            $packageTypes  = array_values(array_unique(array_merge($packageTypes,  (array) $res->getPackageTypes())));
            $deliveryTypes = array_values(array_unique(array_merge($deliveryTypes, (array) $res->getDeliveryTypes())));

            // Collect option keys (presence only)
            $optionsObj  = (array) $res->getOptions();
            $optionKeys  = array_values(array_unique(array_merge($optionKeys, array_keys($optionsObj))));

            // Carrier: ensure consistency across results, once inconsistent stay null
            if (!$carrierInconsistent) {
                $resCarrier = $res->getCarrier();
                if (null === $carrier) {
                    $carrier = $resCarrier;
                } elseif ($carrier !== $resCarrier) {
                    $carrier = null;
                    $carrierInconsistent = true; // Mark as inconsistent, prevent future overwrites
                }
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

            // NOTE: physical_properties are input-only fields in requests, not part of the response
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
