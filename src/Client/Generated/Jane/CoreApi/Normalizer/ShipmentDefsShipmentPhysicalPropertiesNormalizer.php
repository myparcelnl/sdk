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
class ShipmentDefsShipmentPhysicalPropertiesNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentPhysicalProperties::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentPhysicalProperties::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentPhysicalProperties();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\ShipmentDefsShipmentPhysicalPropertiesConstraint());
        }
        if (\array_key_exists('weight', $data)) {
            $object->weight = $data['weight'];
        }
        if (\array_key_exists('height', $data) && $data['height'] !== null) {
            $value = $data['height'];
            if (is_int($data['height'])) {
                $value = $data['height'];
            } elseif (is_null($data['height'])) {
                $value = $data['height'];
            }
            $object->height = $value;
        }
        elseif (\array_key_exists('height', $data)) {
            $object->height = null;
        }
        if (\array_key_exists('width', $data) && $data['width'] !== null) {
            $value_1 = $data['width'];
            if (is_int($data['width'])) {
                $value_1 = $data['width'];
            } elseif (is_null($data['width'])) {
                $value_1 = $data['width'];
            }
            $object->width = $value_1;
        }
        elseif (\array_key_exists('width', $data)) {
            $object->width = null;
        }
        if (\array_key_exists('length', $data) && $data['length'] !== null) {
            $value_2 = $data['length'];
            if (is_int($data['length'])) {
                $value_2 = $data['length'];
            } elseif (is_null($data['length'])) {
                $value_2 = $data['length'];
            }
            $object->length = $value_2;
        }
        elseif (\array_key_exists('length', $data)) {
            $object->length = null;
        }
        if (\array_key_exists('volume', $data) && $data['volume'] !== null) {
            $value_3 = $data['volume'];
            if (is_int($data['volume'])) {
                $value_3 = $data['volume'];
            } elseif (is_null($data['volume'])) {
                $value_3 = $data['volume'];
            }
            $object->volume = $value_3;
        }
        elseif (\array_key_exists('volume', $data)) {
            $object->volume = null;
        }
        if (\array_key_exists('carrier_weight', $data) && $data['carrier_weight'] !== null) {
            $value_4 = $data['carrier_weight'];
            if (is_int($data['carrier_weight'])) {
                $value_4 = $data['carrier_weight'];
            } elseif (is_string($data['carrier_weight'])) {
                $value_4 = $data['carrier_weight'];
            } elseif (is_null($data['carrier_weight'])) {
                $value_4 = $data['carrier_weight'];
            }
            $object->carrierWeight = $value_4;
        }
        elseif (\array_key_exists('carrier_weight', $data)) {
            $object->carrierWeight = null;
        }
        if (\array_key_exists('carrier_height', $data) && $data['carrier_height'] !== null) {
            $value_5 = $data['carrier_height'];
            if (is_int($data['carrier_height'])) {
                $value_5 = $data['carrier_height'];
            } elseif (is_null($data['carrier_height'])) {
                $value_5 = $data['carrier_height'];
            } elseif (is_null($data['carrier_height'])) {
                $value_5 = $data['carrier_height'];
            }
            $object->carrierHeight = $value_5;
        }
        elseif (\array_key_exists('carrier_height', $data)) {
            $object->carrierHeight = null;
        }
        if (\array_key_exists('carrier_width', $data) && $data['carrier_width'] !== null) {
            $value_6 = $data['carrier_width'];
            if (is_int($data['carrier_width'])) {
                $value_6 = $data['carrier_width'];
            } elseif (is_null($data['carrier_width'])) {
                $value_6 = $data['carrier_width'];
            } elseif (is_null($data['carrier_width'])) {
                $value_6 = $data['carrier_width'];
            }
            $object->carrierWidth = $value_6;
        }
        elseif (\array_key_exists('carrier_width', $data)) {
            $object->carrierWidth = null;
        }
        if (\array_key_exists('carrier_length', $data) && $data['carrier_length'] !== null) {
            $value_7 = $data['carrier_length'];
            if (is_int($data['carrier_length'])) {
                $value_7 = $data['carrier_length'];
            } elseif (is_null($data['carrier_length'])) {
                $value_7 = $data['carrier_length'];
            } elseif (is_null($data['carrier_length'])) {
                $value_7 = $data['carrier_length'];
            }
            $object->carrierLength = $value_7;
        }
        elseif (\array_key_exists('carrier_length', $data)) {
            $object->carrierLength = null;
        }
        if (\array_key_exists('carrier_volume', $data) && $data['carrier_volume'] !== null) {
            $value_8 = $data['carrier_volume'];
            if (is_int($data['carrier_volume'])) {
                $value_8 = $data['carrier_volume'];
            } elseif (is_null($data['carrier_volume'])) {
                $value_8 = $data['carrier_volume'];
            } elseif (is_null($data['carrier_volume'])) {
                $value_8 = $data['carrier_volume'];
            }
            $object->carrierVolume = $value_8;
        }
        elseif (\array_key_exists('carrier_volume', $data)) {
            $object->carrierVolume = null;
        }
        return $object;
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $dataArray = [];
        if (array_key_exists('weight', get_object_vars($data))) {
            $dataArray['weight'] = $data->weight;
        }
        if (array_key_exists('height', get_object_vars($data))) {
            $value = $data->height;
            if (is_int($data->height)) {
                $value = $data->height;
            } elseif (is_null($data->height)) {
                $value = $data->height;
            }
            $dataArray['height'] = $value;
        }
        if (array_key_exists('width', get_object_vars($data))) {
            $value_1 = $data->width;
            if (is_int($data->width)) {
                $value_1 = $data->width;
            } elseif (is_null($data->width)) {
                $value_1 = $data->width;
            }
            $dataArray['width'] = $value_1;
        }
        if (array_key_exists('length', get_object_vars($data))) {
            $value_2 = $data->length;
            if (is_int($data->length)) {
                $value_2 = $data->length;
            } elseif (is_null($data->length)) {
                $value_2 = $data->length;
            }
            $dataArray['length'] = $value_2;
        }
        if (array_key_exists('volume', get_object_vars($data))) {
            $value_3 = $data->volume;
            if (is_int($data->volume)) {
                $value_3 = $data->volume;
            } elseif (is_null($data->volume)) {
                $value_3 = $data->volume;
            }
            $dataArray['volume'] = $value_3;
        }
        if (array_key_exists('carrierWeight', get_object_vars($data))) {
            $value_4 = $data->carrierWeight;
            if (is_int($data->carrierWeight)) {
                $value_4 = $data->carrierWeight;
            } elseif (is_string($data->carrierWeight)) {
                $value_4 = $data->carrierWeight;
            } elseif (is_null($data->carrierWeight)) {
                $value_4 = $data->carrierWeight;
            }
            $dataArray['carrier_weight'] = $value_4;
        }
        if (array_key_exists('carrierHeight', get_object_vars($data))) {
            $value_5 = $data->carrierHeight;
            if (is_int($data->carrierHeight)) {
                $value_5 = $data->carrierHeight;
            } elseif (is_null($data->carrierHeight)) {
                $value_5 = $data->carrierHeight;
            } elseif (is_null($data->carrierHeight)) {
                $value_5 = $data->carrierHeight;
            }
            $dataArray['carrier_height'] = $value_5;
        }
        if (array_key_exists('carrierWidth', get_object_vars($data))) {
            $value_6 = $data->carrierWidth;
            if (is_int($data->carrierWidth)) {
                $value_6 = $data->carrierWidth;
            } elseif (is_null($data->carrierWidth)) {
                $value_6 = $data->carrierWidth;
            } elseif (is_null($data->carrierWidth)) {
                $value_6 = $data->carrierWidth;
            }
            $dataArray['carrier_width'] = $value_6;
        }
        if (array_key_exists('carrierLength', get_object_vars($data))) {
            $value_7 = $data->carrierLength;
            if (is_int($data->carrierLength)) {
                $value_7 = $data->carrierLength;
            } elseif (is_null($data->carrierLength)) {
                $value_7 = $data->carrierLength;
            } elseif (is_null($data->carrierLength)) {
                $value_7 = $data->carrierLength;
            }
            $dataArray['carrier_length'] = $value_7;
        }
        if (array_key_exists('carrierVolume', get_object_vars($data))) {
            $value_8 = $data->carrierVolume;
            if (is_int($data->carrierVolume)) {
                $value_8 = $data->carrierVolume;
            } elseif (is_null($data->carrierVolume)) {
                $value_8 = $data->carrierVolume;
            } elseif (is_null($data->carrierVolume)) {
                $value_8 = $data->carrierVolume;
            }
            $dataArray['carrier_volume'] = $value_8;
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\ShipmentDefsShipmentPhysicalPropertiesConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentPhysicalProperties::class => false];
    }
}