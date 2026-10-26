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
class ShipmentResponsesDeliveryOptionsDeliveryPossibilityNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesDeliveryOptionsDeliveryPossibility::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesDeliveryOptionsDeliveryPossibility::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesDeliveryOptionsDeliveryPossibility();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\ShipmentResponsesDeliveryOptionsDeliveryPossibilityConstraint());
        }
        if (\array_key_exists('type', $data) && $data['type'] !== null) {
            $value = $data['type'];
            if (is_string($data['type'])) {
                $value = \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsDeliveryOptionsDeliveryNameV2::tryFrom($data['type']) ?? $data['type'];
            } elseif (is_null($data['type'])) {
                $value = $data['type'];
            }
            $object->type = $value;
        }
        elseif (\array_key_exists('type', $data)) {
            $object->type = null;
        }
        if (\array_key_exists('package_type', $data)) {
            $object->packageType = is_string($data['package_type']) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesDeliveryOptionsPackageTypeV2::tryFrom($data['package_type']) ?? $data['package_type'] : $data['package_type'];
        }
        if (\array_key_exists('delivery_time_frames', $data)) {
            $values = [];
            foreach ($data['delivery_time_frames'] as $value_1) {
                $values[] = $this->denormalizer->denormalize($value_1, \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsDeliveryOptionsDeliveryTimeFrame::class, 'json', $context);
            }
            $object->deliveryTimeFrames = $values;
        }
        if (\array_key_exists('shipment_options', $data)) {
            $values_1 = [];
            foreach ($data['shipment_options'] as $value_2) {
                $values_1[] = $this->denormalizer->denormalize($value_2, \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesDeliveryOptionsDeliveryPossibilityShipmentOptionsItem::class, 'json', $context);
            }
            $object->shipmentOptions = $values_1;
        }
        if (\array_key_exists('price', $data)) {
            $object->price = $this->denormalizer->denormalize($data['price'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonDefsPrice::class, 'json', $context);
        }
        if (\array_key_exists('identifier', $data)) {
            $object->identifier = $data['identifier'];
        }
        if (\array_key_exists('transporter', $data)) {
            $object->transporter = $data['transporter'];
        }
        if (\array_key_exists('label_type', $data)) {
            $object->labelType = $data['label_type'];
        }
        return $object;
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $dataArray = [];
        if (null !== ($data->type ?? null)) {
            $value = $data->type;
            if ($data->type instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsDeliveryOptionsDeliveryNameV2 || is_string($data->type)) {
                $value = $data->type instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsDeliveryOptionsDeliveryNameV2 ? $data->type->value : $data->type;
            } elseif (is_null($data->type)) {
                $value = $data->type;
            }
            $dataArray['type'] = $value;
        }
        if (null !== ($data->packageType ?? null)) {
            $dataArray['package_type'] = $data->packageType instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesDeliveryOptionsPackageTypeV2 ? $data->packageType->value : $data->packageType;
        }
        if (null !== ($data->deliveryTimeFrames ?? null)) {
            $values = [];
            foreach ($data->deliveryTimeFrames as $value_1) {
                $normalized = $value_1 === null ? null : $this->normalizer->normalize($value_1, 'json', $context);
                $values[] = \is_iterable($normalized) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized) : $normalized;
            }
            $dataArray['delivery_time_frames'] = $values;
        }
        if (null !== ($data->shipmentOptions ?? null)) {
            $values_1 = [];
            foreach ($data->shipmentOptions as $value_2) {
                $normalized_1 = $value_2 === null ? null : $this->normalizer->normalize($value_2, 'json', $context);
                $values_1[] = \is_iterable($normalized_1) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_1) : $normalized_1;
            }
            $dataArray['shipment_options'] = $values_1;
        }
        if (array_key_exists('price', get_object_vars($data))) {
            $normalized_2 = $data->price === null ? null : $this->normalizer->normalize($data->price, 'json', $context);
            $dataArray['price'] = \is_iterable($normalized_2) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_2) : $normalized_2;
        }
        if (array_key_exists('identifier', get_object_vars($data))) {
            $dataArray['identifier'] = $data->identifier;
        }
        if (array_key_exists('transporter', get_object_vars($data))) {
            $dataArray['transporter'] = $data->transporter;
        }
        if (array_key_exists('labelType', get_object_vars($data))) {
            $dataArray['label_type'] = $data->labelType;
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\ShipmentResponsesDeliveryOptionsDeliveryPossibilityConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesDeliveryOptionsDeliveryPossibility::class => false];
    }
}