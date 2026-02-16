<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Capabilities;

use MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\CapabilitiesPostCapabilitiesRequestV2 as CoreRequestV2;
use MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\CapabilitiesRecipientV2 as CoreRecipientV2;
use MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\CapabilitiesSenderV2 as CoreSenderV2;
use MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\CapabilitiesOptionsV2 as CoreOptionsV2;
use MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\CapabilitiesPhysicalPropertiesV2 as CorePhysicalPropertiesV2;
use MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\CapabilitiesPostCapabilitiesRequestV2Pickup as CorePickupV2;
use MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\CapabilitiesPostCapabilitiesRequestV2PickupLocation as CorePickupLocationV2;
use MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\PhysicalPropertiesHeightV2;
use MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\PhysicalPropertiesLengthV2;
use MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\PhysicalPropertiesWidthV2;
use MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\PhysicalPropertiesWeightV2;
use MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\CapabilitiesResponsesCapabilitiesV2 as CoreResponseV2;

class CapabilitiesMapper
{
    /**
     * Map SDK request to the Core API request model.
     *
     * Note: we intentionally do not filter enum-like values (carrier, deliveryType, packageType, direction)
     * to preserve forward compatibility when the API adds new allowable values.
     */
    public function mapToCoreApi(CapabilitiesRequest $request): CoreRequestV2
    {
        $core = new CoreRequestV2();

        // Required: recipient.country_code
        $recipient = new CoreRecipientV2();
        $recipient->setCountryCode($request->getCountryCode());
        $core->setRecipient($recipient);

        // Optional: shop_id
        if (null !== $request->getShopId()) {
            $core->setShopId($request->getShopId());
        }

        // Optional: carrier / delivery_type / package_type / direction (pass-through)
        if (null !== $request->getCarrier()) {
            $core->setCarrier($request->getCarrier());
        }

        if (null !== $request->getDeliveryType()) {
            $core->setDeliveryType($request->getDeliveryType());
        }

        if (null !== $request->getPackageType()) {
            $core->setPackageType($request->getPackageType());
        }

        if (null !== $request->getDirection()) {
            $core->setDirection($request->getDirection());
        }

        // Optional: sender
        if ($request->getSender()) {
            $senderData = $request->getSender();
            $sender = new CoreSenderV2();

            if (array_key_exists('country_code', $senderData)) {
                $sender->setCountryCode($senderData['country_code']);
            }

            if (array_key_exists('is_business', $senderData)) {
                $sender->setIsBusiness($senderData['is_business']);
            }

            $core->setSender($sender);
        }

        // Optional: options
        if ($request->getOptions()) {
            $core->setOptions($this->mapOptions($request->getOptions()));
        }

        // Optional: physical_properties
        if ($request->getPhysicalProperties()) {
            $core->setPhysicalProperties($this->mapPhysicalProperties($request->getPhysicalProperties()));
        }

        // Optional: pickup
        if ($request->getPickup()) {
            $core->setPickup($this->mapPickup($request->getPickup()));
        }

        return $core;
    }

