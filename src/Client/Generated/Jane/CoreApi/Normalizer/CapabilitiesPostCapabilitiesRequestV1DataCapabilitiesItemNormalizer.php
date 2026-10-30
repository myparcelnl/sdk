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
class CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItem::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItem::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItem();
        if (null === $data || false === \is_array($data)) {
            return $object;
        }
        if (isset($data['$ref']) && !isset($data['type']) && !isset($data['properties']) && !isset($data['allOf'])) {
            return new Reference($data['$ref'], $context['document-origin']);
        }
        if (isset($data['$recursiveRef'])) {
            return new Reference($data['$recursiveRef'], $context['document-origin']);
        }
        if (\array_key_exists('package_type', $data) && \is_int($data['package_type'])) {
            $data['package_type'] = (float) $data['package_type'];
        }
        if (\array_key_exists('delivery_type', $data) && \is_int($data['delivery_type'])) {
            $data['delivery_type'] = (float) $data['delivery_type'];
        }
        if (\array_key_exists('shipment_type', $data) && \is_int($data['shipment_type'])) {
            $data['shipment_type'] = (float) $data['shipment_type'];
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemConstraint());
        }
        if (\array_key_exists('shop_id', $data)) {
            $object->shopId = $data['shop_id'];
        }
        if (\array_key_exists('contract_id', $data)) {
            $object->contractId = $data['contract_id'];
        }
        if (\array_key_exists('recipient', $data)) {
            $object->recipient = $this->denormalizer->denormalize($data['recipient'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemRecipient::class, 'json', $context);
        }
        if (\array_key_exists('sender', $data)) {
            $object->sender = $this->denormalizer->denormalize($data['sender'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemSender::class, 'json', $context);
        }
        if (\array_key_exists('carrier_id', $data)) {
            $object->carrierId = is_int($data['carrier_id']) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedCarrier::tryFrom($data['carrier_id']) ?? $data['carrier_id'] : $data['carrier_id'];
        }
        if (\array_key_exists('package_type', $data)) {
            $object->packageType = $data['package_type'];
        }
        if (\array_key_exists('physical_properties', $data)) {
            $object->physicalProperties = $this->denormalizer->denormalize($data['physical_properties'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemPhysicalProperties::class, 'json', $context);
        }
        if (\array_key_exists('options', $data)) {
            $object->options = $this->denormalizer->denormalize($data['options'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemOptions::class, 'json', $context);
        }
        if (\array_key_exists('delivery_type', $data)) {
            $object->deliveryType = $data['delivery_type'];
        }
        if (\array_key_exists('shipment_type', $data)) {
            $object->shipmentType = $data['shipment_type'];
        }
        if (\array_key_exists('direction', $data)) {
            $object->direction = is_string($data['direction']) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemDirection::tryFrom($data['direction']) ?? $data['direction'] : $data['direction'];
        }
        if (\array_key_exists('pickup', $data)) {
            $object->pickup = $this->denormalizer->denormalize($data['pickup'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemPickup::class, 'json', $context);
        }
        return $object;
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $dataArray = [];
        if (array_key_exists('shopId', get_object_vars($data))) {
            $dataArray['shop_id'] = $data->shopId;
        }
        if (array_key_exists('contractId', get_object_vars($data))) {
            $dataArray['contract_id'] = $data->contractId;
        }
        if (null !== ($data->recipient ?? null)) {
            $normalized = $this->normalizer->normalize($data->recipient, 'json', $context);
            $dataArray['recipient'] = \is_iterable($normalized) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized) : $normalized;
        }
        if (array_key_exists('sender', get_object_vars($data))) {
            $normalized_1 = $data->sender === null ? null : $this->normalizer->normalize($data->sender, 'json', $context);
            $dataArray['sender'] = \is_iterable($normalized_1) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_1) : $normalized_1;
        }
        if (array_key_exists('carrierId', get_object_vars($data))) {
            $dataArray['carrier_id'] = $data->carrierId instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedCarrier ? $data->carrierId->value : $data->carrierId;
        }
        if (array_key_exists('packageType', get_object_vars($data))) {
            $dataArray['package_type'] = $data->packageType;
        }
        if (array_key_exists('physicalProperties', get_object_vars($data))) {
            $normalized_2 = $data->physicalProperties === null ? null : $this->normalizer->normalize($data->physicalProperties, 'json', $context);
            $dataArray['physical_properties'] = \is_iterable($normalized_2) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_2) : $normalized_2;
        }
        if (array_key_exists('options', get_object_vars($data))) {
            $normalized_3 = $data->options === null ? null : $this->normalizer->normalize($data->options, 'json', $context);
            $dataArray['options'] = \is_iterable($normalized_3) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_3) : $normalized_3;
        }
        if (array_key_exists('deliveryType', get_object_vars($data))) {
            $dataArray['delivery_type'] = $data->deliveryType;
        }
        if (array_key_exists('shipmentType', get_object_vars($data))) {
            $dataArray['shipment_type'] = $data->shipmentType;
        }
        if (array_key_exists('direction', get_object_vars($data))) {
            $dataArray['direction'] = $data->direction instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemDirection ? $data->direction->value : $data->direction;
        }
        if (array_key_exists('pickup', get_object_vars($data))) {
            $normalized_4 = $data->pickup === null ? null : $this->normalizer->normalize($data->pickup, 'json', $context);
            $dataArray['pickup'] = \is_iterable($normalized_4) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_4) : $normalized_4;
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItem::class => false];
    }
}