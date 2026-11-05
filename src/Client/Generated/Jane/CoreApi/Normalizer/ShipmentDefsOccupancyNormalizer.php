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
class ShipmentDefsOccupancyNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsOccupancy::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsOccupancy::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsOccupancy();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\ShipmentDefsOccupancyConstraint());
        }
        if (\array_key_exists('monday', $data) && $data['monday'] !== null) {
            $value = $data['monday'];
            if (is_float($data['monday'])) {
                $value = $data['monday'];
            } elseif (is_null($data['monday'])) {
                $value = $data['monday'];
            }
            $object->monday = $value;
            unset($data['monday']);
        }
        elseif (\array_key_exists('monday', $data)) {
            $object->monday = null;
            unset($data['monday']);
        }
        if (\array_key_exists('tuesday', $data) && $data['tuesday'] !== null) {
            $value_1 = $data['tuesday'];
            if (is_float($data['tuesday'])) {
                $value_1 = $data['tuesday'];
            } elseif (is_null($data['tuesday'])) {
                $value_1 = $data['tuesday'];
            }
            $object->tuesday = $value_1;
            unset($data['tuesday']);
        }
        elseif (\array_key_exists('tuesday', $data)) {
            $object->tuesday = null;
            unset($data['tuesday']);
        }
        if (\array_key_exists('wednesday', $data) && $data['wednesday'] !== null) {
            $value_2 = $data['wednesday'];
            if (is_float($data['wednesday'])) {
                $value_2 = $data['wednesday'];
            } elseif (is_null($data['wednesday'])) {
                $value_2 = $data['wednesday'];
            }
            $object->wednesday = $value_2;
            unset($data['wednesday']);
        }
        elseif (\array_key_exists('wednesday', $data)) {
            $object->wednesday = null;
            unset($data['wednesday']);
        }
        if (\array_key_exists('thursday', $data) && $data['thursday'] !== null) {
            $value_3 = $data['thursday'];
            if (is_float($data['thursday'])) {
                $value_3 = $data['thursday'];
            } elseif (is_null($data['thursday'])) {
                $value_3 = $data['thursday'];
            }
            $object->thursday = $value_3;
            unset($data['thursday']);
        }
        elseif (\array_key_exists('thursday', $data)) {
            $object->thursday = null;
            unset($data['thursday']);
        }
        if (\array_key_exists('friday', $data) && $data['friday'] !== null) {
            $value_4 = $data['friday'];
            if (is_float($data['friday'])) {
                $value_4 = $data['friday'];
            } elseif (is_null($data['friday'])) {
                $value_4 = $data['friday'];
            }
            $object->friday = $value_4;
            unset($data['friday']);
        }
        elseif (\array_key_exists('friday', $data)) {
            $object->friday = null;
            unset($data['friday']);
        }
        if (\array_key_exists('saturday', $data) && $data['saturday'] !== null) {
            $value_5 = $data['saturday'];
            if (is_float($data['saturday'])) {
                $value_5 = $data['saturday'];
            } elseif (is_null($data['saturday'])) {
                $value_5 = $data['saturday'];
            }
            $object->saturday = $value_5;
            unset($data['saturday']);
        }
        elseif (\array_key_exists('saturday', $data)) {
            $object->saturday = null;
            unset($data['saturday']);
        }
        if (\array_key_exists('sunday', $data) && $data['sunday'] !== null) {
            $value_6 = $data['sunday'];
            if (is_float($data['sunday'])) {
                $value_6 = $data['sunday'];
            } elseif (is_null($data['sunday'])) {
                $value_6 = $data['sunday'];
            }
            $object->sunday = $value_6;
            unset($data['sunday']);
        }
        elseif (\array_key_exists('sunday', $data)) {
            $object->sunday = null;
            unset($data['sunday']);
        }
        foreach ($data as $key => $value_7) {
            if (preg_match('/.*/', (string) $key)) {
                $object[$key] = $value_7;
            }
        }
        return $object;
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $dataArray = [];
        if (array_key_exists('monday', get_object_vars($data))) {
            $value = $data->monday;
            if (is_float($data->monday)) {
                $value = $data->monday;
            } elseif (is_null($data->monday)) {
                $value = $data->monday;
            }
            $dataArray['monday'] = $value;
        }
        if (array_key_exists('tuesday', get_object_vars($data))) {
            $value_1 = $data->tuesday;
            if (is_float($data->tuesday)) {
                $value_1 = $data->tuesday;
            } elseif (is_null($data->tuesday)) {
                $value_1 = $data->tuesday;
            }
            $dataArray['tuesday'] = $value_1;
        }
        if (array_key_exists('wednesday', get_object_vars($data))) {
            $value_2 = $data->wednesday;
            if (is_float($data->wednesday)) {
                $value_2 = $data->wednesday;
            } elseif (is_null($data->wednesday)) {
                $value_2 = $data->wednesday;
            }
            $dataArray['wednesday'] = $value_2;
        }
        if (array_key_exists('thursday', get_object_vars($data))) {
            $value_3 = $data->thursday;
            if (is_float($data->thursday)) {
                $value_3 = $data->thursday;
            } elseif (is_null($data->thursday)) {
                $value_3 = $data->thursday;
            }
            $dataArray['thursday'] = $value_3;
        }
        if (array_key_exists('friday', get_object_vars($data))) {
            $value_4 = $data->friday;
            if (is_float($data->friday)) {
                $value_4 = $data->friday;
            } elseif (is_null($data->friday)) {
                $value_4 = $data->friday;
            }
            $dataArray['friday'] = $value_4;
        }
        if (array_key_exists('saturday', get_object_vars($data))) {
            $value_5 = $data->saturday;
            if (is_float($data->saturday)) {
                $value_5 = $data->saturday;
            } elseif (is_null($data->saturday)) {
                $value_5 = $data->saturday;
            }
            $dataArray['saturday'] = $value_5;
        }
        if (array_key_exists('sunday', get_object_vars($data))) {
            $value_6 = $data->sunday;
            if (is_float($data->sunday)) {
                $value_6 = $data->sunday;
            } elseif (is_null($data->sunday)) {
                $value_6 = $data->sunday;
            }
            $dataArray['sunday'] = $value_6;
        }
        foreach ($data->additionalPropertyEntries() as $key => $value_7) {
            if (preg_match('/.*/', (string) $key)) {
                $dataArray[$key] = $value_7;
            }
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\ShipmentDefsOccupancyConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsOccupancy::class => false];
    }
}