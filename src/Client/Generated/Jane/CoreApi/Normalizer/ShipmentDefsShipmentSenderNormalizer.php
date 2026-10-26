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
class ShipmentDefsShipmentSenderNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentSender::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentSender::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentSender();
        if (null === $data || false === \is_array($data)) {
            return $object;
        }
        if (isset($data['$ref']) && !isset($data['type']) && !isset($data['properties']) && !isset($data['allOf'])) {
            return new Reference($data['$ref'], $context['document-origin']);
        }
        if (isset($data['$recursiveRef'])) {
            return new Reference($data['$recursiveRef'], $context['document-origin']);
        }
        if (\array_key_exists('invalid', $data) && \is_int($data['invalid'])) {
            $data['invalid'] = (bool) $data['invalid'];
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\ShipmentDefsShipmentSenderConstraint());
        }
        if (\array_key_exists('account_id', $data)) {
            $object->accountId = $data['account_id'];
        }
        if (\array_key_exists('person', $data)) {
            $object->person = $data['person'];
        }
        if (\array_key_exists('first_name', $data)) {
            $object->firstName = $data['first_name'];
        }
        if (\array_key_exists('last_name', $data)) {
            $object->lastName = $data['last_name'];
        }
        if (\array_key_exists('company', $data) && $data['company'] !== null) {
            $value = $data['company'];
            if (is_null($data['company'])) {
                $value = $data['company'];
            } elseif (is_string($data['company'])) {
                $value = $data['company'];
            }
            $object->company = $value;
        }
        elseif (\array_key_exists('company', $data)) {
            $object->company = null;
        }
        if (\array_key_exists('street', $data)) {
            $object->street = $data['street'];
        }
        if (\array_key_exists('street_additional_info', $data) && $data['street_additional_info'] !== null) {
            $value_1 = $data['street_additional_info'];
            if (is_null($data['street_additional_info'])) {
                $value_1 = $data['street_additional_info'];
            } elseif (is_string($data['street_additional_info'])) {
                $value_1 = $data['street_additional_info'];
            }
            $object->streetAdditionalInfo = $value_1;
        }
        elseif (\array_key_exists('street_additional_info', $data)) {
            $object->streetAdditionalInfo = null;
        }
        if (\array_key_exists('number', $data) && $data['number'] !== null) {
            $value_2 = $data['number'];
            if (is_null($data['number'])) {
                $value_2 = $data['number'];
            } elseif (is_string($data['number'])) {
                $value_2 = $data['number'];
            }
            $object->number = $value_2;
        }
        elseif (\array_key_exists('number', $data)) {
            $object->number = null;
        }
        if (\array_key_exists('number_suffix', $data) && $data['number_suffix'] !== null) {
            $value_3 = $data['number_suffix'];
            if (is_null($data['number_suffix'])) {
                $value_3 = $data['number_suffix'];
            } elseif (is_string($data['number_suffix'])) {
                $value_3 = $data['number_suffix'];
            }
            $object->numberSuffix = $value_3;
        }
        elseif (\array_key_exists('number_suffix', $data)) {
            $object->numberSuffix = null;
        }
        if (\array_key_exists('postal_code', $data)) {
            $object->postalCode = $data['postal_code'];
        }
        if (\array_key_exists('city', $data)) {
            $object->city = $data['city'];
        }
        if (\array_key_exists('state', $data)) {
            $object->state = $data['state'];
        }
        if (\array_key_exists('cc', $data)) {
            $object->cc = $data['cc'];
        }
        if (\array_key_exists('region', $data) && $data['region'] !== null) {
            $value_4 = $data['region'];
            if (is_null($data['region'])) {
                $value_4 = $data['region'];
            } elseif (is_string($data['region'])) {
                $value_4 = $data['region'];
            }
            $object->region = $value_4;
        }
        elseif (\array_key_exists('region', $data)) {
            $object->region = null;
        }
        if (\array_key_exists('area', $data)) {
            $object->area = $data['area'];
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
        if (\array_key_exists('email', $data) && $data['email'] !== null) {
            $value_6 = $data['email'];
            if (is_null($data['email'])) {
                $value_6 = $data['email'];
            } elseif (is_string($data['email'])) {
                $value_6 = $data['email'];
            }
            $object->email = $value_6;
        }
        elseif (\array_key_exists('email', $data)) {
            $object->email = null;
        }
        if (\array_key_exists('billing_email', $data) && $data['billing_email'] !== null) {
            $value_7 = $data['billing_email'];
            if (is_null($data['billing_email'])) {
                $value_7 = $data['billing_email'];
            } elseif (is_string($data['billing_email'])) {
                $value_7 = $data['billing_email'];
            }
            $object->billingEmail = $value_7;
        }
        elseif (\array_key_exists('billing_email', $data)) {
            $object->billingEmail = null;
        }
        if (\array_key_exists('secondary_emails', $data) && $data['secondary_emails'] !== null) {
            $values = [];
            foreach ($data['secondary_emails'] as $value_8) {
                $value_9 = $value_8;
                if (is_null($value_8)) {
                    $value_9 = $value_8;
                } elseif (is_string($value_8)) {
                    $value_9 = $value_8;
                }
                $values[] = $value_9;
            }
            $object->secondaryEmails = $values;
        }
        elseif (\array_key_exists('secondary_emails', $data)) {
            $object->secondaryEmails = null;
        }
        if (\array_key_exists('box_number', $data) && $data['box_number'] !== null) {
            $value_10 = $data['box_number'];
            if (is_null($data['box_number'])) {
                $value_10 = $data['box_number'];
            } elseif (is_string($data['box_number'])) {
                $value_10 = $data['box_number'];
            }
            $object->boxNumber = $value_10;
        }
        elseif (\array_key_exists('box_number', $data)) {
            $object->boxNumber = null;
        }
        if (\array_key_exists('eori_number', $data)) {
            $object->eoriNumber = $data['eori_number'];
        }
        if (\array_key_exists('vat_number', $data)) {
            $object->vatNumber = $data['vat_number'];
        }
        if (\array_key_exists('invalid', $data)) {
            $object->invalid = $data['invalid'];
        }
        return $object;
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $dataArray = [];
        if (array_key_exists('accountId', get_object_vars($data))) {
            $dataArray['account_id'] = $data->accountId;
        }
        if (array_key_exists('person', get_object_vars($data))) {
            $dataArray['person'] = $data->person;
        }
        if (array_key_exists('firstName', get_object_vars($data))) {
            $dataArray['first_name'] = $data->firstName;
        }
        if (array_key_exists('lastName', get_object_vars($data))) {
            $dataArray['last_name'] = $data->lastName;
        }
        if (array_key_exists('company', get_object_vars($data))) {
            $value = $data->company;
            if (is_null($data->company)) {
                $value = $data->company;
            } elseif (is_string($data->company)) {
                $value = $data->company;
            }
            $dataArray['company'] = $value;
        }
        if (array_key_exists('street', get_object_vars($data))) {
            $dataArray['street'] = $data->street;
        }
        if (array_key_exists('streetAdditionalInfo', get_object_vars($data))) {
            $value_1 = $data->streetAdditionalInfo;
            if (is_null($data->streetAdditionalInfo)) {
                $value_1 = $data->streetAdditionalInfo;
            } elseif (is_string($data->streetAdditionalInfo)) {
                $value_1 = $data->streetAdditionalInfo;
            }
            $dataArray['street_additional_info'] = $value_1;
        }
        if (array_key_exists('number', get_object_vars($data))) {
            $value_2 = $data->number;
            if (is_null($data->number)) {
                $value_2 = $data->number;
            } elseif (is_string($data->number)) {
                $value_2 = $data->number;
            }
            $dataArray['number'] = $value_2;
        }
        if (array_key_exists('numberSuffix', get_object_vars($data))) {
            $value_3 = $data->numberSuffix;
            if (is_null($data->numberSuffix)) {
                $value_3 = $data->numberSuffix;
            } elseif (is_string($data->numberSuffix)) {
                $value_3 = $data->numberSuffix;
            }
            $dataArray['number_suffix'] = $value_3;
        }
        if (array_key_exists('postalCode', get_object_vars($data))) {
            $dataArray['postal_code'] = $data->postalCode;
        }
        if (array_key_exists('city', get_object_vars($data))) {
            $dataArray['city'] = $data->city;
        }
        if (array_key_exists('state', get_object_vars($data))) {
            $dataArray['state'] = $data->state;
        }
        if (null !== ($data->cc ?? null)) {
            $dataArray['cc'] = $data->cc;
        }
        if (array_key_exists('region', get_object_vars($data))) {
            $value_4 = $data->region;
            if (is_null($data->region)) {
                $value_4 = $data->region;
            } elseif (is_string($data->region)) {
                $value_4 = $data->region;
            }
            $dataArray['region'] = $value_4;
        }
        if (array_key_exists('area', get_object_vars($data))) {
            $dataArray['area'] = $data->area;
        }
        if (array_key_exists('phone', get_object_vars($data))) {
            $value_5 = $data->phone;
            if (is_null($data->phone)) {
                $value_5 = $data->phone;
            } elseif (is_string($data->phone)) {
                $value_5 = $data->phone;
            }
            $dataArray['phone'] = $value_5;
        }
        if (array_key_exists('email', get_object_vars($data))) {
            $value_6 = $data->email;
            if (is_null($data->email)) {
                $value_6 = $data->email;
            } elseif (is_string($data->email)) {
                $value_6 = $data->email;
            }
            $dataArray['email'] = $value_6;
        }
        if (array_key_exists('billingEmail', get_object_vars($data))) {
            $value_7 = $data->billingEmail;
            if (is_null($data->billingEmail)) {
                $value_7 = $data->billingEmail;
            } elseif (is_string($data->billingEmail)) {
                $value_7 = $data->billingEmail;
            }
            $dataArray['billing_email'] = $value_7;
        }
        if (array_key_exists('secondaryEmails', get_object_vars($data))) {
            $values = [];
            foreach ($data->secondaryEmails as $value_8) {
                $value_9 = $value_8;
                if (is_null($value_8)) {
                    $value_9 = $value_8;
                } elseif (is_string($value_8)) {
                    $value_9 = $value_8;
                }
                $values[] = $value_9;
            }
            $dataArray['secondary_emails'] = $values;
        }
        if (array_key_exists('boxNumber', get_object_vars($data))) {
            $value_10 = $data->boxNumber;
            if (is_null($data->boxNumber)) {
                $value_10 = $data->boxNumber;
            } elseif (is_string($data->boxNumber)) {
                $value_10 = $data->boxNumber;
            }
            $dataArray['box_number'] = $value_10;
        }
        if (array_key_exists('eoriNumber', get_object_vars($data))) {
            $dataArray['eori_number'] = $data->eoriNumber;
        }
        if (array_key_exists('vatNumber', get_object_vars($data))) {
            $dataArray['vat_number'] = $data->vatNumber;
        }
        if (array_key_exists('invalid', get_object_vars($data))) {
            $dataArray['invalid'] = $data->invalid;
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\ShipmentDefsShipmentSenderConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentSender::class => false];
    }
}