    /**
     * Map Core API response to the SDK response model.
     *
     * Note: physical properties are treated as input-only for this SDK response.
     */
    public function mapFromCoreApi(CoreResponseV2 $core): CapabilitiesResponse
    {
        $results = $core->getResults() ?? [];

        if (empty($results)) {
            return new CapabilitiesResponse([], [], [], null, [], null);
        }

        $packageTypes = [];
        $deliveryTypes = [];
        $optionKeys = [];
        $transactionTypes = [];
        $carrier = null;
        $carrierInconsistent = false;
        $colloMax = null;

        foreach ($results as $res) {
            $packageTypes = array_values(array_unique(array_merge(
                $packageTypes,
                (array) $res->getPackageTypes()
            )));

            $deliveryTypes = array_values(array_unique(array_merge(
                $deliveryTypes,
                (array) $res->getDeliveryTypes()
            )));

            $optionKeys = array_values(array_unique(array_merge(
                $optionKeys,
                array_keys((array) $res->getOptions())
            )));

            if (! $carrierInconsistent) {
                $resCarrier = $res->getCarrier();
                if (null === $carrier) {
                    $carrier = $resCarrier;
                } elseif ($carrier !== $resCarrier) {
                    $carrier = null;
                    $carrierInconsistent = true;
                }
            }

            $transactionTypes = array_values(array_unique(array_merge(
                $transactionTypes,
                (array) $res->getTransactionTypes()
            )));

            $collo = $res->getCollo();
            if ($collo && method_exists($collo, 'getMax')) {
                $colloMax = max($colloMax ?? 0, (int) $collo->getMax());
            }
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

    /**
     * Map SDK shipment option data to a Core API CapabilitiesOptionsV2 instance.
     *
     * Unknown options are ignored silently.
     *
     * @param array<string, mixed> $optionsData
     */
    private function mapOptions(array $optionsData): CoreOptionsV2
    {
        $options = new CoreOptionsV2();

        foreach ($optionsData as $key => $value) {
            $setter = self::KNOWN_OPTION_SETTERS[$key] ?? $this->getFallbackSetterName($key);

            if (! method_exists($options, $setter)) {
                continue;
            }

            $options->{$setter}($this->normalizeOptionValue($value));
        }

        return $options;
    }

    /**
     * Explicit mappings where the Core API v2 setter name differs semantically from the option key.
     * New options should flow through via the fallback setter mapping.
     */
    private const KNOWN_OPTION_SETTERS = [
        'signature'          => 'setRequiresSignature',
        'only_recipient'     => 'setRecipientOnlyDelivery',
        'age_check'          => 'setRequiresAgeVerification',
        'receipt_code'       => 'setRequiresReceiptCode',
        'large_format'       => 'setOversizedPackage',
        'printerless_return' => 'setPrintReturnLabelAtDropOff',
        'collect'            => 'setScheduledCollection',
        'return'             => 'setReturnOnFirstFailedDelivery',
    ];

    /**
     * Convert snake_case to a setter name.
     *
     * Example: same_day_delivery → setSameDayDelivery
     */
    private function getFallbackSetterName(string $key): string
    {
        $studly = str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));

        return 'set' . $studly;
    }

    /**
     * In the Capabilities API, null typically represents an enabled option without configuration.
     *
     * Important: do not use truthy checks, as false/0 may be valid values.
     *
     * @param mixed $value
     * @return mixed
     */
    private function normalizeOptionValue($value)
    {
        return $value === null ? new \stdClass() : $value;
    }

    /**
     * @param array<string, mixed> $physicalData
     */
    private function mapPhysicalProperties(array $physicalData): CorePhysicalPropertiesV2
    {
        $physical = new CorePhysicalPropertiesV2();

        if (isset($physicalData['height']) && is_array($physicalData['height'])) {
            $h = $physicalData['height'];
            if (array_key_exists('value', $h) && array_key_exists('unit', $h)) {
                $height = new PhysicalPropertiesHeightV2();
                $height->setValue($h['value']);
                $height->setUnit($h['unit']);
                $physical->setHeight($height);
            }
        }

        if (isset($physicalData['width']) && is_array($physicalData['width'])) {
            $w = $physicalData['width'];
            if (array_key_exists('value', $w) && array_key_exists('unit', $w)) {
                $width = new PhysicalPropertiesWidthV2();
                $width->setValue($w['value']);
                $width->setUnit($w['unit']);
                $physical->setWidth($width);
            }
        }

        if (isset($physicalData['length']) && is_array($physicalData['length'])) {
            $l = $physicalData['length'];
            if (array_key_exists('value', $l) && array_key_exists('unit', $l)) {
                $length = new PhysicalPropertiesLengthV2();
                $length->setValue($l['value']);
                $length->setUnit($l['unit']);
                $physical->setLength($length);
            }
        }

        if (isset($physicalData['weight']) && is_array($physicalData['weight'])) {
            $w = $physicalData['weight'];
            if (array_key_exists('value', $w) && array_key_exists('unit', $w)) {
                $weight = new PhysicalPropertiesWeightV2();
                $weight->setValue($w['value']);
                $weight->setUnit($w['unit']);
                $physical->setWeight($weight);
            }
        }

        return $physical;
    }

    /**
     * @param array<string, mixed> $pickupData
     */
    private function mapPickup(array $pickupData): CorePickupV2
    {
        $pickup = new CorePickupV2();

        if (isset($pickupData['location']) && is_array($pickupData['location'])) {
            $locationData = $pickupData['location'];
            $location = new CorePickupLocationV2();

            if (array_key_exists('type', $locationData)) {
                $location->setType($locationData['type']);
            }

            $pickup->setLocation($location);
        }

        return $pickup;
    }
}
