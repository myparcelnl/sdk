<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer;

use Jane\Component\JsonSchemaRuntime\Reference;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Normalizer\CheckArray;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Normalizer\ValidatorTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
class ShipmentResponsesDropOffPointNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesDropOffPoint::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesDropOffPoint::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesDropOffPoint();
        if (null === $data || false === \is_array($data)) {
            return $object;
        }
        if (isset($data['$ref']) && !isset($data['type']) && !isset($data['properties']) && !isset($data['allOf'])) {
            return new Reference($data['$ref'], $context['document-origin']);
        }
        if (isset($data['$recursiveRef'])) {
            return new Reference($data['$recursiveRef'], $context['document-origin']);
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\ShipmentResponsesDropOffPointConstraint());
        }
        if (\array_key_exists('location_code', $data)) {
            $object->locationCode = $data['location_code'];
            unset($data['location_code']);
        }
        if (\array_key_exists('location_name', $data) && $data['location_name'] !== null) {
            $value = $data['location_name'];
            if (is_string($data['location_name'])) {
                $value = $data['location_name'];
            } elseif (is_null($data['location_name'])) {
                $value = $data['location_name'];
            }
            $object->locationName = $value;
            unset($data['location_name']);
        }
        elseif (\array_key_exists('location_name', $data)) {
            $object->locationName = null;
            unset($data['location_name']);
        }
        if (\array_key_exists('cc', $data)) {
            $object->cc = $data['cc'];
            unset($data['cc']);
        }
        if (\array_key_exists('state', $data) && $data['state'] !== null) {
            $value_1 = $data['state'];
            if (is_string($data['state'])) {
                $value_1 = $data['state'];
            } elseif (is_null($data['state'])) {
                $value_1 = $data['state'];
            }
            $object->state = $value_1;
            unset($data['state']);
        }
        elseif (\array_key_exists('state', $data)) {
            $object->state = null;
            unset($data['state']);
        }
        if (\array_key_exists('city', $data)) {
            $object->city = $data['city'];
            unset($data['city']);
        }
        if (\array_key_exists('postal_code', $data)) {
            $object->postalCode = $data['postal_code'];
            unset($data['postal_code']);
        }
        if (\array_key_exists('street', $data)) {
            $object->street = $data['street'];
            unset($data['street']);
        }
        if (\array_key_exists('number', $data) && $data['number'] !== null) {
            $value_2 = $data['number'];
            if (is_string($data['number'])) {
                $value_2 = $data['number'];
            } elseif (is_int($data['number'])) {
                $value_2 = $data['number'];
            } elseif (is_null($data['number'])) {
                $value_2 = $data['number'];
            }
            $object->number = $value_2;
            unset($data['number']);
        }
        elseif (\array_key_exists('number', $data)) {
            $object->number = null;
            unset($data['number']);
        }
        if (\array_key_exists('number_suffix', $data) && $data['number_suffix'] !== null) {
            $value_3 = $data['number_suffix'];
            if (is_string($data['number_suffix'])) {
                $value_3 = $data['number_suffix'];
            } elseif (is_null($data['number_suffix'])) {
                $value_3 = $data['number_suffix'];
            }
            $object->numberSuffix = $value_3;
            unset($data['number_suffix']);
        }
        elseif (\array_key_exists('number_suffix', $data)) {
            $object->numberSuffix = null;
            unset($data['number_suffix']);
        }
        if (\array_key_exists('phone', $data) && $data['phone'] !== null) {
            $value_4 = $data['phone'];
            if (is_string($data['phone'])) {
                $value_4 = $data['phone'];
            } elseif (is_null($data['phone'])) {
                $value_4 = $data['phone'];
            }
            $object->phone = $value_4;
            unset($data['phone']);
        }
        elseif (\array_key_exists('phone', $data)) {
            $object->phone = null;
            unset($data['phone']);
        }
        if (\array_key_exists('reference', $data) && $data['reference'] !== null) {
            $value_5 = $data['reference'];
            if (is_string($data['reference'])) {
                $value_5 = $data['reference'];
            } elseif (is_null($data['reference'])) {
                $value_5 = $data['reference'];
            }
            $object->reference = $value_5;
            unset($data['reference']);
        }
        elseif (\array_key_exists('reference', $data)) {
            $object->reference = null;
            unset($data['reference']);
        }
        if (\array_key_exists('longitude', $data) && $data['longitude'] !== null) {
            $value_6 = $data['longitude'];
            if (is_float($data['longitude'])) {
                $value_6 = $data['longitude'];
            } elseif (is_string($data['longitude'])) {
                $value_6 = $data['longitude'];
            } elseif (is_null($data['longitude'])) {
                $value_6 = $data['longitude'];
            }
            $object->longitude = $value_6;
            unset($data['longitude']);
        }
        elseif (\array_key_exists('longitude', $data)) {
            $object->longitude = null;
            unset($data['longitude']);
        }
        if (\array_key_exists('latitude', $data) && $data['latitude'] !== null) {
            $value_7 = $data['latitude'];
            if (is_float($data['latitude'])) {
                $value_7 = $data['latitude'];
            } elseif (is_string($data['latitude'])) {
                $value_7 = $data['latitude'];
            } elseif (is_null($data['latitude'])) {
                $value_7 = $data['latitude'];
            }
            $object->latitude = $value_7;
            unset($data['latitude']);
        }
        elseif (\array_key_exists('latitude', $data)) {
            $object->latitude = null;
            unset($data['latitude']);
        }
        if (\array_key_exists('available_days', $data) && $data['available_days'] !== null) {
            $values = [];
            foreach ($data['available_days'] as $value_8) {
                $value_9 = $value_8;
                if (is_int($value_8) && \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonDefsDayOfWeek::tryFrom($value_8) !== null) {
                    $value_9 = \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonDefsDayOfWeek::tryFrom($value_8) ?? $value_8;
                } elseif (is_int($value_8)) {
                    $value_9 = \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonDefsIsoDayOfWeek::tryFrom($value_8) ?? $value_8;
                }
                $values[] = $value_9;
            }
            $object->availableDays = $values;
            unset($data['available_days']);
        }
        elseif (\array_key_exists('available_days', $data)) {
            $object->availableDays = null;
            unset($data['available_days']);
        }
        if (\array_key_exists('cut_off_time', $data) && $data['cut_off_time'] !== null) {
            $value_10 = $data['cut_off_time'];
            if (is_string($data['cut_off_time'])) {
                $value_10 = $data['cut_off_time'];
            } elseif (is_null($data['cut_off_time'])) {
                $value_10 = $data['cut_off_time'];
            }
            $object->cutOffTime = $value_10;
            unset($data['cut_off_time']);
        }
        elseif (\array_key_exists('cut_off_time', $data)) {
            $object->cutOffTime = null;
            unset($data['cut_off_time']);
        }
        if (\array_key_exists('carrier_id', $data)) {
            $object->carrierId = is_int($data['carrier_id']) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesCarrier::tryFrom($data['carrier_id']) ?? $data['carrier_id'] : $data['carrier_id'];
            unset($data['carrier_id']);
        }
        if (\array_key_exists('distance', $data) && $data['distance'] !== null) {
            $value_11 = $data['distance'];
            if (is_float($data['distance'])) {
                $value_11 = $data['distance'];
            } elseif (is_null($data['distance'])) {
                $value_11 = $data['distance'];
            }
            $object->distance = $value_11;
            unset($data['distance']);
        }
        elseif (\array_key_exists('distance', $data)) {
            $object->distance = null;
            unset($data['distance']);
        }
        if (\array_key_exists('occupancy', $data) && $data['occupancy'] !== null) {
            $value_12 = $data['occupancy'];
            if (is_array($data['occupancy'])) {
                $value_12 = $this->denormalizer->denormalize($data['occupancy'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsOccupancy::class, 'json', $context);
            } elseif (is_null($data['occupancy'])) {
                $value_12 = $data['occupancy'];
            }
            $object->occupancy = $value_12;
            unset($data['occupancy']);
        }
        elseif (\array_key_exists('occupancy', $data)) {
            $object->occupancy = null;
            unset($data['occupancy']);
        }
        if (\array_key_exists('occupancy_today', $data) && $data['occupancy_today'] !== null) {
            $value_13 = $data['occupancy_today'];
            if (is_float($data['occupancy_today'])) {
                $value_13 = $data['occupancy_today'];
            } elseif (is_null($data['occupancy_today'])) {
                $value_13 = $data['occupancy_today'];
            }
            $object->occupancyToday = $value_13;
            unset($data['occupancy_today']);
        }
        elseif (\array_key_exists('occupancy_today', $data)) {
            $object->occupancyToday = null;
            unset($data['occupancy_today']);
        }
        if (\array_key_exists('default_drop_off_point', $data) && $data['default_drop_off_point'] !== null) {
            $value_14 = $data['default_drop_off_point'];
            if (is_bool($data['default_drop_off_point'])) {
                $value_14 = $data['default_drop_off_point'];
            } elseif (is_null($data['default_drop_off_point'])) {
                $value_14 = $data['default_drop_off_point'];
            }
            $object->defaultDropOffPoint = $value_14;
            unset($data['default_drop_off_point']);
        }
        elseif (\array_key_exists('default_drop_off_point', $data)) {
            $object->defaultDropOffPoint = null;
            unset($data['default_drop_off_point']);
        }
        if (\array_key_exists('type', $data) && $data['type'] !== null) {
            $value_15 = $data['type'];
            if (is_string($data['type'])) {
                $value_15 = \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentLocationType::tryFrom($data['type']) ?? $data['type'];
            } elseif (is_null($data['type'])) {
                $value_15 = $data['type'];
            }
            $object->type = $value_15;
            unset($data['type']);
        }
        elseif (\array_key_exists('type', $data)) {
            $object->type = null;
            unset($data['type']);
        }
        if (\array_key_exists('opening_hours', $data) && $data['opening_hours'] !== null) {
            $object->openingHours = $this->denormalizer->denormalize($data['opening_hours'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesDropOffPointDefinitionsOpeningHours::class, 'json', $context);
            unset($data['opening_hours']);
        }
        elseif (\array_key_exists('opening_hours', $data)) {
            $object->openingHours = null;
            unset($data['opening_hours']);
        }
        foreach ($data as $key => $value_16) {
            if (preg_match('/.*/', (string) $key)) {
                $object[$key] = $value_16;
            }
        }
        return $object;
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $dataArray = [];
        if (array_key_exists('locationCode', get_object_vars($data))) {
            $dataArray['location_code'] = $data->locationCode;
        }
        if (array_key_exists('locationName', get_object_vars($data))) {
            $value = $data->locationName;
            if (is_string($data->locationName)) {
                $value = $data->locationName;
            } elseif (is_null($data->locationName)) {
                $value = $data->locationName;
            }
            $dataArray['location_name'] = $value;
        }
        if (array_key_exists('cc', get_object_vars($data))) {
            $dataArray['cc'] = $data->cc;
        }
        if (array_key_exists('state', get_object_vars($data))) {
            $value_1 = $data->state;
            if (is_string($data->state)) {
                $value_1 = $data->state;
            } elseif (is_null($data->state)) {
                $value_1 = $data->state;
            }
            $dataArray['state'] = $value_1;
        }
        if (array_key_exists('city', get_object_vars($data))) {
            $dataArray['city'] = $data->city;
        }
        if (array_key_exists('postalCode', get_object_vars($data))) {
            $dataArray['postal_code'] = $data->postalCode;
        }
        if (array_key_exists('street', get_object_vars($data))) {
            $dataArray['street'] = $data->street;
        }
        if (array_key_exists('number', get_object_vars($data))) {
            $value_2 = $data->number;
            if (is_string($data->number)) {
                $value_2 = $data->number;
            } elseif (is_int($data->number)) {
                $value_2 = $data->number;
            } elseif (is_null($data->number)) {
                $value_2 = $data->number;
            }
            $dataArray['number'] = $value_2;
        }
        if (array_key_exists('numberSuffix', get_object_vars($data))) {
            $value_3 = $data->numberSuffix;
            if (is_string($data->numberSuffix)) {
                $value_3 = $data->numberSuffix;
            } elseif (is_null($data->numberSuffix)) {
                $value_3 = $data->numberSuffix;
            }
            $dataArray['number_suffix'] = $value_3;
        }
        if (array_key_exists('phone', get_object_vars($data))) {
            $value_4 = $data->phone;
            if (is_string($data->phone)) {
                $value_4 = $data->phone;
            } elseif (is_null($data->phone)) {
                $value_4 = $data->phone;
            }
            $dataArray['phone'] = $value_4;
        }
        if (array_key_exists('reference', get_object_vars($data))) {
            $value_5 = $data->reference;
            if (is_string($data->reference)) {
                $value_5 = $data->reference;
            } elseif (is_null($data->reference)) {
                $value_5 = $data->reference;
            }
            $dataArray['reference'] = $value_5;
        }
        if (array_key_exists('longitude', get_object_vars($data))) {
            $value_6 = $data->longitude;
            if (is_float($data->longitude)) {
                $value_6 = $data->longitude;
            } elseif (is_string($data->longitude)) {
                $value_6 = $data->longitude;
            } elseif (is_null($data->longitude)) {
                $value_6 = $data->longitude;
            }
            $dataArray['longitude'] = $value_6;
        }
        if (array_key_exists('latitude', get_object_vars($data))) {
            $value_7 = $data->latitude;
            if (is_float($data->latitude)) {
                $value_7 = $data->latitude;
            } elseif (is_string($data->latitude)) {
                $value_7 = $data->latitude;
            } elseif (is_null($data->latitude)) {
                $value_7 = $data->latitude;
            }
            $dataArray['latitude'] = $value_7;
        }
        if (array_key_exists('availableDays', get_object_vars($data))) {
            $values = [];
            foreach ($data->availableDays as $value_8) {
                $value_9 = $value_8;
                if ($value_8 instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonDefsDayOfWeek || is_int($value_8)) {
                    $value_9 = $value_8 instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonDefsDayOfWeek ? $value_8->value : $value_8;
                } elseif ($value_8 instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonDefsIsoDayOfWeek || is_int($value_8)) {
                    $value_9 = $value_8 instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonDefsIsoDayOfWeek ? $value_8->value : $value_8;
                }
                $values[] = $value_9;
            }
            $dataArray['available_days'] = $values;
        }
        if (array_key_exists('cutOffTime', get_object_vars($data))) {
            $value_10 = $data->cutOffTime;
            if (is_string($data->cutOffTime)) {
                $value_10 = $data->cutOffTime;
            } elseif (is_null($data->cutOffTime)) {
                $value_10 = $data->cutOffTime;
            }
            $dataArray['cut_off_time'] = $value_10;
        }
        if (array_key_exists('carrierId', get_object_vars($data))) {
            $dataArray['carrier_id'] = $data->carrierId instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesCarrier ? $data->carrierId->value : $data->carrierId;
        }
        if (array_key_exists('distance', get_object_vars($data))) {
            $value_11 = $data->distance;
            if (is_float($data->distance)) {
                $value_11 = $data->distance;
            } elseif (is_null($data->distance)) {
                $value_11 = $data->distance;
            }
            $dataArray['distance'] = $value_11;
        }
        if (array_key_exists('occupancy', get_object_vars($data))) {
            $value_12 = $data->occupancy;
            if (is_object($data->occupancy)) {
                $normalized = $this->normalizer->normalize($data->occupancy, 'json', $context);
                $value_12 = \is_iterable($normalized) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized) : $normalized;
            } elseif (is_null($data->occupancy)) {
                $value_12 = $data->occupancy;
            }
            $dataArray['occupancy'] = $value_12;
        }
        if (array_key_exists('occupancyToday', get_object_vars($data))) {
            $value_13 = $data->occupancyToday;
            if (is_float($data->occupancyToday)) {
                $value_13 = $data->occupancyToday;
            } elseif (is_null($data->occupancyToday)) {
                $value_13 = $data->occupancyToday;
            }
            $dataArray['occupancy_today'] = $value_13;
        }
        if (array_key_exists('defaultDropOffPoint', get_object_vars($data))) {
            $value_14 = $data->defaultDropOffPoint;
            if (is_bool($data->defaultDropOffPoint)) {
                $value_14 = $data->defaultDropOffPoint;
            } elseif (is_null($data->defaultDropOffPoint)) {
                $value_14 = $data->defaultDropOffPoint;
            }
            $dataArray['default_drop_off_point'] = $value_14;
        }
        if (array_key_exists('type', get_object_vars($data))) {
            $value_15 = $data->type;
            if ($data->type instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentLocationType || is_string($data->type)) {
                $value_15 = $data->type instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentLocationType ? $data->type->value : $data->type;
            } elseif (is_null($data->type)) {
                $value_15 = $data->type;
            }
            $dataArray['type'] = $value_15;
        }
        if (array_key_exists('openingHours', get_object_vars($data))) {
            $normalized_1 = $data->openingHours === null ? null : $this->normalizer->normalize($data->openingHours, 'json', $context);
            $dataArray['opening_hours'] = \is_iterable($normalized_1) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_1) : $normalized_1;
        }
        foreach ($data->additionalPropertyEntries() as $key => $value_16) {
            if (preg_match('/.*/', (string) $key)) {
                $dataArray[$key] = $value_16;
            }
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\ShipmentResponsesDropOffPointConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesDropOffPoint::class => false];
    }
}