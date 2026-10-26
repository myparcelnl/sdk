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
class RefCapabilitiesResponseCapabilityV1Normalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseCapabilityV1::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseCapabilityV1::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseCapabilityV1();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\RefCapabilitiesResponseCapabilityV1Constraint());
        }
        if (\array_key_exists('carrier_id', $data)) {
            $object->carrierId = is_int($data['carrier_id']) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedCarrier::tryFrom($data['carrier_id']) ?? $data['carrier_id'] : $data['carrier_id'];
        }
        if (\array_key_exists('contract', $data)) {
            $object->contract = $this->denormalizer->denormalize($data['contract'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedContractV1::class, 'json', $context);
        }
        if (\array_key_exists('package_types', $data)) {
            $values = [];
            foreach ($data['package_types'] as $value) {
                $values[] = is_int($value) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentPackageType::tryFrom($value) ?? $value : $value;
            }
            $object->packageTypes = $values;
        }
        if (\array_key_exists('options', $data)) {
            $object->options = $this->denormalizer->denormalize($data['options'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseOptionsOptionsV1::class, 'json', $context);
        }
        if (\array_key_exists('physical_properties', $data)) {
            $object->physicalProperties = $this->denormalizer->denormalize($data['physical_properties'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponsePhysicalPropertiesPhysicalPropertiesV1::class, 'json', $context);
        }
        if (\array_key_exists('delivery_types', $data)) {
            $values_1 = [];
            foreach ($data['delivery_types'] as $value_1) {
                $values_1[] = is_int($value_1) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesDeliveryType::tryFrom($value_1) ?? $value_1 : $value_1;
            }
            $object->deliveryTypes = $values_1;
        }
        if (\array_key_exists('transaction_types', $data)) {
            $values_2 = [];
            foreach ($data['transaction_types'] as $value_2) {
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
        if (null !== ($data->carrierId ?? null)) {
            $dataArray['carrier_id'] = $data->carrierId instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedCarrier ? $data->carrierId->value : $data->carrierId;
        }
        if (array_key_exists('contract', get_object_vars($data))) {
            $normalized = $data->contract === null ? null : $this->normalizer->normalize($data->contract, 'json', $context);
            $dataArray['contract'] = \is_iterable($normalized) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized) : $normalized;
        }
        if (null !== ($data->packageTypes ?? null)) {
            $values = [];
            foreach ($data->packageTypes as $value) {
                $values[] = $value instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentPackageType ? $value->value : $value;
            }
            $dataArray['package_types'] = $values;
        }
        if (null !== ($data->options ?? null)) {
            $normalized_1 = $this->normalizer->normalize($data->options, 'json', $context);
            $dataArray['options'] = \is_iterable($normalized_1) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_1) : $normalized_1;
        }
        if (null !== ($data->physicalProperties ?? null)) {
            $normalized_2 = $this->normalizer->normalize($data->physicalProperties, 'json', $context);
            $dataArray['physical_properties'] = \is_iterable($normalized_2) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_2) : $normalized_2;
        }
        if (null !== ($data->deliveryTypes ?? null)) {
            $values_1 = [];
            foreach ($data->deliveryTypes as $value_1) {
                $values_1[] = $value_1 instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesDeliveryType ? $value_1->value : $value_1;
            }
            $dataArray['delivery_types'] = $values_1;
        }
        if (null !== ($data->transactionTypes ?? null)) {
            $values_2 = [];
            foreach ($data->transactionTypes as $value_2) {
                $values_2[] = $value_2 instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesTransactionTypes ? $value_2->value : $value_2;
            }
            $dataArray['transaction_types'] = $values_2;
        }
        if (null !== ($data->collo ?? null)) {
            $normalized_3 = $this->normalizer->normalize($data->collo, 'json', $context);
            $dataArray['collo'] = \is_iterable($normalized_3) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_3) : $normalized_3;
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\RefCapabilitiesResponseCapabilityV1Constraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseCapabilityV1::class => false];
    }
}