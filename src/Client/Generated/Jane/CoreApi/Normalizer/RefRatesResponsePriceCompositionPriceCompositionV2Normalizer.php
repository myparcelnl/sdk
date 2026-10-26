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
class RefRatesResponsePriceCompositionPriceCompositionV2Normalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefRatesResponsePriceCompositionPriceCompositionV2::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefRatesResponsePriceCompositionPriceCompositionV2::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefRatesResponsePriceCompositionPriceCompositionV2();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\RefRatesResponsePriceCompositionPriceCompositionV2Constraint());
        }
        if (\array_key_exists('base', $data)) {
            $object->base = $this->denormalizer->denormalize($data['base'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefRatesResponsePriceCompositionPriceV2::class, 'json', $context);
        }
        if (\array_key_exists('charges', $data)) {
            $values = [];
            foreach ($data['charges'] as $value) {
                $values[] = $this->denormalizer->denormalize($value, \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefRatesResponsePriceCompositionPriceV2::class, 'json', $context);
            }
            $object->charges = $values;
        }
        if (\array_key_exists('discounts', $data)) {
            $values_1 = [];
            foreach ($data['discounts'] as $value_1) {
                $values_1[] = $this->denormalizer->denormalize($value_1, \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefRatesResponsePriceCompositionPriceV2::class, 'json', $context);
            }
            $object->discounts = $values_1;
        }
        if (\array_key_exists('options', $data)) {
            $values_2 = [];
            foreach ($data['options'] as $value_2) {
                $values_2[] = $this->denormalizer->denormalize($value_2, \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefRatesResponsePriceCompositionPriceV2::class, 'json', $context);
            }
            $object->options = $values_2;
        }
        if (\array_key_exists('other', $data)) {
            $values_3 = [];
            foreach ($data['other'] as $value_3) {
                $values_3[] = $this->denormalizer->denormalize($value_3, \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefRatesResponsePriceCompositionPriceV2::class, 'json', $context);
            }
            $object->other = $values_3;
        }
        if (\array_key_exists('total', $data)) {
            $object->total = $this->denormalizer->denormalize($data['total'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefRatesResponsePriceCompositionPriceV2::class, 'json', $context);
        }
        return $object;
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $dataArray = [];
        if (null !== ($data->base ?? null)) {
            $normalized = $this->normalizer->normalize($data->base, 'json', $context);
            $dataArray['base'] = \is_iterable($normalized) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized) : $normalized;
        }
        if (null !== ($data->charges ?? null)) {
            $values = [];
            foreach ($data->charges as $value) {
                $normalized_1 = $value === null ? null : $this->normalizer->normalize($value, 'json', $context);
                $values[] = \is_iterable($normalized_1) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_1) : $normalized_1;
            }
            $dataArray['charges'] = $values;
        }
        if (null !== ($data->discounts ?? null)) {
            $values_1 = [];
            foreach ($data->discounts as $value_1) {
                $normalized_2 = $value_1 === null ? null : $this->normalizer->normalize($value_1, 'json', $context);
                $values_1[] = \is_iterable($normalized_2) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_2) : $normalized_2;
            }
            $dataArray['discounts'] = $values_1;
        }
        if (null !== ($data->options ?? null)) {
            $values_2 = [];
            foreach ($data->options as $value_2) {
                $normalized_3 = $value_2 === null ? null : $this->normalizer->normalize($value_2, 'json', $context);
                $values_2[] = \is_iterable($normalized_3) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_3) : $normalized_3;
            }
            $dataArray['options'] = $values_2;
        }
        if (null !== ($data->other ?? null)) {
            $values_3 = [];
            foreach ($data->other as $value_3) {
                $normalized_4 = $value_3 === null ? null : $this->normalizer->normalize($value_3, 'json', $context);
                $values_3[] = \is_iterable($normalized_4) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_4) : $normalized_4;
            }
            $dataArray['other'] = $values_3;
        }
        if (null !== ($data->total ?? null)) {
            $normalized_5 = $this->normalizer->normalize($data->total, 'json', $context);
            $dataArray['total'] = \is_iterable($normalized_5) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_5) : $normalized_5;
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\RefRatesResponsePriceCompositionPriceCompositionV2Constraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefRatesResponsePriceCompositionPriceCompositionV2::class => false];
    }
}