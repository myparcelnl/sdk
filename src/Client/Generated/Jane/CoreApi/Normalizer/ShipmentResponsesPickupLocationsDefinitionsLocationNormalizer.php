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
class ShipmentResponsesPickupLocationsDefinitionsLocationNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesPickupLocationsDefinitionsLocation::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesPickupLocationsDefinitionsLocation::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesPickupLocationsDefinitionsLocation();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\ShipmentResponsesPickupLocationsDefinitionsLocationConstraint());
        }
        if (\array_key_exists('location_name', $data)) {
            $object->locationName = $data['location_name'];
        }
        if (\array_key_exists('retail_network_id', $data) && $data['retail_network_id'] !== null) {
            $value = $data['retail_network_id'];
            if (is_string($data['retail_network_id'])) {
                $value = $data['retail_network_id'];
            } elseif (is_null($data['retail_network_id'])) {
                $value = $data['retail_network_id'];
            }
            $object->retailNetworkId = $value;
        }
        elseif (\array_key_exists('retail_network_id', $data)) {
            $object->retailNetworkId = null;
        }
        if (\array_key_exists('distance', $data)) {
            $object->distance = $data['distance'];
        }
        if (\array_key_exists('latitude', $data)) {
            $object->latitude = $data['latitude'];
        }
        if (\array_key_exists('longitude', $data)) {
            $object->longitude = $data['longitude'];
        }
        if (\array_key_exists('location_code', $data)) {
            $object->locationCode = $data['location_code'];
        }
        if (\array_key_exists('phone_number', $data) && $data['phone_number'] !== null) {
            $value_1 = $data['phone_number'];
            if (is_string($data['phone_number'])) {
                $value_1 = $data['phone_number'];
            } elseif (is_null($data['phone_number'])) {
                $value_1 = $data['phone_number'];
            }
            $object->phoneNumber = $value_1;
        }
        elseif (\array_key_exists('phone_number', $data)) {
            $object->phoneNumber = null;
        }
        if (\array_key_exists('type', $data) && $data['type'] !== null) {
            $value_2 = $data['type'];
            if (is_string($data['type'])) {
                $value_2 = \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentLocationType::tryFrom($data['type']) ?? $data['type'];
            } elseif (is_null($data['type'])) {
                $value_2 = $data['type'];
            }
            $object->type = $value_2;
        }
        elseif (\array_key_exists('type', $data)) {
            $object->type = null;
        }
        if (\array_key_exists('opening_hours', $data)) {
            $object->openingHours = $this->denormalizer->denormalize($data['opening_hours'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesPickupLocationsDefinitionsOpeningHours::class, 'json', $context);
        }
        return $object;
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $dataArray = [];
        if (null !== ($data->locationName ?? null)) {
            $dataArray['location_name'] = $data->locationName;
        }
        if (null !== ($data->retailNetworkId ?? null)) {
            $value = $data->retailNetworkId;
            if (is_string($data->retailNetworkId)) {
                $value = $data->retailNetworkId;
            } elseif (is_null($data->retailNetworkId)) {
                $value = $data->retailNetworkId;
            }
            $dataArray['retail_network_id'] = $value;
        }
        if (null !== ($data->distance ?? null)) {
            $dataArray['distance'] = $data->distance;
        }
        if (null !== ($data->latitude ?? null)) {
            $dataArray['latitude'] = $data->latitude;
        }
        if (null !== ($data->longitude ?? null)) {
            $dataArray['longitude'] = $data->longitude;
        }
        if (null !== ($data->locationCode ?? null)) {
            $dataArray['location_code'] = $data->locationCode;
        }
        if (null !== ($data->phoneNumber ?? null)) {
            $value_1 = $data->phoneNumber;
            if (is_string($data->phoneNumber)) {
                $value_1 = $data->phoneNumber;
            } elseif (is_null($data->phoneNumber)) {
                $value_1 = $data->phoneNumber;
            }
            $dataArray['phone_number'] = $value_1;
        }
        if (null !== ($data->type ?? null)) {
            $value_2 = $data->type;
            if ($data->type instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentLocationType || is_string($data->type)) {
                $value_2 = $data->type instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentLocationType ? $data->type->value : $data->type;
            } elseif (is_null($data->type)) {
                $value_2 = $data->type;
            }
            $dataArray['type'] = $value_2;
        }
        if (null !== ($data->openingHours ?? null)) {
            $normalized = $this->normalizer->normalize($data->openingHours, 'json', $context);
            $dataArray['opening_hours'] = \is_iterable($normalized) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized) : $normalized;
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\ShipmentResponsesPickupLocationsDefinitionsLocationConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesPickupLocationsDefinitionsLocation::class => false];
    }
}