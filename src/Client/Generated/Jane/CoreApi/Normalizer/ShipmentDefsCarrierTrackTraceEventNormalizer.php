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
class ShipmentDefsCarrierTrackTraceEventNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsCarrierTrackTraceEvent::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsCarrierTrackTraceEvent::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsCarrierTrackTraceEvent();
        if (null === $data || false === \is_array($data)) {
            return $object;
        }
        if (isset($data['$ref']) && !isset($data['type']) && !isset($data['properties']) && !isset($data['allOf'])) {
            return new Reference($data['$ref'], $context['document-origin']);
        }
        if (isset($data['$recursiveRef'])) {
            return new Reference($data['$recursiveRef'], $context['document-origin']);
        }
        if (\array_key_exists('delayed', $data) && \is_int($data['delayed'])) {
            $data['delayed'] = (bool) $data['delayed'];
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\ShipmentDefsCarrierTrackTraceEventConstraint());
        }
        if (\array_key_exists('code', $data)) {
            $object->code = $data['code'];
        }
        if (\array_key_exists('status', $data) && $data['status'] !== null) {
            $value = $data['status'];
            if (is_null($data['status'])) {
                $value = $data['status'];
            } elseif (is_int($data['status'])) {
                $value = \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentStatus::tryFrom($data['status']) ?? $data['status'];
            }
            $object->status = $value;
        }
        elseif (\array_key_exists('status', $data)) {
            $object->status = null;
        }
        if (\array_key_exists('main', $data) && $data['main'] !== null) {
            $value_1 = $data['main'];
            if (is_string($data['main'])) {
                $value_1 = $data['main'];
            } elseif (is_null($data['main'])) {
                $value_1 = $data['main'];
            }
            $object->main = $value_1;
        }
        elseif (\array_key_exists('main', $data)) {
            $object->main = null;
        }
        if (\array_key_exists('description', $data)) {
            $object->description = $data['description'];
        }
        if (\array_key_exists('time', $data)) {
            $object->time = $data['time'];
        }
        if (\array_key_exists('delayed', $data)) {
            $object->delayed = $data['delayed'];
        }
        if (\array_key_exists('location', $data)) {
            $object->location = $this->denormalizer->denormalize($data['location'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsTrackingLocation::class, 'json', $context);
        }
        return $object;
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $dataArray = [];
        if (null !== ($data->code ?? null)) {
            $dataArray['code'] = $data->code;
        }
        if (null !== ($data->status ?? null)) {
            $value = $data->status;
            if (is_null($data->status)) {
                $value = $data->status;
            } elseif ($data->status instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentStatus || is_int($data->status)) {
                $value = $data->status instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentStatus ? $data->status->value : $data->status;
            }
            $dataArray['status'] = $value;
        }
        if (null !== ($data->main ?? null)) {
            $value_1 = $data->main;
            if (is_string($data->main)) {
                $value_1 = $data->main;
            } elseif (is_null($data->main)) {
                $value_1 = $data->main;
            }
            $dataArray['main'] = $value_1;
        }
        if (null !== ($data->description ?? null)) {
            $dataArray['description'] = $data->description;
        }
        if (null !== ($data->time ?? null)) {
            $dataArray['time'] = $data->time;
        }
        if (null !== ($data->delayed ?? null)) {
            $dataArray['delayed'] = $data->delayed;
        }
        if (null !== ($data->location ?? null)) {
            $normalized = $this->normalizer->normalize($data->location, 'json', $context);
            $dataArray['location'] = \is_iterable($normalized) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized) : $normalized;
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\ShipmentDefsCarrierTrackTraceEventConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsCarrierTrackTraceEvent::class => false];
    }
}