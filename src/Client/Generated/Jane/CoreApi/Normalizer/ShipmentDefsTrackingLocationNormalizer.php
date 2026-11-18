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
class ShipmentDefsTrackingLocationNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsTrackingLocation::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsTrackingLocation::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsTrackingLocation();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\ShipmentDefsTrackingLocationConstraint());
        }
        if (\array_key_exists('name', $data)) {
            $value = $data['name'];
            if (is_string($data['name'])) {
                $value = $data['name'];
            }
            $object->name = $value;
        }
        if (\array_key_exists('cc', $data) && $data['cc'] !== null) {
            $value_1 = $data['cc'];
            if (is_string($data['cc'])) {
                $value_1 = $data['cc'];
            } elseif (is_null($data['cc'])) {
                $value_1 = $data['cc'];
            }
            $object->cc = $value_1;
        }
        elseif (\array_key_exists('cc', $data)) {
            $object->cc = null;
        }
        if (\array_key_exists('city', $data) && $data['city'] !== null) {
            $value_2 = $data['city'];
            if (is_string($data['city'])) {
                $value_2 = $data['city'];
            } elseif (is_null($data['city'])) {
                $value_2 = $data['city'];
            }
            $object->city = $value_2;
        }
        elseif (\array_key_exists('city', $data)) {
            $object->city = null;
        }
        if (\array_key_exists('postal_code', $data) && $data['postal_code'] !== null) {
            $value_3 = $data['postal_code'];
            if (is_string($data['postal_code'])) {
                $value_3 = $data['postal_code'];
            } elseif (is_null($data['postal_code'])) {
                $value_3 = $data['postal_code'];
            }
            $object->postalCode = $value_3;
        }
        elseif (\array_key_exists('postal_code', $data)) {
            $object->postalCode = null;
        }
        if (\array_key_exists('street', $data) && $data['street'] !== null) {
            $value_4 = $data['street'];
            if (is_string($data['street'])) {
                $value_4 = $data['street'];
            } elseif (is_null($data['street'])) {
                $value_4 = $data['street'];
            }
            $object->street = $value_4;
        }
        elseif (\array_key_exists('street', $data)) {
            $object->street = null;
        }
        if (\array_key_exists('number', $data) && $data['number'] !== null) {
            $value_5 = $data['number'];
            if (is_string($data['number'])) {
                $value_5 = $data['number'];
            } elseif (is_null($data['number'])) {
                $value_5 = $data['number'];
            }
            $object->number = $value_5;
        }
        elseif (\array_key_exists('number', $data)) {
            $object->number = null;
        }
        if (\array_key_exists('number_suffix', $data) && $data['number_suffix'] !== null) {
            $value_6 = $data['number_suffix'];
            if (is_string($data['number_suffix'])) {
                $value_6 = $data['number_suffix'];
            } elseif (is_null($data['number_suffix'])) {
                $value_6 = $data['number_suffix'];
            }
            $object->numberSuffix = $value_6;
        }
        elseif (\array_key_exists('number_suffix', $data)) {
            $object->numberSuffix = null;
        }
        if (\array_key_exists('longitude', $data) && $data['longitude'] !== null) {
            $value_7 = $data['longitude'];
            if (is_float($data['longitude'])) {
                $value_7 = $data['longitude'];
            } elseif (is_null($data['longitude'])) {
                $value_7 = $data['longitude'];
            }
            $object->longitude = $value_7;
        }
        elseif (\array_key_exists('longitude', $data)) {
            $object->longitude = null;
        }
        if (\array_key_exists('latitude', $data) && $data['latitude'] !== null) {
            $value_8 = $data['latitude'];
            if (is_float($data['latitude'])) {
                $value_8 = $data['latitude'];
            } elseif (is_null($data['latitude'])) {
                $value_8 = $data['latitude'];
            }
            $object->latitude = $value_8;
        }
        elseif (\array_key_exists('latitude', $data)) {
            $object->latitude = null;
        }
        return $object;
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $dataArray = [];
        if (null !== ($data->name ?? null)) {
            $value = $data->name;
            if (is_string($data->name)) {
                $value = $data->name;
            }
            $dataArray['name'] = $value;
        }
        if (null !== ($data->cc ?? null)) {
            $value_1 = $data->cc;
            if (is_string($data->cc)) {
                $value_1 = $data->cc;
            } elseif (is_null($data->cc)) {
                $value_1 = $data->cc;
            }
            $dataArray['cc'] = $value_1;
        }
        if (null !== ($data->city ?? null)) {
            $value_2 = $data->city;
            if (is_string($data->city)) {
                $value_2 = $data->city;
            } elseif (is_null($data->city)) {
                $value_2 = $data->city;
            }
            $dataArray['city'] = $value_2;
        }
        if (null !== ($data->postalCode ?? null)) {
            $value_3 = $data->postalCode;
            if (is_string($data->postalCode)) {
                $value_3 = $data->postalCode;
            } elseif (is_null($data->postalCode)) {
                $value_3 = $data->postalCode;
            }
            $dataArray['postal_code'] = $value_3;
        }
        if (null !== ($data->street ?? null)) {
            $value_4 = $data->street;
            if (is_string($data->street)) {
                $value_4 = $data->street;
            } elseif (is_null($data->street)) {
                $value_4 = $data->street;
            }
            $dataArray['street'] = $value_4;
        }
        if (null !== ($data->number ?? null)) {
            $value_5 = $data->number;
            if (is_string($data->number)) {
                $value_5 = $data->number;
            } elseif (is_null($data->number)) {
                $value_5 = $data->number;
            }
            $dataArray['number'] = $value_5;
        }
        if (null !== ($data->numberSuffix ?? null)) {
            $value_6 = $data->numberSuffix;
            if (is_string($data->numberSuffix)) {
                $value_6 = $data->numberSuffix;
            } elseif (is_null($data->numberSuffix)) {
                $value_6 = $data->numberSuffix;
            }
            $dataArray['number_suffix'] = $value_6;
        }
        if (null !== ($data->longitude ?? null)) {
            $value_7 = $data->longitude;
            if (is_float($data->longitude)) {
                $value_7 = $data->longitude;
            } elseif (is_null($data->longitude)) {
                $value_7 = $data->longitude;
            }
            $dataArray['longitude'] = $value_7;
        }
        if (null !== ($data->latitude ?? null)) {
            $value_8 = $data->latitude;
            if (is_float($data->latitude)) {
                $value_8 = $data->latitude;
            } elseif (is_null($data->latitude)) {
                $value_8 = $data->latitude;
            }
            $dataArray['latitude'] = $value_8;
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\ShipmentDefsTrackingLocationConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsTrackingLocation::class => false];
    }
}