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
class RefCapabilitiesResponsePhysicalPropertiesPhysicalPropertiesV1Normalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponsePhysicalPropertiesPhysicalPropertiesV1::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponsePhysicalPropertiesPhysicalPropertiesV1::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponsePhysicalPropertiesPhysicalPropertiesV1();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\RefCapabilitiesResponsePhysicalPropertiesPhysicalPropertiesV1Constraint());
        }
        if (\array_key_exists('weight', $data)) {
            $object->weight = $this->denormalizer->denormalize($data['weight'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponsePhysicalPropertiesPhysicalProperty::class, 'json', $context);
            unset($data['weight']);
        }
        if (\array_key_exists('width', $data)) {
            $object->width = $this->denormalizer->denormalize($data['width'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponsePhysicalPropertiesPhysicalProperty::class, 'json', $context);
            unset($data['width']);
        }
        if (\array_key_exists('length', $data)) {
            $object->length = $this->denormalizer->denormalize($data['length'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponsePhysicalPropertiesPhysicalProperty::class, 'json', $context);
            unset($data['length']);
        }
        if (\array_key_exists('height', $data)) {
            $object->height = $this->denormalizer->denormalize($data['height'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponsePhysicalPropertiesPhysicalProperty::class, 'json', $context);
            unset($data['height']);
        }
        if (\array_key_exists('girth', $data)) {
            $object->girth = $this->denormalizer->denormalize($data['girth'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponsePhysicalPropertiesPhysicalProperty::class, 'json', $context);
            unset($data['girth']);
        }
        if (\array_key_exists('volumetric_weight', $data)) {
            $object->volumetricWeight = $this->denormalizer->denormalize($data['volumetric_weight'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponsePhysicalPropertiesPhysicalProperty::class, 'json', $context);
            unset($data['volumetric_weight']);
        }
        if (\array_key_exists('volume', $data)) {
            $object->volume = $this->denormalizer->denormalize($data['volume'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponsePhysicalPropertiesPhysicalProperty::class, 'json', $context);
            unset($data['volume']);
        }
        if (\array_key_exists('length_width_height_sum', $data)) {
            $object->lengthWidthHeightSum = $this->denormalizer->denormalize($data['length_width_height_sum'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponsePhysicalPropertiesPhysicalProperty::class, 'json', $context);
            unset($data['length_width_height_sum']);
        }
        foreach ($data as $key => $value) {
            if (preg_match('/.*/', (string) $key)) {
                $object[$key] = $value;
            }
        }
        return $object;
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $dataArray = [];
        if (array_key_exists('weight', get_object_vars($data))) {
            $normalized = $data->weight === null ? null : $this->normalizer->normalize($data->weight, 'json', $context);
            $dataArray['weight'] = \is_iterable($normalized) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized) : $normalized;
        }
        if (array_key_exists('width', get_object_vars($data))) {
            $normalized_1 = $data->width === null ? null : $this->normalizer->normalize($data->width, 'json', $context);
            $dataArray['width'] = \is_iterable($normalized_1) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_1) : $normalized_1;
        }
        if (array_key_exists('length', get_object_vars($data))) {
            $normalized_2 = $data->length === null ? null : $this->normalizer->normalize($data->length, 'json', $context);
            $dataArray['length'] = \is_iterable($normalized_2) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_2) : $normalized_2;
        }
        if (array_key_exists('height', get_object_vars($data))) {
            $normalized_3 = $data->height === null ? null : $this->normalizer->normalize($data->height, 'json', $context);
            $dataArray['height'] = \is_iterable($normalized_3) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_3) : $normalized_3;
        }
        if (array_key_exists('girth', get_object_vars($data))) {
            $normalized_4 = $data->girth === null ? null : $this->normalizer->normalize($data->girth, 'json', $context);
            $dataArray['girth'] = \is_iterable($normalized_4) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_4) : $normalized_4;
        }
        if (array_key_exists('volumetricWeight', get_object_vars($data))) {
            $normalized_5 = $data->volumetricWeight === null ? null : $this->normalizer->normalize($data->volumetricWeight, 'json', $context);
            $dataArray['volumetric_weight'] = \is_iterable($normalized_5) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_5) : $normalized_5;
        }
        if (array_key_exists('volume', get_object_vars($data))) {
            $normalized_6 = $data->volume === null ? null : $this->normalizer->normalize($data->volume, 'json', $context);
            $dataArray['volume'] = \is_iterable($normalized_6) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_6) : $normalized_6;
        }
        if (array_key_exists('lengthWidthHeightSum', get_object_vars($data))) {
            $normalized_7 = $data->lengthWidthHeightSum === null ? null : $this->normalizer->normalize($data->lengthWidthHeightSum, 'json', $context);
            $dataArray['length_width_height_sum'] = \is_iterable($normalized_7) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_7) : $normalized_7;
        }
        foreach ($data->additionalPropertyEntries() as $key => $value) {
            if (preg_match('/.*/', (string) $key)) {
                $dataArray[$key] = $value;
            }
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\RefCapabilitiesResponsePhysicalPropertiesPhysicalPropertiesV1Constraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponsePhysicalPropertiesPhysicalPropertiesV1::class => false];
    }
}