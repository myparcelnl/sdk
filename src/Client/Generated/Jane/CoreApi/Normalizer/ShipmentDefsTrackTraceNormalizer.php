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
class ShipmentDefsTrackTraceNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsTrackTrace::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsTrackTrace::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsTrackTrace();
        if (null === $data || false === \is_array($data)) {
            return $object;
        }
        if (isset($data['$ref']) && !isset($data['type']) && !isset($data['properties']) && !isset($data['allOf'])) {
            return new Reference($data['$ref'], $context['document-origin']);
        }
        if (isset($data['$recursiveRef'])) {
            return new Reference($data['$recursiveRef'], $context['document-origin']);
        }
        if (\array_key_exists('delayed', $data) && \is_int($data['delayed'])) {
            $data['delayed'] = (bool) $data['delayed'];
        }
        if (\array_key_exists('returnable', $data) && \is_int($data['returnable'])) {
            $data['returnable'] = (bool) $data['returnable'];
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\ShipmentDefsTrackTraceConstraint());
        }
        if (\array_key_exists('shipment_id', $data)) {
            $object->shipmentId = $data['shipment_id'];
        }
        if (\array_key_exists('carrier', $data)) {
            $object->carrier = is_int($data['carrier']) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesCarrier::tryFrom($data['carrier']) ?? $data['carrier'] : $data['carrier'];
        }
        if (\array_key_exists('code', $data)) {
            $object->code = $data['code'];
        }
        if (\array_key_exists('description', $data)) {
            $object->description = $data['description'];
        }
        if (\array_key_exists('time', $data)) {
            $object->time = $data['time'];
        }
        if (\array_key_exists('link_consumer_portal', $data) && $data['link_consumer_portal'] !== null) {
            $value = $data['link_consumer_portal'];
            if (is_string($data['link_consumer_portal'])) {
                $value = $data['link_consumer_portal'];
            } elseif (is_null($data['link_consumer_portal'])) {
                $value = $data['link_consumer_portal'];
            }
            $object->linkConsumerPortal = $value;
        }
        elseif (\array_key_exists('link_consumer_portal', $data)) {
            $object->linkConsumerPortal = null;
        }
        if (\array_key_exists('link_tracktrace', $data) && $data['link_tracktrace'] !== null) {
            $value_1 = $data['link_tracktrace'];
            if (is_string($data['link_tracktrace'])) {
                $value_1 = $data['link_tracktrace'];
            } elseif (is_null($data['link_tracktrace'])) {
                $value_1 = $data['link_tracktrace'];
            }
            $object->linkTracktrace = $value_1;
        }
        elseif (\array_key_exists('link_tracktrace', $data)) {
            $object->linkTracktrace = null;
        }
        if (\array_key_exists('partner_tracktraces', $data)) {
            $values = [];
            foreach ($data['partner_tracktraces'] as $value_2) {
                $values[] = $this->denormalizer->denormalize($value_2, \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsExternalTrackTraceLink::class, 'json', $context);
            }
            $object->partnerTracktraces = $values;
        }
        if (\array_key_exists('recipient', $data)) {
            $object->recipient = $this->denormalizer->denormalize($data['recipient'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentRecipient::class, 'json', $context);
        }
        if (\array_key_exists('sender', $data)) {
            $object->sender = $this->denormalizer->denormalize($data['sender'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentSender::class, 'json', $context);
        }
        if (\array_key_exists('options', $data)) {
            $object->options = $this->denormalizer->denormalize($data['options'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentShipmentOptions::class, 'json', $context);
        }
        if (\array_key_exists('pickup', $data) && $data['pickup'] !== null) {
            $object->pickup = $this->denormalizer->denormalize($data['pickup'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentPickup::class, 'json', $context);
        }
        elseif (\array_key_exists('pickup', $data)) {
            $object->pickup = null;
        }
        if (\array_key_exists('delayed', $data)) {
            $object->delayed = $data['delayed'];
        }
        if (\array_key_exists('location', $data)) {
            $object->location = $this->denormalizer->denormalize($data['location'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsTrackingLocation::class, 'json', $context);
        }
        if (\array_key_exists('status', $data)) {
            $object->status = $this->denormalizer->denormalize($data['status'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsTrackTraceStatus::class, 'json', $context);
        }
        if (\array_key_exists('history', $data)) {
            $values_1 = [];
            foreach ($data['history'] as $value_3) {
                $values_1[] = $this->denormalizer->denormalize($value_3, \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsCarrierTrackTraceEvent::class, 'json', $context);
            }
            $object->history = $values_1;
        }
        if (\array_key_exists('delivery_moment_type', $data) && $data['delivery_moment_type'] !== null) {
            $value_4 = $data['delivery_moment_type'];
            if (is_string($data['delivery_moment_type'])) {
                $value_4 = $data['delivery_moment_type'];
            } elseif (is_null($data['delivery_moment_type'])) {
                $value_4 = $data['delivery_moment_type'];
            }
            $object->deliveryMomentType = $value_4;
        }
        elseif (\array_key_exists('delivery_moment_type', $data)) {
            $object->deliveryMomentType = null;
        }
        if (\array_key_exists('delivery_moment', $data) && $data['delivery_moment'] !== null) {
            $object->deliveryMoment = $this->denormalizer->denormalize($data['delivery_moment'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsDeliveryMomentPropertiesTimeFrame::class, 'json', $context);
        }
        elseif (\array_key_exists('delivery_moment', $data)) {
            $object->deliveryMoment = null;
        }
        if (\array_key_exists('signature', $data) && $data['signature'] !== null) {
            $object->signature = $data['signature'];
        }
        elseif (\array_key_exists('signature', $data)) {
            $object->signature = null;
        }
        if (\array_key_exists('returnable', $data)) {
            $object->returnable = $data['returnable'];
        }
        if (\array_key_exists('last_mile_carrier_name', $data) && $data['last_mile_carrier_name'] !== null) {
            $value_5 = $data['last_mile_carrier_name'];
            if (is_string($data['last_mile_carrier_name'])) {
                $value_5 = $data['last_mile_carrier_name'];
            } elseif (is_null($data['last_mile_carrier_name'])) {
                $value_5 = $data['last_mile_carrier_name'];
            }
            $object->lastMileCarrierName = $value_5;
        }
        elseif (\array_key_exists('last_mile_carrier_name', $data)) {
            $object->lastMileCarrierName = null;
        }
        return $object;
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $dataArray = [];
        if (null !== ($data->shipmentId ?? null)) {
            $dataArray['shipment_id'] = $data->shipmentId;
        }
        if (null !== ($data->carrier ?? null)) {
            $dataArray['carrier'] = $data->carrier instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesCarrier ? $data->carrier->value : $data->carrier;
        }
        if (null !== ($data->code ?? null)) {
            $dataArray['code'] = $data->code;
        }
        if (null !== ($data->description ?? null)) {
            $dataArray['description'] = $data->description;
        }
        if (null !== ($data->time ?? null)) {
            $dataArray['time'] = $data->time;
        }
        if (null !== ($data->linkConsumerPortal ?? null)) {
            $value = $data->linkConsumerPortal;
            if (is_string($data->linkConsumerPortal)) {
                $value = $data->linkConsumerPortal;
            } elseif (is_null($data->linkConsumerPortal)) {
                $value = $data->linkConsumerPortal;
            }
            $dataArray['link_consumer_portal'] = $value;
        }
        if (null !== ($data->linkTracktrace ?? null)) {
            $value_1 = $data->linkTracktrace;
            if (is_string($data->linkTracktrace)) {
                $value_1 = $data->linkTracktrace;
            } elseif (is_null($data->linkTracktrace)) {
                $value_1 = $data->linkTracktrace;
            }
            $dataArray['link_tracktrace'] = $value_1;
        }
        if (null !== ($data->partnerTracktraces ?? null)) {
            $values = [];
            foreach ($data->partnerTracktraces as $value_2) {
                $normalized = $value_2 === null ? null : $this->normalizer->normalize($value_2, 'json', $context);
                $values[] = \is_iterable($normalized) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized) : $normalized;
            }
            $dataArray['partner_tracktraces'] = $values;
        }
        if (null !== ($data->recipient ?? null)) {
            $normalized_1 = $this->normalizer->normalize($data->recipient, 'json', $context);
            $dataArray['recipient'] = \is_iterable($normalized_1) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_1) : $normalized_1;
        }
        if (null !== ($data->sender ?? null)) {
            $normalized_2 = $this->normalizer->normalize($data->sender, 'json', $context);
            $dataArray['sender'] = \is_iterable($normalized_2) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_2) : $normalized_2;
        }
        if (null !== ($data->options ?? null)) {
            $normalized_3 = $this->normalizer->normalize($data->options, 'json', $context);
            $dataArray['options'] = \is_iterable($normalized_3) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_3) : $normalized_3;
        }
        if (null !== ($data->pickup ?? null)) {
            $normalized_4 = $this->normalizer->normalize($data->pickup, 'json', $context);
            $dataArray['pickup'] = \is_iterable($normalized_4) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_4) : $normalized_4;
        }
        if (null !== ($data->delayed ?? null)) {
            $dataArray['delayed'] = $data->delayed;
        }
        if (null !== ($data->location ?? null)) {
            $normalized_5 = $this->normalizer->normalize($data->location, 'json', $context);
            $dataArray['location'] = \is_iterable($normalized_5) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_5) : $normalized_5;
        }
        if (null !== ($data->status ?? null)) {
            $normalized_6 = $this->normalizer->normalize($data->status, 'json', $context);
            $dataArray['status'] = \is_iterable($normalized_6) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_6) : $normalized_6;
        }
        if (null !== ($data->history ?? null)) {
            $values_1 = [];
            foreach ($data->history as $value_3) {
                $normalized_7 = $value_3 === null ? null : $this->normalizer->normalize($value_3, 'json', $context);
                $values_1[] = \is_iterable($normalized_7) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_7) : $normalized_7;
            }
            $dataArray['history'] = $values_1;
        }
        if (array_key_exists('deliveryMomentType', get_object_vars($data))) {
            $value_4 = $data->deliveryMomentType;
            if (is_string($data->deliveryMomentType)) {
                $value_4 = $data->deliveryMomentType;
            } elseif (is_null($data->deliveryMomentType)) {
                $value_4 = $data->deliveryMomentType;
            }
            $dataArray['delivery_moment_type'] = $value_4;
        }
        if (array_key_exists('deliveryMoment', get_object_vars($data))) {
            $normalized_8 = $data->deliveryMoment === null ? null : $this->normalizer->normalize($data->deliveryMoment, 'json', $context);
            $dataArray['delivery_moment'] = \is_iterable($normalized_8) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_8) : $normalized_8;
        }
        if (array_key_exists('signature', get_object_vars($data))) {
            $dataArray['signature'] = $data->signature;
        }
        if (array_key_exists('returnable', get_object_vars($data))) {
            $dataArray['returnable'] = $data->returnable;
        }
        if (array_key_exists('lastMileCarrierName', get_object_vars($data))) {
            $value_5 = $data->lastMileCarrierName;
            if (is_string($data->lastMileCarrierName)) {
                $value_5 = $data->lastMileCarrierName;
            } elseif (is_null($data->lastMileCarrierName)) {
                $value_5 = $data->lastMileCarrierName;
            }
            $dataArray['last_mile_carrier_name'] = $value_5;
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\ShipmentDefsTrackTraceConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsTrackTrace::class => false];
    }
}