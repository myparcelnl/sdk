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
class RefShipmentSenderNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentSender::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentSender::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentSender();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\RefShipmentSenderConstraint());
        }
        if (\array_key_exists('cc', $data)) {
            $object->cc = $data['cc'];
        }
        if (\array_key_exists('postal_code', $data)) {
            $object->postalCode = $data['postal_code'];
        }
        if (\array_key_exists('city', $data)) {
            $object->city = $data['city'];
        }
        if (\array_key_exists('street', $data)) {
            $object->street = $data['street'];
        }
        if (\array_key_exists('street_additional_info', $data) && $data['street_additional_info'] !== null) {
            $value = $data['street_additional_info'];
            if (is_null($data['street_additional_info'])) {
                $value = $data['street_additional_info'];
            } elseif (is_string($data['street_additional_info'])) {
                $value = $data['street_additional_info'];
            }
            $object->streetAdditionalInfo = $value;
        }
        elseif (\array_key_exists('street_additional_info', $data)) {
            $object->streetAdditionalInfo = null;
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
        if (\array_key_exists('person', $data)) {
            $object->person = $data['person'];
        }
        if (\array_key_exists('company', $data)) {
            $object->company = $data['company'];
        }
        if (\array_key_exists('phone', $data)) {
            $object->phone = $data['phone'];
        }
        if (\array_key_exists('email', $data) && $data['email'] !== null) {
            $object->email = $data['email'];
        }
        elseif (\array_key_exists('email', $data)) {
            $object->email = null;
        }
        return $object;
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $dataArray = [];
        if (null !== ($data->cc ?? null)) {
            $dataArray['cc'] = $data->cc;
        }
        if (null !== ($data->postalCode ?? null)) {
            $dataArray['postal_code'] = $data->postalCode;
        }
        if (null !== ($data->city ?? null)) {
            $dataArray['city'] = $data->city;
        }
        if (null !== ($data->street ?? null)) {
            $dataArray['street'] = $data->street;
        }
        if (array_key_exists('streetAdditionalInfo', get_object_vars($data))) {
            $value = $data->streetAdditionalInfo;
            if (is_null($data->streetAdditionalInfo)) {
                $value = $data->streetAdditionalInfo;
            } elseif (is_string($data->streetAdditionalInfo)) {
                $value = $data->streetAdditionalInfo;
            }
            $dataArray['street_additional_info'] = $value;
        }
        if (array_key_exists('number', get_object_vars($data))) {
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
        if (null !== ($data->person ?? null)) {
            $dataArray['person'] = $data->person;
        }
        if (array_key_exists('company', get_object_vars($data))) {
            $dataArray['company'] = $data->company;
        }
        if (array_key_exists('phone', get_object_vars($data))) {
            $dataArray['phone'] = $data->phone;
        }
        if (array_key_exists('email', get_object_vars($data))) {
            $dataArray['email'] = $data->email;
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\RefShipmentSenderConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentSender::class => false];
    }
}