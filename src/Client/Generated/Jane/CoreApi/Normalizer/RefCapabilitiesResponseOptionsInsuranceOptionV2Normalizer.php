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
class RefCapabilitiesResponseOptionsInsuranceOptionV2Normalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseOptionsInsuranceOptionV2::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseOptionsInsuranceOptionV2::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseOptionsInsuranceOptionV2();
        if (null === $data || false === \is_array($data)) {
            return $object;
        }
        if (isset($data['$ref']) && !isset($data['type']) && !isset($data['properties']) && !isset($data['allOf'])) {
            return new Reference($data['$ref'], $context['document-origin']);
        }
        if (isset($data['$recursiveRef'])) {
            return new Reference($data['$recursiveRef'], $context['document-origin']);
        }
        if (\array_key_exists('isSelectedByDefault', $data) && \is_int($data['isSelectedByDefault'])) {
            $data['isSelectedByDefault'] = (bool) $data['isSelectedByDefault'];
        }
        if (\array_key_exists('isRequired', $data) && \is_int($data['isRequired'])) {
            $data['isRequired'] = (bool) $data['isRequired'];
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\RefCapabilitiesResponseOptionsInsuranceOptionV2Constraint());
        }
        if (\array_key_exists('isSelectedByDefault', $data)) {
            $object->isSelectedByDefault = $data['isSelectedByDefault'];
            unset($data['isSelectedByDefault']);
        }
        if (\array_key_exists('isRequired', $data)) {
            $object->isRequired = $data['isRequired'];
            unset($data['isRequired']);
        }
        if (\array_key_exists('requires', $data)) {
            $values = [];
            foreach ($data['requires'] as $value) {
                $values[] = $value;
            }
            $object->requires = $values;
            unset($data['requires']);
        }
        if (\array_key_exists('excludes', $data)) {
            $values_1 = [];
            foreach ($data['excludes'] as $value_1) {
                $values_1[] = $value_1;
            }
            $object->excludes = $values_1;
            unset($data['excludes']);
        }
        if (\array_key_exists('insuredAmount', $data)) {
            $object->insuredAmount = $this->denormalizer->denormalize($data['insuredAmount'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedOptionsInsuranceBaseInsuranceV2InsuredAmount::class, 'json', $context);
            unset($data['insuredAmount']);
        }
        if (\array_key_exists('default', $data)) {
            $object->default = $this->denormalizer->denormalize($data['default'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesMoney::class, 'json', $context);
            unset($data['default']);
        }
        if (\array_key_exists('min', $data)) {
            $object->min = $this->denormalizer->denormalize($data['min'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesMoney::class, 'json', $context);
            unset($data['min']);
        }
        if (\array_key_exists('max', $data)) {
            $object->max = $this->denormalizer->denormalize($data['max'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesMoney::class, 'json', $context);
            unset($data['max']);
        }
        foreach ($data as $key => $value_2) {
            if (preg_match('/.*/', (string) $key)) {
                $object[$key] = $value_2;
            }
        }
        return $object;
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $dataArray = [];
        if (null !== ($data->isSelectedByDefault ?? null)) {
            $dataArray['isSelectedByDefault'] = $data->isSelectedByDefault;
        }
        if (null !== ($data->isRequired ?? null)) {
            $dataArray['isRequired'] = $data->isRequired;
        }
        if (null !== ($data->requires ?? null)) {
            $values = [];
            foreach ($data->requires as $value) {
                $values[] = $value;
            }
            $dataArray['requires'] = $values;
        }
        if (null !== ($data->excludes ?? null)) {
            $values_1 = [];
            foreach ($data->excludes as $value_1) {
                $values_1[] = $value_1;
            }
            $dataArray['excludes'] = $values_1;
        }
        if (array_key_exists('insuredAmount', get_object_vars($data))) {
            $normalized = $data->insuredAmount === null ? null : $this->normalizer->normalize($data->insuredAmount, 'json', $context);
            $dataArray['insuredAmount'] = \is_iterable($normalized) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized) : $normalized;
        }
        if (array_key_exists('default', get_object_vars($data))) {
            $normalized_1 = $data->default === null ? null : $this->normalizer->normalize($data->default, 'json', $context);
            $dataArray['default'] = \is_iterable($normalized_1) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_1) : $normalized_1;
        }
        if (array_key_exists('min', get_object_vars($data))) {
            $normalized_2 = $data->min === null ? null : $this->normalizer->normalize($data->min, 'json', $context);
            $dataArray['min'] = \is_iterable($normalized_2) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_2) : $normalized_2;
        }
        if (array_key_exists('max', get_object_vars($data))) {
            $normalized_3 = $data->max === null ? null : $this->normalizer->normalize($data->max, 'json', $context);
            $dataArray['max'] = \is_iterable($normalized_3) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_3) : $normalized_3;
        }
        foreach ($data->additionalPropertyEntries() as $key => $value_2) {
            if (preg_match('/.*/', (string) $key)) {
                $dataArray[$key] = $value_2;
            }
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\RefCapabilitiesResponseOptionsInsuranceOptionV2Constraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseOptionsInsuranceOptionV2::class => false];
    }
}