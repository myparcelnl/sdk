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
class ShipmentPostShipmentsRequestV11DataShipmentsItemCollectionContactNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemCollectionContact::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemCollectionContact::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemCollectionContact();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\ShipmentPostShipmentsRequestV11DataShipmentsItemCollectionContactConstraint());
        }
        if (\array_key_exists('cc', $data)) {
            $object->cc = $data['cc'];
            unset($data['cc']);
        }
        if (\array_key_exists('city', $data)) {
            $object->city = $data['city'];
            unset($data['city']);
        }
        if (\array_key_exists('company', $data)) {
            $value = $data['company'];
            if (is_string($data['company'])) {
                $value = $data['company'];
            }
            $object->company = $value;
            unset($data['company']);
        }
        if (\array_key_exists('email', $data)) {
            $object->email = $data['email'];
            unset($data['email']);
        }
        if (\array_key_exists('first_name', $data)) {
            $object->firstName = $data['first_name'];
            unset($data['first_name']);
        }
        if (\array_key_exists('gender', $data)) {
            $object->gender = is_string($data['gender']) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemCollectionContactGender::tryFrom($data['gender']) ?? $data['gender'] : $data['gender'];
            unset($data['gender']);
        }
        if (\array_key_exists('last_name', $data)) {
            $object->lastName = $data['last_name'];
            unset($data['last_name']);
        }
        if (\array_key_exists('number', $data)) {
            $value_1 = $data['number'];
            if (is_string($data['number'])) {
                $value_1 = $data['number'];
            } elseif (is_int($data['number'])) {
                $value_1 = $data['number'];
            }
            $object->number = $value_1;
            unset($data['number']);
        }
        if (\array_key_exists('number_suffix', $data) && $data['number_suffix'] !== null) {
            $value_2 = $data['number_suffix'];
            if (is_string($data['number_suffix'])) {
                $value_2 = $data['number_suffix'];
            } elseif (is_null($data['number_suffix'])) {
                $value_2 = $data['number_suffix'];
            }
            $object->numberSuffix = $value_2;
            unset($data['number_suffix']);
        }
        elseif (\array_key_exists('number_suffix', $data)) {
            $object->numberSuffix = null;
            unset($data['number_suffix']);
        }
        if (\array_key_exists('phone', $data)) {
            $object->phone = $data['phone'];
            unset($data['phone']);
        }
        if (\array_key_exists('postal_code', $data)) {
            $object->postalCode = $data['postal_code'];
            unset($data['postal_code']);
        }
        if (\array_key_exists('street', $data)) {
            $object->street = $data['street'];
            unset($data['street']);
        }
        if (\array_key_exists('street_additional_info', $data) && $data['street_additional_info'] !== null) {
            $value_3 = $data['street_additional_info'];
            if (is_string($data['street_additional_info'])) {
                $value_3 = $data['street_additional_info'];
            } elseif (is_null($data['street_additional_info'])) {
                $value_3 = $data['street_additional_info'];
            }
            $object->streetAdditionalInfo = $value_3;
            unset($data['street_additional_info']);
        }
        elseif (\array_key_exists('street_additional_info', $data)) {
            $object->streetAdditionalInfo = null;
            unset($data['street_additional_info']);
        }
        foreach ($data as $key => $value_4) {
            if (preg_match('/.*/', (string) $key)) {
                $object[$key] = $value_4;
            }
        }
        return $object;
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $dataArray = [];
        if (null !== ($data->cc ?? null)) {
            $dataArray['cc'] = $data->cc;
        }
        if (null !== ($data->city ?? null)) {
            $dataArray['city'] = $data->city;
        }
        if (null !== ($data->company ?? null)) {
            $value = $data->company;
            if (is_string($data->company)) {
                $value = $data->company;
            }
            $dataArray['company'] = $value;
        }
        if (null !== ($data->email ?? null)) {
            $dataArray['email'] = $data->email;
        }
        if (array_key_exists('firstName', get_object_vars($data))) {
            $dataArray['first_name'] = $data->firstName;
        }
        if (array_key_exists('gender', get_object_vars($data))) {
            $dataArray['gender'] = $data->gender instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemCollectionContactGender ? $data->gender->value : $data->gender;
        }
        if (null !== ($data->lastName ?? null)) {
            $dataArray['last_name'] = $data->lastName;
        }
        if (null !== ($data->number ?? null)) {
            $value_1 = $data->number;
            if (is_string($data->number)) {
                $value_1 = $data->number;
            } elseif (is_int($data->number)) {
                $value_1 = $data->number;
            }
            $dataArray['number'] = $value_1;
        }
        if (array_key_exists('numberSuffix', get_object_vars($data))) {
            $value_2 = $data->numberSuffix;
            if (is_string($data->numberSuffix)) {
                $value_2 = $data->numberSuffix;
            } elseif (is_null($data->numberSuffix)) {
                $value_2 = $data->numberSuffix;
            }
            $dataArray['number_suffix'] = $value_2;
        }
        if (null !== ($data->phone ?? null)) {
            $dataArray['phone'] = $data->phone;
        }
        if (null !== ($data->postalCode ?? null)) {
            $dataArray['postal_code'] = $data->postalCode;
        }
        if (null !== ($data->street ?? null)) {
            $dataArray['street'] = $data->street;
        }
        if (array_key_exists('streetAdditionalInfo', get_object_vars($data))) {
            $value_3 = $data->streetAdditionalInfo;
            if (is_string($data->streetAdditionalInfo)) {
                $value_3 = $data->streetAdditionalInfo;
            } elseif (is_null($data->streetAdditionalInfo)) {
                $value_3 = $data->streetAdditionalInfo;
            }
            $dataArray['street_additional_info'] = $value_3;
        }
        foreach ($data->additionalPropertyEntries() as $key => $value_4) {
            if (preg_match('/.*/', (string) $key)) {
                $dataArray[$key] = $value_4;
            }
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\ShipmentPostShipmentsRequestV11DataShipmentsItemCollectionContactConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemCollectionContact::class => false];
    }
}