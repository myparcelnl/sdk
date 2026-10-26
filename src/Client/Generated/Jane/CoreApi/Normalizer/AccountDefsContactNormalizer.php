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
class AccountDefsContactNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\AccountDefsContact::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\AccountDefsContact::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\AccountDefsContact();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\AccountDefsContactConstraint());
        }
        if (\array_key_exists('id', $data)) {
            $value = $data['id'];
            if (is_int($data['id'])) {
                $value = $data['id'];
            }
            $object->id = $value;
        }
        if (\array_key_exists('gender', $data) && $data['gender'] !== null) {
            $value_1 = $data['gender'];
            if (is_null($data['gender'])) {
                $value_1 = $data['gender'];
            } elseif (is_string($data['gender'])) {
                $value_1 = $data['gender'];
            }
            $object->gender = $value_1;
        }
        elseif (\array_key_exists('gender', $data)) {
            $object->gender = null;
        }
        if (\array_key_exists('first_name', $data) && $data['first_name'] !== null) {
            $value_2 = $data['first_name'];
            if (is_null($data['first_name'])) {
                $value_2 = $data['first_name'];
            } elseif (is_string($data['first_name'])) {
                $value_2 = $data['first_name'];
            }
            $object->firstName = $value_2;
        }
        elseif (\array_key_exists('first_name', $data)) {
            $object->firstName = null;
        }
        if (\array_key_exists('last_name', $data) && $data['last_name'] !== null) {
            $value_3 = $data['last_name'];
            if (is_null($data['last_name'])) {
                $value_3 = $data['last_name'];
            } elseif (is_string($data['last_name'])) {
                $value_3 = $data['last_name'];
            }
            $object->lastName = $value_3;
        }
        elseif (\array_key_exists('last_name', $data)) {
            $object->lastName = null;
        }
        if (\array_key_exists('email', $data) && $data['email'] !== null) {
            $value_4 = $data['email'];
            if (is_null($data['email'])) {
                $value_4 = $data['email'];
            } elseif (is_string($data['email'])) {
                $value_4 = $data['email'];
            }
            $object->email = $value_4;
        }
        elseif (\array_key_exists('email', $data)) {
            $object->email = null;
        }
        if (\array_key_exists('phone', $data) && $data['phone'] !== null) {
            $value_5 = $data['phone'];
            if (is_null($data['phone'])) {
                $value_5 = $data['phone'];
            } elseif (is_string($data['phone'])) {
                $value_5 = $data['phone'];
            }
            $object->phone = $value_5;
        }
        elseif (\array_key_exists('phone', $data)) {
            $object->phone = null;
        }
        if (\array_key_exists('company', $data) && $data['company'] !== null) {
            $value_6 = $data['company'];
            if (is_null($data['company'])) {
                $value_6 = $data['company'];
            } elseif (is_string($data['company'])) {
                $value_6 = $data['company'];
            }
            $object->company = $value_6;
        }
        elseif (\array_key_exists('company', $data)) {
            $object->company = null;
        }
        if (\array_key_exists('street', $data)) {
            $object->street = $data['street'];
        }
        if (\array_key_exists('street_additional_info', $data)) {
            $object->streetAdditionalInfo = $data['street_additional_info'];
        }
        if (\array_key_exists('number', $data)) {
            $value_7 = $data['number'];
            if (is_string($data['number'])) {
                $value_7 = $data['number'];
            } elseif (is_int($data['number'])) {
                $value_7 = $data['number'];
            }
            $object->number = $value_7;
        }
        if (\array_key_exists('number_suffix', $data)) {
            $object->numberSuffix = $data['number_suffix'];
        }
        if (\array_key_exists('postal_code', $data)) {
            $object->postalCode = $data['postal_code'];
        }
        if (\array_key_exists('city', $data)) {
            $object->city = $data['city'];
        }
        if (\array_key_exists('cc', $data)) {
            $object->cc = $data['cc'];
        }
        if (\array_key_exists('region', $data)) {
            $object->region = $data['region'];
        }
        if (\array_key_exists('box_number', $data) && $data['box_number'] !== null) {
            $value_8 = $data['box_number'];
            if (is_null($data['box_number'])) {
                $value_8 = $data['box_number'];
            } elseif (is_string($data['box_number'])) {
                $value_8 = $data['box_number'];
            }
            $object->boxNumber = $value_8;
        }
        elseif (\array_key_exists('box_number', $data)) {
            $object->boxNumber = null;
        }
        return $object;
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $dataArray = [];
        if (array_key_exists('id', get_object_vars($data))) {
            $value = $data->id;
            if (is_int($data->id)) {
                $value = $data->id;
            }
            $dataArray['id'] = $value;
        }
        if (array_key_exists('gender', get_object_vars($data))) {
            $value_1 = $data->gender;
            if (is_null($data->gender)) {
                $value_1 = $data->gender;
            } elseif (is_string($data->gender)) {
                $value_1 = $data->gender;
            }
            $dataArray['gender'] = $value_1;
        }
        if (array_key_exists('firstName', get_object_vars($data))) {
            $value_2 = $data->firstName;
            if (is_null($data->firstName)) {
                $value_2 = $data->firstName;
            } elseif (is_string($data->firstName)) {
                $value_2 = $data->firstName;
            }
            $dataArray['first_name'] = $value_2;
        }
        if (null !== ($data->lastName ?? null)) {
            $value_3 = $data->lastName;
            if (is_null($data->lastName)) {
                $value_3 = $data->lastName;
            } elseif (is_string($data->lastName)) {
                $value_3 = $data->lastName;
            }
            $dataArray['last_name'] = $value_3;
        }
        if (null !== ($data->email ?? null)) {
            $value_4 = $data->email;
            if (is_null($data->email)) {
                $value_4 = $data->email;
            } elseif (is_string($data->email)) {
                $value_4 = $data->email;
            }
            $dataArray['email'] = $value_4;
        }
        if (null !== ($data->phone ?? null)) {
            $value_5 = $data->phone;
            if (is_null($data->phone)) {
                $value_5 = $data->phone;
            } elseif (is_string($data->phone)) {
                $value_5 = $data->phone;
            }
            $dataArray['phone'] = $value_5;
        }
        if (null !== ($data->company ?? null)) {
            $value_6 = $data->company;
            if (is_null($data->company)) {
                $value_6 = $data->company;
            } elseif (is_string($data->company)) {
                $value_6 = $data->company;
            }
            $dataArray['company'] = $value_6;
        }
        if (null !== ($data->street ?? null)) {
            $dataArray['street'] = $data->street;
        }
        if (array_key_exists('streetAdditionalInfo', get_object_vars($data))) {
            $dataArray['street_additional_info'] = $data->streetAdditionalInfo;
        }
        if (null !== ($data->number ?? null)) {
            $value_7 = $data->number;
            if (is_string($data->number)) {
                $value_7 = $data->number;
            } elseif (is_int($data->number)) {
                $value_7 = $data->number;
            }
            $dataArray['number'] = $value_7;
        }
        if (array_key_exists('numberSuffix', get_object_vars($data))) {
            $dataArray['number_suffix'] = $data->numberSuffix;
        }
        if (null !== ($data->postalCode ?? null)) {
            $dataArray['postal_code'] = $data->postalCode;
        }
        if (null !== ($data->city ?? null)) {
            $dataArray['city'] = $data->city;
        }
        if (null !== ($data->cc ?? null)) {
            $dataArray['cc'] = $data->cc;
        }
        if (array_key_exists('region', get_object_vars($data))) {
            $dataArray['region'] = $data->region;
        }
        if (array_key_exists('boxNumber', get_object_vars($data))) {
            $value_8 = $data->boxNumber;
            if (is_null($data->boxNumber)) {
                $value_8 = $data->boxNumber;
            } elseif (is_string($data->boxNumber)) {
                $value_8 = $data->boxNumber;
            }
            $dataArray['box_number'] = $value_8;
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\AccountDefsContactConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\AccountDefsContact::class => false];
    }
}