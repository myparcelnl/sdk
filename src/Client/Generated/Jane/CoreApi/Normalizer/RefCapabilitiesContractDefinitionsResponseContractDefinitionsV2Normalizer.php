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
class RefCapabilitiesContractDefinitionsResponseContractDefinitionsV2Normalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesContractDefinitionsResponseContractDefinitionsV2::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesContractDefinitionsResponseContractDefinitionsV2::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesContractDefinitionsResponseContractDefinitionsV2();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\RefCapabilitiesContractDefinitionsResponseContractDefinitionsV2Constraint());
        }
        if (\array_key_exists('carrier', $data)) {
            $object->carrier = is_string($data['carrier']) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedCarrierV2::tryFrom($data['carrier']) ?? $data['carrier'] : $data['carrier'];
        }
        if (\array_key_exists('packageTypes', $data)) {
            $values = [];
            foreach ($data['packageTypes'] as $value) {
                $values[] = is_string($value) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentPackageTypeV2::tryFrom($value) ?? $value : $value;
            }
            $object->packageTypes = $values;
        }
        if (\array_key_exists('options', $data)) {
            $object->options = $this->denormalizer->denormalize($data['options'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesContractDefinitionsResponseOptionsOptionsV2::class, 'json', $context);
        }
        if (\array_key_exists('deliveryTypes', $data)) {
            $values_1 = [];
            foreach ($data['deliveryTypes'] as $value_1) {
                $values_1[] = is_string($value_1) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesDeliveryTypeV2::tryFrom($value_1) ?? $value_1 : $value_1;
            }
            $object->deliveryTypes = $values_1;
        }
        if (\array_key_exists('transactionTypes', $data)) {
            $values_2 = [];
            foreach ($data['transactionTypes'] as $value_2) {
                $values_2[] = is_string($value_2) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesTransactionTypes::tryFrom($value_2) ?? $value_2 : $value_2;
            }
            $object->transactionTypes = $values_2;
        }
        if (\array_key_exists('collo', $data)) {
            $object->collo = $this->denormalizer->denormalize($data['collo'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseCollo::class, 'json', $context);
        }
        return $object;
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $dataArray = [];
        if (null !== ($data->carrier ?? null)) {
            $dataArray['carrier'] = $data->carrier instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedCarrierV2 ? $data->carrier->value : $data->carrier;
        }
        if (null !== ($data->packageTypes ?? null)) {
            $values = [];
            foreach ($data->packageTypes as $value) {
                $values[] = $value instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentPackageTypeV2 ? $value->value : $value;
            }
            $dataArray['packageTypes'] = $values;
        }
        if (null !== ($data->options ?? null)) {
            $normalized = $this->normalizer->normalize($data->options, 'json', $context);
            $dataArray['options'] = \is_iterable($normalized) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized) : $normalized;
        }
        if (null !== ($data->deliveryTypes ?? null)) {
            $values_1 = [];
            foreach ($data->deliveryTypes as $value_1) {
                $values_1[] = $value_1 instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesDeliveryTypeV2 ? $value_1->value : $value_1;
            }
            $dataArray['deliveryTypes'] = $values_1;
        }
        if (null !== ($data->transactionTypes ?? null)) {
            $values_2 = [];
            foreach ($data->transactionTypes as $value_2) {
                $values_2[] = $value_2 instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesTransactionTypes ? $value_2->value : $value_2;
            }
            $dataArray['transactionTypes'] = $values_2;
        }
        if (null !== ($data->collo ?? null)) {
            $normalized_1 = $this->normalizer->normalize($data->collo, 'json', $context);
            $dataArray['collo'] = \is_iterable($normalized_1) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_1) : $normalized_1;
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\RefCapabilitiesContractDefinitionsResponseContractDefinitionsV2Constraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesContractDefinitionsResponseContractDefinitionsV2::class => false];
    }
}