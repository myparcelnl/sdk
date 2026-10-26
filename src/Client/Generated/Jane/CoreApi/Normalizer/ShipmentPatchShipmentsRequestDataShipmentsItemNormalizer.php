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
class ShipmentPatchShipmentsRequestDataShipmentsItemNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPatchShipmentsRequestDataShipmentsItem::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPatchShipmentsRequestDataShipmentsItem::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPatchShipmentsRequestDataShipmentsItem();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\ShipmentPatchShipmentsRequestDataShipmentsItemConstraint());
        }
        if (\array_key_exists('id', $data)) {
            $object->id = $data['id'];
        }
        if (\array_key_exists('hidden', $data)) {
            $object->hidden = is_int($data['hidden']) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPatchShipmentsRequestDataShipmentsItemHidden::tryFrom($data['hidden']) ?? $data['hidden'] : $data['hidden'];
        }
        if (\array_key_exists('status', $data)) {
            $object->status = is_int($data['status']) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentStatus::tryFrom($data['status']) ?? $data['status'] : $data['status'];
        }
        if (\array_key_exists('delivered', $data)) {
            $object->delivered = is_int($data['delivered']) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPatchShipmentsRequestDataShipmentsItemDelivered::tryFrom($data['delivered']) ?? $data['delivered'] : $data['delivered'];
        }
        return $object;
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $dataArray = [];
        if (null !== ($data->id ?? null)) {
            $dataArray['id'] = $data->id;
        }
        if (array_key_exists('hidden', get_object_vars($data))) {
            $dataArray['hidden'] = $data->hidden instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPatchShipmentsRequestDataShipmentsItemHidden ? $data->hidden->value : $data->hidden;
        }
        if (array_key_exists('status', get_object_vars($data))) {
            $dataArray['status'] = $data->status instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentStatus ? $data->status->value : $data->status;
        }
        if (array_key_exists('delivered', get_object_vars($data))) {
            $dataArray['delivered'] = $data->delivered instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPatchShipmentsRequestDataShipmentsItemDelivered ? $data->delivered->value : $data->delivered;
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\ShipmentPatchShipmentsRequestDataShipmentsItemConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPatchShipmentsRequestDataShipmentsItem::class => false];
    }
}