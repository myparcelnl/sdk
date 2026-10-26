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
class ShipmentDefsDeliveryOptionsOpeningHoursNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsDeliveryOptionsOpeningHours::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsDeliveryOptionsOpeningHours::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsDeliveryOptionsOpeningHours();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\ShipmentDefsDeliveryOptionsOpeningHoursConstraint());
        }
        if (\array_key_exists('monday', $data)) {
            $values = [];
            foreach ($data['monday'] as $value) {
                $values[] = $value;
            }
            $object->monday = $values;
        }
        if (\array_key_exists('tuesday', $data)) {
            $values_1 = [];
            foreach ($data['tuesday'] as $value_1) {
                $values_1[] = $value_1;
            }
            $object->tuesday = $values_1;
        }
        if (\array_key_exists('wednesday', $data)) {
            $values_2 = [];
            foreach ($data['wednesday'] as $value_2) {
                $values_2[] = $value_2;
            }
            $object->wednesday = $values_2;
        }
        if (\array_key_exists('thursday', $data)) {
            $values_3 = [];
            foreach ($data['thursday'] as $value_3) {
                $values_3[] = $value_3;
            }
            $object->thursday = $values_3;
        }
        if (\array_key_exists('friday', $data)) {
            $values_4 = [];
            foreach ($data['friday'] as $value_4) {
                $values_4[] = $value_4;
            }
            $object->friday = $values_4;
        }
        if (\array_key_exists('saturday', $data)) {
            $values_5 = [];
            foreach ($data['saturday'] as $value_5) {
                $values_5[] = $value_5;
            }
            $object->saturday = $values_5;
        }
        if (\array_key_exists('sunday', $data)) {
            $values_6 = [];
            foreach ($data['sunday'] as $value_6) {
                $values_6[] = $value_6;
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
                $values[] = $value;
            }
            $dataArray['monday'] = $values;
        }
        if (null !== ($data->tuesday ?? null)) {
            $values_1 = [];
            foreach ($data->tuesday as $value_1) {
                $values_1[] = $value_1;
            }
            $dataArray['tuesday'] = $values_1;
        }
        if (null !== ($data->wednesday ?? null)) {
            $values_2 = [];
            foreach ($data->wednesday as $value_2) {
                $values_2[] = $value_2;
            }
            $dataArray['wednesday'] = $values_2;
        }
        if (null !== ($data->thursday ?? null)) {
            $values_3 = [];
            foreach ($data->thursday as $value_3) {
                $values_3[] = $value_3;
            }
            $dataArray['thursday'] = $values_3;
        }
        if (null !== ($data->friday ?? null)) {
            $values_4 = [];
            foreach ($data->friday as $value_4) {
                $values_4[] = $value_4;
            }
            $dataArray['friday'] = $values_4;
        }
        if (null !== ($data->saturday ?? null)) {
            $values_5 = [];
            foreach ($data->saturday as $value_5) {
                $values_5[] = $value_5;
            }
            $dataArray['saturday'] = $values_5;
        }
        if (null !== ($data->sunday ?? null)) {
            $values_6 = [];
            foreach ($data->sunday as $value_6) {
                $values_6[] = $value_6;
            }
            $dataArray['sunday'] = $values_6;
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\ShipmentDefsDeliveryOptionsOpeningHoursConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsDeliveryOptionsOpeningHours::class => false];
    }
}