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
class ShipmentPutShipmentsRequestV11DataShipmentsItemRecipientNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPutShipmentsRequestV11DataShipmentsItemRecipient::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPutShipmentsRequestV11DataShipmentsItemRecipient::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPutShipmentsRequestV11DataShipmentsItemRecipient();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\ShipmentPutShipmentsRequestV11DataShipmentsItemRecipientConstraint());
        }
        if (\array_key_exists('cc', $data)) {
            $object->cc = $data['cc'];
        }
        if (\array_key_exists('region', $data)) {
            $object->region = $data['region'];
        }
        if (\array_key_exists('state', $data)) {
            $object->state = $data['state'];
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
        if (\array_key_exists('street_additional_info', $data)) {
            $object->streetAdditionalInfo = $data['street_additional_info'];
        }
        if (\array_key_exists('number', $data) && $data['number'] !== null) {
            $value = $data['number'];
            if (is_string($data['number'])) {
                $value = $data['number'];
            } elseif (is_null($data['number'])) {
                $value = $data['number'];
            }
            $object->number = $value;
        }
        elseif (\array_key_exists('number', $data)) {
            $object->number = null;
        }
        if (\array_key_exists('number_suffix', $data) && $data['number_suffix'] !== null) {
            $value_1 = $data['number_suffix'];
            if (is_string($data['number_suffix'])) {
                $value_1 = $data['number_suffix'];
            } elseif (is_null($data['number_suffix'])) {
                $value_1 = $data['number_suffix'];
            }
            $object->numberSuffix = $value_1;
        }
        elseif (\array_key_exists('number_suffix', $data)) {
            $object->numberSuffix = null;
        }
        if (\array_key_exists('box_number', $data)) {
            $object->boxNumber = $data['box_number'];
        }
        if (\array_key_exists('person', $data)) {
            $object->person = $data['person'];
        }
        if (\array_key_exists('company', $data)) {
            $object->company = $data['company'];
        }
        if (\array_key_exists('email', $data)) {
            $object->email = $data['email'];
        }
        if (\array_key_exists('secondary_emails', $data)) {
            $values = [];
            foreach ($data['secondary_emails'] as $value_2) {
                $values[] = $value_2;
            }
            $object->secondaryEmails = $values;
        }
        if (\array_key_exists('phone', $data)) {
            $object->phone = $data['phone'];
        }
        if (\array_key_exists('eori_number', $data) && $data['eori_number'] !== null) {
            $value_3 = $data['eori_number'];
            if (is_string($data['eori_number'])) {
                $value_3 = $data['eori_number'];
            } elseif (is_null($data['eori_number'])) {
                $value_3 = $data['eori_number'];
            }
            $object->eoriNumber = $value_3;
        }
        elseif (\array_key_exists('eori_number', $data)) {
            $object->eoriNumber = null;
        }
        if (\array_key_exists('vat_number', $data) && $data['vat_number'] !== null) {
            $value_4 = $data['vat_number'];
            if (is_string($data['vat_number'])) {
                $value_4 = $data['vat_number'];
            } elseif (is_null($data['vat_number'])) {
                $value_4 = $data['vat_number'];
            }
            $object->vatNumber = $value_4;
        }
        elseif (\array_key_exists('vat_number', $data)) {
            $object->vatNumber = null;
        }
        return $object;
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $dataArray = [];
        if (null !== ($data->cc ?? null)) {
            $dataArray['cc'] = $data->cc;
        }
        if (array_key_exists('region', get_object_vars($data))) {
            $dataArray['region'] = $data->region;
        }
        if (array_key_exists('state', get_object_vars($data))) {
            $dataArray['state'] = $data->state;
        }
        if (array_key_exists('postalCode', get_object_vars($data))) {
            $dataArray['postal_code'] = $data->postalCode;
        }
        if (null !== ($data->city ?? null)) {
            $dataArray['city'] = $data->city;
        }
        if (null !== ($data->street ?? null)) {
            $dataArray['street'] = $data->street;
        }
        if (array_key_exists('streetAdditionalInfo', get_object_vars($data))) {
            $dataArray['street_additional_info'] = $data->streetAdditionalInfo;
        }
        if (array_key_exists('number', get_object_vars($data))) {
            $value = $data->number;
            if (is_string($data->number)) {
                $value = $data->number;
            } elseif (is_null($data->number)) {
                $value = $data->number;
            }
            $dataArray['number'] = $value;
        }
        if (array_key_exists('numberSuffix', get_object_vars($data))) {
            $value_1 = $data->numberSuffix;
            if (is_string($data->numberSuffix)) {
                $value_1 = $data->numberSuffix;
            } elseif (is_null($data->numberSuffix)) {
                $value_1 = $data->numberSuffix;
            }
            $dataArray['number_suffix'] = $value_1;
        }
        if (array_key_exists('boxNumber', get_object_vars($data))) {
            $dataArray['box_number'] = $data->boxNumber;
        }
        if (null !== ($data->person ?? null)) {
            $dataArray['person'] = $data->person;
        }
        if (array_key_exists('company', get_object_vars($data))) {
            $dataArray['company'] = $data->company;
        }
        if (array_key_exists('email', get_object_vars($data))) {
            $dataArray['email'] = $data->email;
        }
        if (array_key_exists('secondaryEmails', get_object_vars($data))) {
            $values = [];
            foreach ($data->secondaryEmails as $value_2) {
                $values[] = $value_2;
            }
            $dataArray['secondary_emails'] = $values;
        }
        if (array_key_exists('phone', get_object_vars($data))) {
            $dataArray['phone'] = $data->phone;
        }
        if (array_key_exists('eoriNumber', get_object_vars($data))) {
            $value_3 = $data->eoriNumber;
            if (is_string($data->eoriNumber)) {
                $value_3 = $data->eoriNumber;
            } elseif (is_null($data->eoriNumber)) {
                $value_3 = $data->eoriNumber;
            }
            $dataArray['eori_number'] = $value_3;
        }
        if (array_key_exists('vatNumber', get_object_vars($data))) {
            $value_4 = $data->vatNumber;
            if (is_string($data->vatNumber)) {
                $value_4 = $data->vatNumber;
            } elseif (is_null($data->vatNumber)) {
                $value_4 = $data->vatNumber;
            }
            $dataArray['vat_number'] = $value_4;
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\ShipmentPutShipmentsRequestV11DataShipmentsItemRecipientConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPutShipmentsRequestV11DataShipmentsItemRecipient::class => false];
    }
}