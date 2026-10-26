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
class RefCapabilitiesResponseOptionsOptionV2Normalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseOptionsOptionV2::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseOptionsOptionV2::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseOptionsOptionV2();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\RefCapabilitiesResponseOptionsOptionV2Constraint());
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
        foreach ($data->additionalPropertyEntries() as $key => $value_2) {
            if (preg_match('/.*/', (string) $key)) {
                $dataArray[$key] = $value_2;
            }
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\RefCapabilitiesResponseOptionsOptionV2Constraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseOptionsOptionV2::class => false];
    }
}