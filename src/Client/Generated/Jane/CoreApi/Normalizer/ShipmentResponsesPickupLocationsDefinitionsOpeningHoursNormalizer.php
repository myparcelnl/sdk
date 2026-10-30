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
class ShipmentResponsesPickupLocationsDefinitionsOpeningHoursNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesPickupLocationsDefinitionsOpeningHours::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesPickupLocationsDefinitionsOpeningHours::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesPickupLocationsDefinitionsOpeningHours();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\ShipmentResponsesPickupLocationsDefinitionsOpeningHoursConstraint());
        }
        if (\array_key_exists('monday', $data)) {
            $values = [];
            foreach ($data['monday'] as $value) {
                $values[] = $this->denormalizer->denormalize($value, \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesPickupLocationsDefinitionsTimeframe::class, 'json', $context);
            }
            $object->monday = $values;
        }
        if (\array_key_exists('tuesday', $data)) {
            $values_1 = [];
            foreach ($data['tuesday'] as $value_1) {
                $values_1[] = $this->denormalizer->denormalize($value_1, \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesPickupLocationsDefinitionsTimeframe::class, 'json', $context);
            }
            $object->tuesday = $values_1;
        }
        if (\array_key_exists('wednesday', $data)) {
            $values_2 = [];
            foreach ($data['wednesday'] as $value_2) {
                $values_2[] = $this->denormalizer->denormalize($value_2, \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesPickupLocationsDefinitionsTimeframe::class, 'json', $context);
            }
            $object->wednesday = $values_2;
        }
        if (\array_key_exists('thursday', $data)) {
            $values_3 = [];
            foreach ($data['thursday'] as $value_3) {
                $values_3[] = $this->denormalizer->denormalize($value_3, \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesPickupLocationsDefinitionsTimeframe::class, 'json', $context);
            }
            $object->thursday = $values_3;
        }
        if (\array_key_exists('friday', $data)) {
            $values_4 = [];
            foreach ($data['friday'] as $value_4) {
                $values_4[] = $this->denormalizer->denormalize($value_4, \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesPickupLocationsDefinitionsTimeframe::class, 'json', $context);
            }
            $object->friday = $values_4;
        }
        if (\array_key_exists('saturday', $data)) {
            $values_5 = [];
            foreach ($data['saturday'] as $value_5) {
                $values_5[] = $this->denormalizer->denormalize($value_5, \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesPickupLocationsDefinitionsTimeframe::class, 'json', $context);
            }
            $object->saturday = $values_5;
        }
        if (\array_key_exists('sunday', $data)) {
            $values_6 = [];
            foreach ($data['sunday'] as $value_6) {
                $values_6[] = $this->denormalizer->denormalize($value_6, \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesPickupLocationsDefinitionsTimeframe::class, 'json', $context);
            }
            $object->sunday = $values_6;
        }
        return $object;
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $dataArray = [];
        if (null !== ($data->monday ?? null)) {
            $values = [];
            foreach ($data->monday as $value) {
                $normalized = $value === null ? null : $this->normalizer->normalize($value, 'json', $context);
                $values[] = \is_iterable($normalized) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized) : $normalized;
            }
            $dataArray['monday'] = $values;
        }
        if (null !== ($data->tuesday ?? null)) {
            $values_1 = [];
            foreach ($data->tuesday as $value_1) {
                $normalized_1 = $value_1 === null ? null : $this->normalizer->normalize($value_1, 'json', $context);
                $values_1[] = \is_iterable($normalized_1) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_1) : $normalized_1;
            }
            $dataArray['tuesday'] = $values_1;
        }
        if (null !== ($data->wednesday ?? null)) {
            $values_2 = [];
            foreach ($data->wednesday as $value_2) {
                $normalized_2 = $value_2 === null ? null : $this->normalizer->normalize($value_2, 'json', $context);
                $values_2[] = \is_iterable($normalized_2) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_2) : $normalized_2;
            }
            $dataArray['wednesday'] = $values_2;
        }
        if (null !== ($data->thursday ?? null)) {
            $values_3 = [];
            foreach ($data->thursday as $value_3) {
                $normalized_3 = $value_3 === null ? null : $this->normalizer->normalize($value_3, 'json', $context);
                $values_3[] = \is_iterable($normalized_3) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_3) : $normalized_3;
            }
            $dataArray['thursday'] = $values_3;
        }
        if (null !== ($data->friday ?? null)) {
            $values_4 = [];
            foreach ($data->friday as $value_4) {
                $normalized_4 = $value_4 === null ? null : $this->normalizer->normalize($value_4, 'json', $context);
                $values_4[] = \is_iterable($normalized_4) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_4) : $normalized_4;
            }
            $dataArray['friday'] = $values_4;
        }
        if (null !== ($data->saturday ?? null)) {
            $values_5 = [];
            foreach ($data->saturday as $value_5) {
                $normalized_5 = $value_5 === null ? null : $this->normalizer->normalize($value_5, 'json', $context);
                $values_5[] = \is_iterable($normalized_5) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_5) : $normalized_5;
            }
            $dataArray['saturday'] = $values_5;
        }
        if (null !== ($data->sunday ?? null)) {
            $values_6 = [];
            foreach ($data->sunday as $value_6) {
                $normalized_6 = $value_6 === null ? null : $this->normalizer->normalize($value_6, 'json', $context);
                $values_6[] = \is_iterable($normalized_6) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_6) : $normalized_6;
            }
            $dataArray['sunday'] = $values_6;
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\ShipmentResponsesPickupLocationsDefinitionsOpeningHoursConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesPickupLocationsDefinitionsOpeningHours::class => false];
    }
}