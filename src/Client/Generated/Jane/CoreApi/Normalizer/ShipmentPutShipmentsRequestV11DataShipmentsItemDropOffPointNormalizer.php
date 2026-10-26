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
class ShipmentPutShipmentsRequestV11DataShipmentsItemDropOffPointNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPutShipmentsRequestV11DataShipmentsItemDropOffPoint::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPutShipmentsRequestV11DataShipmentsItemDropOffPoint::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPutShipmentsRequestV11DataShipmentsItemDropOffPoint();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\ShipmentPutShipmentsRequestV11DataShipmentsItemDropOffPointConstraint());
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
        if (\array_key_exists('number_suffix', $data)) {
            $object->numberSuffix = $data['number_suffix'];
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
        if (\array_key_exists('retail_network_id', $data)) {
            $object->retailNetworkId = $data['retail_network_id'];
        }
        return $object;
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $dataArray = [];
        if (null !== ($data->postalCode ?? null)) {
            $dataArray['postal_code'] = $data->postalCode;
        }
        if (null !== ($data->locationName ?? null)) {
            $dataArray['location_name'] = $data->locationName;
        }
        if (null !== ($data->city ?? null)) {
            $dataArray['city'] = $data->city;
        }
        if (null !== ($data->street ?? null)) {
            $dataArray['street'] = $data->street;
        }
        if (null !== ($data->number ?? null)) {
            $dataArray['number'] = $data->number;
        }
        if (array_key_exists('numberSuffix', get_object_vars($data))) {
            $dataArray['number_suffix'] = $data->numberSuffix;
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
        if (null !== ($data->locationCode ?? null)) {
            $dataArray['location_code'] = $data->locationCode;
        }
        if (array_key_exists('retailNetworkId', get_object_vars($data))) {
            $dataArray['retail_network_id'] = $data->retailNetworkId;
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\ShipmentPutShipmentsRequestV11DataShipmentsItemDropOffPointConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPutShipmentsRequestV11DataShipmentsItemDropOffPoint::class => false];
    }
}