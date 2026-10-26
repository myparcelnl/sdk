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
class ShipmentDefsShipmentRecipientNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentRecipient::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentRecipient::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentRecipient();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\ShipmentDefsShipmentRecipientConstraint());
        }
        if (\array_key_exists('person', $data)) {
            $object->person = $data['person'];
            unset($data['person']);
        }
        if (\array_key_exists('first_name', $data)) {
            $object->firstName = $data['first_name'];
            unset($data['first_name']);
        }
        if (\array_key_exists('last_name', $data)) {
            $object->lastName = $data['last_name'];
            unset($data['last_name']);
        }
        if (\array_key_exists('company', $data) && $data['company'] !== null) {
            $value = $data['company'];
            if (is_null($data['company'])) {
                $value = $data['company'];
            } elseif (is_string($data['company'])) {
                $value = $data['company'];
            }
            $object->company = $value;
            unset($data['company']);
        }
        elseif (\array_key_exists('company', $data)) {
            $object->company = null;
            unset($data['company']);
        }
        if (\array_key_exists('street', $data)) {
            $object->street = $data['street'];
            unset($data['street']);
        }
        if (\array_key_exists('street_additional_info', $data) && $data['street_additional_info'] !== null) {
            $value_1 = $data['street_additional_info'];
            if (is_null($data['street_additional_info'])) {
                $value_1 = $data['street_additional_info'];
            } elseif (is_string($data['street_additional_info'])) {
                $value_1 = $data['street_additional_info'];
            }
            $object->streetAdditionalInfo = $value_1;
            unset($data['street_additional_info']);
        }
        elseif (\array_key_exists('street_additional_info', $data)) {
            $object->streetAdditionalInfo = null;
            unset($data['street_additional_info']);
        }
        if (\array_key_exists('number', $data) && $data['number'] !== null) {
            $value_2 = $data['number'];
            if (is_null($data['number'])) {
                $value_2 = $data['number'];
            } elseif (is_string($data['number'])) {
                $value_2 = $data['number'];
            }
            $object->number = $value_2;
            unset($data['number']);
        }
        elseif (\array_key_exists('number', $data)) {
            $object->number = null;
            unset($data['number']);
        }
        if (\array_key_exists('number_suffix', $data) && $data['number_suffix'] !== null) {
            $value_3 = $data['number_suffix'];
            if (is_null($data['number_suffix'])) {
                $value_3 = $data['number_suffix'];
            } elseif (is_string($data['number_suffix'])) {
                $value_3 = $data['number_suffix'];
            }
            $object->numberSuffix = $value_3;
            unset($data['number_suffix']);
        }
        elseif (\array_key_exists('number_suffix', $data)) {
            $object->numberSuffix = null;
            unset($data['number_suffix']);
        }
        if (\array_key_exists('postal_code', $data)) {
            $object->postalCode = $data['postal_code'];
            unset($data['postal_code']);
        }
        if (\array_key_exists('city', $data)) {
            $object->city = $data['city'];
            unset($data['city']);
        }
        if (\array_key_exists('state', $data)) {
            $object->state = $data['state'];
            unset($data['state']);
        }
        if (\array_key_exists('area', $data)) {
            $object->area = $data['area'];
            unset($data['area']);
        }
        if (\array_key_exists('phone', $data) && $data['phone'] !== null) {
            $value_4 = $data['phone'];
            if (is_null($data['phone'])) {
                $value_4 = $data['phone'];
            } elseif (is_string($data['phone'])) {
                $value_4 = $data['phone'];
            }
            $object->phone = $value_4;
            unset($data['phone']);
        }
        elseif (\array_key_exists('phone', $data)) {
            $object->phone = null;
            unset($data['phone']);
        }
        if (\array_key_exists('account_id', $data)) {
            $object->accountId = $data['account_id'];
            unset($data['account_id']);
        }
        if (\array_key_exists('cc', $data)) {
            $object->cc = $data['cc'];
            unset($data['cc']);
        }
        if (\array_key_exists('region', $data) && $data['region'] !== null) {
            $value_5 = $data['region'];
            if (is_null($data['region'])) {
                $value_5 = $data['region'];
            } elseif (is_string($data['region'])) {
                $value_5 = $data['region'];
            }
            $object->region = $value_5;
            unset($data['region']);
        }
        elseif (\array_key_exists('region', $data)) {
            $object->region = null;
            unset($data['region']);
        }
        if (\array_key_exists('email', $data) && $data['email'] !== null) {
            $value_6 = $data['email'];
            if (is_null($data['email'])) {
                $value_6 = $data['email'];
            } elseif (is_string($data['email'])) {
                $value_6 = $data['email'];
            }
            $object->email = $value_6;
            unset($data['email']);
        }
        elseif (\array_key_exists('email', $data)) {
            $object->email = null;
            unset($data['email']);
        }
        if (\array_key_exists('billing_email', $data) && $data['billing_email'] !== null) {
            $value_7 = $data['billing_email'];
            if (is_null($data['billing_email'])) {
                $value_7 = $data['billing_email'];
            } elseif (is_string($data['billing_email'])) {
                $value_7 = $data['billing_email'];
            }
            $object->billingEmail = $value_7;
            unset($data['billing_email']);
        }
        elseif (\array_key_exists('billing_email', $data)) {
            $object->billingEmail = null;
            unset($data['billing_email']);
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
            unset($data['secondary_emails']);
        }
        elseif (\array_key_exists('secondary_emails', $data)) {
            $object->secondaryEmails = null;
            unset($data['secondary_emails']);
        }
        if (\array_key_exists('box_number', $data) && $data['box_number'] !== null) {
            $value_10 = $data['box_number'];
            if (is_null($data['box_number'])) {
                $value_10 = $data['box_number'];
            } elseif (is_string($data['box_number'])) {
                $value_10 = $data['box_number'];
            }
            $object->boxNumber = $value_10;
            unset($data['box_number']);
        }
        elseif (\array_key_exists('box_number', $data)) {
            $object->boxNumber = null;
            unset($data['box_number']);
        }
        if (\array_key_exists('eori_number', $data)) {
            $object->eoriNumber = $data['eori_number'];
            unset($data['eori_number']);
        }
        if (\array_key_exists('vat_number', $data)) {
            $object->vatNumber = $data['vat_number'];
            unset($data['vat_number']);
        }
        if (\array_key_exists('invalid', $data)) {
            $object->invalid = $data['invalid'];
            unset($data['invalid']);
        }
        foreach ($data as $key => $value_11) {
            if (preg_match('/.*/', (string) $key)) {
                $object[$key] = $value_11;
            }
        }
        return $object;
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $dataArray = [];
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
        if (array_key_exists('area', get_object_vars($data))) {
            $dataArray['area'] = $data->area;
        }
        if (array_key_exists('phone', get_object_vars($data))) {
            $value_4 = $data->phone;
            if (is_null($data->phone)) {
                $value_4 = $data->phone;
            } elseif (is_string($data->phone)) {
                $value_4 = $data->phone;
            }
            $dataArray['phone'] = $value_4;
        }
        if (array_key_exists('accountId', get_object_vars($data))) {
            $dataArray['account_id'] = $data->accountId;
        }
        if (null !== ($data->cc ?? null)) {
            $dataArray['cc'] = $data->cc;
        }
        if (array_key_exists('region', get_object_vars($data))) {
            $value_5 = $data->region;
            if (is_null($data->region)) {
                $value_5 = $data->region;
            } elseif (is_string($data->region)) {
                $value_5 = $data->region;
            }
            $dataArray['region'] = $value_5;
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
        foreach ($data->additionalPropertyEntries() as $key => $value_11) {
            if (preg_match('/.*/', (string) $key)) {
                $dataArray[$key] = $value_11;
            }
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\ShipmentDefsShipmentRecipientConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentRecipient::class => false];
    }
}