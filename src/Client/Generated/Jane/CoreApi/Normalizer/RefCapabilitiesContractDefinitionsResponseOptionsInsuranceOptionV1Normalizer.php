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
class RefCapabilitiesContractDefinitionsResponseOptionsInsuranceOptionV1Normalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesContractDefinitionsResponseOptionsInsuranceOptionV1::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesContractDefinitionsResponseOptionsInsuranceOptionV1::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesContractDefinitionsResponseOptionsInsuranceOptionV1();
        if (null === $data || false === \is_array($data)) {
            return $object;
        }
        if (isset($data['$ref']) && !isset($data['type']) && !isset($data['properties']) && !isset($data['allOf'])) {
            return new Reference($data['$ref'], $context['document-origin']);
        }
        if (isset($data['$recursiveRef'])) {
            return new Reference($data['$recursiveRef'], $context['document-origin']);
        }
        if (\array_key_exists('is_selected_by_default', $data) && \is_int($data['is_selected_by_default'])) {
            $data['is_selected_by_default'] = (bool) $data['is_selected_by_default'];
        }
        if (\array_key_exists('is_required', $data) && \is_int($data['is_required'])) {
            $data['is_required'] = (bool) $data['is_required'];
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\RefCapabilitiesContractDefinitionsResponseOptionsInsuranceOptionV1Constraint());
        }
        if (\array_key_exists('is_selected_by_default', $data)) {
            $object->isSelectedByDefault = $data['is_selected_by_default'];
            unset($data['is_selected_by_default']);
        }
        if (\array_key_exists('is_required', $data)) {
            $object->isRequired = $data['is_required'];
            unset($data['is_required']);
        }
        if (\array_key_exists('insured_amount', $data)) {
            $object->insuredAmount = $this->denormalizer->denormalize($data['insured_amount'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedOptionsInsuranceBaseInsuranceV1InsuredAmount::class, 'json', $context);
            unset($data['insured_amount']);
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
        if (null !== ($data->isSelectedByDefault ?? null)) {
            $dataArray['is_selected_by_default'] = $data->isSelectedByDefault;
        }
        if (null !== ($data->isRequired ?? null)) {
            $dataArray['is_required'] = $data->isRequired;
        }
        if (array_key_exists('insuredAmount', get_object_vars($data))) {
            $normalized = $data->insuredAmount === null ? null : $this->normalizer->normalize($data->insuredAmount, 'json', $context);
            $dataArray['insured_amount'] = \is_iterable($normalized) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized) : $normalized;
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
        foreach ($data->additionalPropertyEntries() as $key => $value) {
            if (preg_match('/.*/', (string) $key)) {
                $dataArray[$key] = $value;
            }
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\RefCapabilitiesContractDefinitionsResponseOptionsInsuranceOptionV1Constraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesContractDefinitionsResponseOptionsInsuranceOptionV1::class => false];
    }
}