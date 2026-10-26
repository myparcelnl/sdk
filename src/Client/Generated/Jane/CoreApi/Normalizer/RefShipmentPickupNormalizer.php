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
class RefShipmentPickupNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentPickup::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentPickup::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentPickup();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\RefShipmentPickupConstraint());
        }
        if (\array_key_exists('postal_code', $data)) {
            $object->postalCode = $data['postal_code'];
        }
        if (\array_key_exists('location_name', $data)) {
            $object->locationName = $data['location_name'];
        }
        if (\array_key_exists('city', $data)) {
            $object->city = $data['city'];
        }
        if (\array_key_exists('street', $data)) {
            $object->street = $data['street'];
        }
        if (\array_key_exists('number', $data)) {
            $object->number = $data['number'];
        }
        if (\array_key_exists('number_suffix', $data) && $data['number_suffix'] !== null) {
            $value = $data['number_suffix'];
            if (is_string($data['number_suffix'])) {
                $value = $data['number_suffix'];
            } elseif (is_null($data['number_suffix'])) {
                $value = $data['number_suffix'];
            }
            $object->numberSuffix = $value;
        }
        elseif (\array_key_exists('number_suffix', $data)) {
            $object->numberSuffix = null;
        }
        if (\array_key_exists('box_number', $data)) {
            $object->boxNumber = $data['box_number'];
        }
        if (\array_key_exists('region', $data)) {
            $object->region = $data['region'];
        }
        if (\array_key_exists('state', $data)) {
            $object->state = $data['state'];
        }
        if (\array_key_exists('cc', $data)) {
            $object->cc = $data['cc'];
        }
        if (\array_key_exists('location_code', $data)) {
            $object->locationCode = $data['location_code'];
        }
        if (\array_key_exists('location_type', $data)) {
            $value_1 = $data['location_type'];
            if (is_int($data['location_type'])) {
                $value_1 = \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentBpostBpostPugoType::tryFrom($data['location_type']) ?? $data['location_type'];
            } elseif (is_string($data['location_type'])) {
                $value_1 = \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentLocationType::tryFrom($data['location_type']) ?? $data['location_type'];
            }
            $object->locationType = $value_1;
        }
        if (\array_key_exists('retail_network_id', $data)) {
            $object->retailNetworkId = $data['retail_network_id'];
        }
        return $object;
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $dataArray = [];
        if (array_key_exists('postalCode', get_object_vars($data))) {
            $dataArray['postal_code'] = $data->postalCode;
        }
        if (array_key_exists('locationName', get_object_vars($data))) {
            $dataArray['location_name'] = $data->locationName;
        }
        if (array_key_exists('city', get_object_vars($data))) {
            $dataArray['city'] = $data->city;
        }
        if (array_key_exists('street', get_object_vars($data))) {
            $dataArray['street'] = $data->street;
        }
        if (array_key_exists('number', get_object_vars($data))) {
            $dataArray['number'] = $data->number;
        }
        if (array_key_exists('numberSuffix', get_object_vars($data))) {
            $value = $data->numberSuffix;
            if (is_string($data->numberSuffix)) {
                $value = $data->numberSuffix;
            } elseif (is_null($data->numberSuffix)) {
                $value = $data->numberSuffix;
            }
            $dataArray['number_suffix'] = $value;
        }
        if (array_key_exists('boxNumber', get_object_vars($data))) {
            $dataArray['box_number'] = $data->boxNumber;
        }
        if (array_key_exists('region', get_object_vars($data))) {
            $dataArray['region'] = $data->region;
        }
        if (array_key_exists('state', get_object_vars($data))) {
            $dataArray['state'] = $data->state;
        }
        if (array_key_exists('cc', get_object_vars($data))) {
            $dataArray['cc'] = $data->cc;
        }
        if (array_key_exists('locationCode', get_object_vars($data))) {
            $dataArray['location_code'] = $data->locationCode;
        }
        if (array_key_exists('locationType', get_object_vars($data))) {
            $value_1 = $data->locationType;
            if ($data->locationType instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentBpostBpostPugoType || is_int($data->locationType)) {
                $value_1 = $data->locationType instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentBpostBpostPugoType ? $data->locationType->value : $data->locationType;
            } elseif ($data->locationType instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentLocationType || is_string($data->locationType)) {
                $value_1 = $data->locationType instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentLocationType ? $data->locationType->value : $data->locationType;
            }
            $dataArray['location_type'] = $value_1;
        }
        if (array_key_exists('retailNetworkId', get_object_vars($data))) {
            $dataArray['retail_network_id'] = $data->retailNetworkId;
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\RefShipmentPickupConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentPickup::class => false];
    }
}