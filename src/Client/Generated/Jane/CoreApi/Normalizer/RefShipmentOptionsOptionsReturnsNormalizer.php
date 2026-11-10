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
class RefShipmentOptionsOptionsReturnsNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentOptionsOptionsReturns::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentOptionsOptionsReturns::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentOptionsOptionsReturns();
        if (null === $data || false === \is_array($data)) {
            return $object;
        }
        if (isset($data['$ref']) && !isset($data['type']) && !isset($data['properties']) && !isset($data['allOf'])) {
            return new Reference($data['$ref'], $context['document-origin']);
        }
        if (isset($data['$recursiveRef'])) {
            return new Reference($data['$recursiveRef'], $context['document-origin']);
        }
        if (\array_key_exists('age_check', $data)) {
            $object->ageCheck = is_int($data['age_check']) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesIntBoolean::tryFrom($data['age_check']) ?? $data['age_check'] : $data['age_check'];
            unset($data['age_check']);
        }
        if (\array_key_exists('cooled_delivery', $data)) {
            $object->cooledDelivery = is_int($data['cooled_delivery']) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesIntBoolean::tryFrom($data['cooled_delivery']) ?? $data['cooled_delivery'] : $data['cooled_delivery'];
            unset($data['cooled_delivery']);
        }
        if (\array_key_exists('drop_off_at_postal_point', $data)) {
            $object->dropOffAtPostalPoint = is_int($data['drop_off_at_postal_point']) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesIntBoolean::tryFrom($data['drop_off_at_postal_point']) ?? $data['drop_off_at_postal_point'] : $data['drop_off_at_postal_point'];
            unset($data['drop_off_at_postal_point']);
        }
        if (\array_key_exists('extra_assurance', $data)) {
            $object->extraAssurance = is_int($data['extra_assurance']) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesIntBoolean::tryFrom($data['extra_assurance']) ?? $data['extra_assurance'] : $data['extra_assurance'];
            unset($data['extra_assurance']);
        }
        if (\array_key_exists('hide_sender', $data)) {
            $object->hideSender = is_int($data['hide_sender']) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesIntBoolean::tryFrom($data['hide_sender']) ?? $data['hide_sender'] : $data['hide_sender'];
            unset($data['hide_sender']);
        }
        if (\array_key_exists('large_format', $data)) {
            $object->largeFormat = is_int($data['large_format']) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesIntBoolean::tryFrom($data['large_format']) ?? $data['large_format'] : $data['large_format'];
            unset($data['large_format']);
        }
        if (\array_key_exists('only_recipient', $data)) {
            $object->onlyRecipient = is_int($data['only_recipient']) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesIntBoolean::tryFrom($data['only_recipient']) ?? $data['only_recipient'] : $data['only_recipient'];
            unset($data['only_recipient']);
        }
        if (\array_key_exists('receipt_code', $data)) {
            $object->receiptCode = is_int($data['receipt_code']) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesIntBoolean::tryFrom($data['receipt_code']) ?? $data['receipt_code'] : $data['receipt_code'];
            unset($data['receipt_code']);
        }
        if (\array_key_exists('return', $data)) {
            $object->return = is_int($data['return']) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesIntBoolean::tryFrom($data['return']) ?? $data['return'] : $data['return'];
            unset($data['return']);
        }
        if (\array_key_exists('same_day_delivery', $data)) {
            $object->sameDayDelivery = is_int($data['same_day_delivery']) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesIntBoolean::tryFrom($data['same_day_delivery']) ?? $data['same_day_delivery'] : $data['same_day_delivery'];
            unset($data['same_day_delivery']);
        }
        if (\array_key_exists('saturday_delivery', $data)) {
            $object->saturdayDelivery = is_int($data['saturday_delivery']) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesIntBoolean::tryFrom($data['saturday_delivery']) ?? $data['saturday_delivery'] : $data['saturday_delivery'];
            unset($data['saturday_delivery']);
        }
        if (\array_key_exists('signature', $data)) {
            $object->signature = is_int($data['signature']) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesIntBoolean::tryFrom($data['signature']) ?? $data['signature'] : $data['signature'];
            unset($data['signature']);
        }
        foreach ($data as $key => $value) {
            if (preg_match('/.*/', (string) $key)) {
                $object[$key] = $value;
            }
        }
        return $object;
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $dataArray = [];
        if (array_key_exists('ageCheck', get_object_vars($data))) {
            $dataArray['age_check'] = $data->ageCheck instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesIntBoolean ? $data->ageCheck->value : $data->ageCheck;
        }
        if (array_key_exists('cooledDelivery', get_object_vars($data))) {
            $dataArray['cooled_delivery'] = $data->cooledDelivery instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesIntBoolean ? $data->cooledDelivery->value : $data->cooledDelivery;
        }
        if (array_key_exists('dropOffAtPostalPoint', get_object_vars($data))) {
            $dataArray['drop_off_at_postal_point'] = $data->dropOffAtPostalPoint instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesIntBoolean ? $data->dropOffAtPostalPoint->value : $data->dropOffAtPostalPoint;
        }
        if (array_key_exists('extraAssurance', get_object_vars($data))) {
            $dataArray['extra_assurance'] = $data->extraAssurance instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesIntBoolean ? $data->extraAssurance->value : $data->extraAssurance;
        }
        if (array_key_exists('hideSender', get_object_vars($data))) {
            $dataArray['hide_sender'] = $data->hideSender instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesIntBoolean ? $data->hideSender->value : $data->hideSender;
        }
        if (array_key_exists('largeFormat', get_object_vars($data))) {
            $dataArray['large_format'] = $data->largeFormat instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesIntBoolean ? $data->largeFormat->value : $data->largeFormat;
        }
        if (array_key_exists('onlyRecipient', get_object_vars($data))) {
            $dataArray['only_recipient'] = $data->onlyRecipient instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesIntBoolean ? $data->onlyRecipient->value : $data->onlyRecipient;
        }
        if (array_key_exists('receiptCode', get_object_vars($data))) {
            $dataArray['receipt_code'] = $data->receiptCode instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesIntBoolean ? $data->receiptCode->value : $data->receiptCode;
        }
        if (array_key_exists('return', get_object_vars($data))) {
            $dataArray['return'] = $data->return instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesIntBoolean ? $data->return->value : $data->return;
        }
        if (array_key_exists('sameDayDelivery', get_object_vars($data))) {
            $dataArray['same_day_delivery'] = $data->sameDayDelivery instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesIntBoolean ? $data->sameDayDelivery->value : $data->sameDayDelivery;
        }
        if (array_key_exists('saturdayDelivery', get_object_vars($data))) {
            $dataArray['saturday_delivery'] = $data->saturdayDelivery instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesIntBoolean ? $data->saturdayDelivery->value : $data->saturdayDelivery;
        }
        if (array_key_exists('signature', get_object_vars($data))) {
            $dataArray['signature'] = $data->signature instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesIntBoolean ? $data->signature->value : $data->signature;
        }
        foreach ($data->additionalPropertyEntries() as $key => $value) {
            if (preg_match('/.*/', (string) $key)) {
                $dataArray[$key] = $value;
            }
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\RefShipmentOptionsOptionsReturnsConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentOptionsOptionsReturns::class => false];
    }
}