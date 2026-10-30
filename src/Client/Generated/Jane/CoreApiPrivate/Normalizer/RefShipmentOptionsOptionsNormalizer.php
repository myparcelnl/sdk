<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer;

use Jane\Component\JsonSchemaRuntime\Reference;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Runtime\Normalizer\CheckArray;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Runtime\Normalizer\ValidatorTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
class RefShipmentOptionsOptionsNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefShipmentOptionsOptions::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefShipmentOptionsOptions::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefShipmentOptionsOptions();
        if (null === $data || false === \is_array($data)) {
            return $object;
        }
        if (isset($data['$ref']) && !isset($data['type']) && !isset($data['properties']) && !isset($data['allOf'])) {
            return new Reference($data['$ref'], $context['document-origin']);
        }
        if (isset($data['$recursiveRef'])) {
            return new Reference($data['$recursiveRef'], $context['document-origin']);
        }
        if (\array_key_exists('age_check', $data) && \is_int($data['age_check'])) {
            $data['age_check'] = (float) $data['age_check'];
        }
        if (\array_key_exists('collect', $data) && \is_int($data['collect'])) {
            $data['collect'] = (float) $data['collect'];
        }
        if (\array_key_exists('cooled_delivery', $data) && \is_int($data['cooled_delivery'])) {
            $data['cooled_delivery'] = (float) $data['cooled_delivery'];
        }
        if (\array_key_exists('drop_off_at_postal_point', $data) && \is_int($data['drop_off_at_postal_point'])) {
            $data['drop_off_at_postal_point'] = (float) $data['drop_off_at_postal_point'];
        }
        if (\array_key_exists('extra_assurance', $data) && \is_int($data['extra_assurance'])) {
            $data['extra_assurance'] = (float) $data['extra_assurance'];
        }
        if (\array_key_exists('fresh_food', $data) && \is_int($data['fresh_food'])) {
            $data['fresh_food'] = (float) $data['fresh_food'];
        }
        if (\array_key_exists('frozen', $data) && \is_int($data['frozen'])) {
            $data['frozen'] = (float) $data['frozen'];
        }
        if (\array_key_exists('hide_sender', $data) && \is_int($data['hide_sender'])) {
            $data['hide_sender'] = (float) $data['hide_sender'];
        }
        if (\array_key_exists('large_format', $data) && \is_int($data['large_format'])) {
            $data['large_format'] = (float) $data['large_format'];
        }
        if (\array_key_exists('only_recipient', $data) && \is_int($data['only_recipient'])) {
            $data['only_recipient'] = (float) $data['only_recipient'];
        }
        if (\array_key_exists('priority_delivery', $data) && \is_int($data['priority_delivery'])) {
            $data['priority_delivery'] = (float) $data['priority_delivery'];
        }
        if (\array_key_exists('receipt_code', $data) && \is_int($data['receipt_code'])) {
            $data['receipt_code'] = (float) $data['receipt_code'];
        }
        if (\array_key_exists('return', $data) && \is_int($data['return'])) {
            $data['return'] = (float) $data['return'];
        }
        if (\array_key_exists('same_day_delivery', $data) && \is_int($data['same_day_delivery'])) {
            $data['same_day_delivery'] = (float) $data['same_day_delivery'];
        }
        if (\array_key_exists('saturday_delivery', $data) && \is_int($data['saturday_delivery'])) {
            $data['saturday_delivery'] = (float) $data['saturday_delivery'];
        }
        if (\array_key_exists('signature', $data) && \is_int($data['signature'])) {
            $data['signature'] = (float) $data['signature'];
        }
        if (\array_key_exists('tracked', $data) && \is_int($data['tracked'])) {
            $data['tracked'] = (float) $data['tracked'];
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Validator\RefShipmentOptionsOptionsConstraint());
        }
        if (\array_key_exists('age_check', $data)) {
            $object->ageCheck = $data['age_check'];
            unset($data['age_check']);
        }
        if (\array_key_exists('cash_on_delivery', $data)) {
            $object->cashOnDelivery = $this->denormalizer->denormalize($data['cash_on_delivery'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefTypesMoney::class, 'json', $context);
            unset($data['cash_on_delivery']);
        }
        if (\array_key_exists('collect', $data)) {
            $object->collect = $data['collect'];
            unset($data['collect']);
        }
        if (\array_key_exists('cooled_delivery', $data)) {
            $object->cooledDelivery = $data['cooled_delivery'];
            unset($data['cooled_delivery']);
        }
        if (\array_key_exists('drop_off_at_postal_point', $data)) {
            $object->dropOffAtPostalPoint = $data['drop_off_at_postal_point'];
            unset($data['drop_off_at_postal_point']);
        }
        if (\array_key_exists('extra_assurance', $data)) {
            $object->extraAssurance = $data['extra_assurance'];
            unset($data['extra_assurance']);
        }
        if (\array_key_exists('fresh_food', $data)) {
            $object->freshFood = $data['fresh_food'];
            unset($data['fresh_food']);
        }
        if (\array_key_exists('frozen', $data)) {
            $object->frozen = $data['frozen'];
            unset($data['frozen']);
        }
        if (\array_key_exists('hide_sender', $data)) {
            $object->hideSender = $data['hide_sender'];
            unset($data['hide_sender']);
        }
        if (\array_key_exists('large_format', $data)) {
            $object->largeFormat = $data['large_format'];
            unset($data['large_format']);
        }
        if (\array_key_exists('only_recipient', $data)) {
            $object->onlyRecipient = $data['only_recipient'];
            unset($data['only_recipient']);
        }
        if (\array_key_exists('priority_delivery', $data)) {
            $object->priorityDelivery = $data['priority_delivery'];
            unset($data['priority_delivery']);
        }
        if (\array_key_exists('receipt_code', $data)) {
            $object->receiptCode = $data['receipt_code'];
            unset($data['receipt_code']);
        }
        if (\array_key_exists('return', $data)) {
            $object->return = $data['return'];
            unset($data['return']);
        }
        if (\array_key_exists('same_day_delivery', $data)) {
            $object->sameDayDelivery = $data['same_day_delivery'];
            unset($data['same_day_delivery']);
        }
        if (\array_key_exists('saturday_delivery', $data)) {
            $object->saturdayDelivery = $data['saturday_delivery'];
            unset($data['saturday_delivery']);
        }
        if (\array_key_exists('signature', $data)) {
            $object->signature = $data['signature'];
            unset($data['signature']);
        }
        if (\array_key_exists('tracked', $data)) {
            $object->tracked = $data['tracked'];
            unset($data['tracked']);
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
            $dataArray['age_check'] = $data->ageCheck;
        }
        if (array_key_exists('cashOnDelivery', get_object_vars($data))) {
            $normalized = $data->cashOnDelivery === null ? null : $this->normalizer->normalize($data->cashOnDelivery, 'json', $context);
            $dataArray['cash_on_delivery'] = \is_iterable($normalized) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Runtime\JsonObject($normalized) : $normalized;
        }
        if (array_key_exists('collect', get_object_vars($data))) {
            $dataArray['collect'] = $data->collect;
        }
        if (array_key_exists('cooledDelivery', get_object_vars($data))) {
            $dataArray['cooled_delivery'] = $data->cooledDelivery;
        }
        if (array_key_exists('dropOffAtPostalPoint', get_object_vars($data))) {
            $dataArray['drop_off_at_postal_point'] = $data->dropOffAtPostalPoint;
        }
        if (array_key_exists('extraAssurance', get_object_vars($data))) {
            $dataArray['extra_assurance'] = $data->extraAssurance;
        }
        if (array_key_exists('freshFood', get_object_vars($data))) {
            $dataArray['fresh_food'] = $data->freshFood;
        }
        if (array_key_exists('frozen', get_object_vars($data))) {
            $dataArray['frozen'] = $data->frozen;
        }
        if (array_key_exists('hideSender', get_object_vars($data))) {
            $dataArray['hide_sender'] = $data->hideSender;
        }
        if (array_key_exists('largeFormat', get_object_vars($data))) {
            $dataArray['large_format'] = $data->largeFormat;
        }
        if (array_key_exists('onlyRecipient', get_object_vars($data))) {
            $dataArray['only_recipient'] = $data->onlyRecipient;
        }
        if (array_key_exists('priorityDelivery', get_object_vars($data))) {
            $dataArray['priority_delivery'] = $data->priorityDelivery;
        }
        if (array_key_exists('receiptCode', get_object_vars($data))) {
            $dataArray['receipt_code'] = $data->receiptCode;
        }
        if (array_key_exists('return', get_object_vars($data))) {
            $dataArray['return'] = $data->return;
        }
        if (array_key_exists('sameDayDelivery', get_object_vars($data))) {
            $dataArray['same_day_delivery'] = $data->sameDayDelivery;
        }
        if (array_key_exists('saturdayDelivery', get_object_vars($data))) {
            $dataArray['saturday_delivery'] = $data->saturdayDelivery;
        }
        if (array_key_exists('signature', get_object_vars($data))) {
            $dataArray['signature'] = $data->signature;
        }
        if (array_key_exists('tracked', get_object_vars($data))) {
            $dataArray['tracked'] = $data->tracked;
        }
        foreach ($data->additionalPropertyEntries() as $key => $value) {
            if (preg_match('/.*/', (string) $key)) {
                $dataArray[$key] = $value;
            }
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Validator\RefShipmentOptionsOptionsConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefShipmentOptionsOptions::class => false];
    }
}