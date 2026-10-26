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
class ShipmentResponsesPickupLocationsDefinitionsTimeframeNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesPickupLocationsDefinitionsTimeframe::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesPickupLocationsDefinitionsTimeframe::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesPickupLocationsDefinitionsTimeframe();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\ShipmentResponsesPickupLocationsDefinitionsTimeframeConstraint());
        }
        if (\array_key_exists('start', $data) && $data['start'] !== null) {
            $value = $data['start'];
            if (is_array($data['start']) and \array_key_exists('date', $data['start']) and \array_key_exists('timezone', $data['start']) and (\array_key_exists('timezone_type', $data['start']) and ($data['start']['timezone_type'] == '0' or $data['start']['timezone_type'] == '1' or $data['start']['timezone_type'] == '2' or $data['start']['timezone_type'] == '3'))) {
                $value = $this->denormalizer->denormalize($data['start'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonDefsDateTime::class, 'json', $context);
            } elseif (is_null($data['start'])) {
                $value = $data['start'];
            }
            $object->start = $value;
        }
        elseif (\array_key_exists('start', $data)) {
            $object->start = null;
        }
        if (\array_key_exists('end', $data) && $data['end'] !== null) {
            $value_1 = $data['end'];
            if (is_array($data['end']) and \array_key_exists('date', $data['end']) and \array_key_exists('timezone', $data['end']) and (\array_key_exists('timezone_type', $data['end']) and ($data['end']['timezone_type'] == '0' or $data['end']['timezone_type'] == '1' or $data['end']['timezone_type'] == '2' or $data['end']['timezone_type'] == '3'))) {
                $value_1 = $this->denormalizer->denormalize($data['end'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonDefsDateTime::class, 'json', $context);
            } elseif (is_null($data['end'])) {
                $value_1 = $data['end'];
            }
            $object->end = $value_1;
        }
        elseif (\array_key_exists('end', $data)) {
            $object->end = null;
        }
        return $object;
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $dataArray = [];
        if (null !== ($data->start ?? null)) {
            $value = $data->start;
            if (is_object($data->start)) {
                $normalized = $this->normalizer->normalize($data->start, 'json', $context);
                $value = \is_iterable($normalized) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized) : $normalized;
            } elseif (is_null($data->start)) {
                $value = $data->start;
            }
            $dataArray['start'] = $value;
        }
        if (null !== ($data->end ?? null)) {
            $value_1 = $data->end;
            if (is_object($data->end)) {
                $normalized_1 = $this->normalizer->normalize($data->end, 'json', $context);
                $value_1 = \is_iterable($normalized_1) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_1) : $normalized_1;
            } elseif (is_null($data->end)) {
                $value_1 = $data->end;
            }
            $dataArray['end'] = $value_1;
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\ShipmentResponsesPickupLocationsDefinitionsTimeframeConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesPickupLocationsDefinitionsTimeframe::class => false];
    }
}