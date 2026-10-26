<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer;

use Jane\Component\JsonSchemaRuntime\Reference;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Runtime\Normalizer\CheckArray;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Runtime\Normalizer\ValidatorTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
class RefShipmentOptionsDeliveryTypeAllNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefShipmentOptionsDeliveryTypeAll::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefShipmentOptionsDeliveryTypeAll::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefShipmentOptionsDeliveryTypeAll();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Validator\RefShipmentOptionsDeliveryTypeAllConstraint());
        }
        if (\array_key_exists('delivery_type', $data) && $data['delivery_type'] !== null) {
            $value = $data['delivery_type'];
            if (is_int($data['delivery_type'])) {
                $value = $data['delivery_type'];
            } elseif (is_null($data['delivery_type'])) {
                $value = $data['delivery_type'];
            }
            $object->deliveryType = $value;
            unset($data['delivery_type']);
        }
        elseif (\array_key_exists('delivery_type', $data)) {
            $object->deliveryType = null;
            unset($data['delivery_type']);
        }
        foreach ($data as $key => $value_1) {
            if (preg_match('/.*/', (string) $key)) {
                $object[$key] = $value_1;
            }
        }
        return $object;
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $dataArray = [];
        if (array_key_exists('deliveryType', get_object_vars($data))) {
            $value = $data->deliveryType;
            if (is_int($data->deliveryType)) {
                $value = $data->deliveryType;
            } elseif (is_null($data->deliveryType)) {
                $value = $data->deliveryType;
            }
            $dataArray['delivery_type'] = $value;
        }
        foreach ($data->additionalPropertyEntries() as $key => $value_1) {
            if (preg_match('/.*/', (string) $key)) {
                $dataArray[$key] = $value_1;
            }
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Validator\RefShipmentOptionsDeliveryTypeAllConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefShipmentOptionsDeliveryTypeAll::class => false];
    }
}