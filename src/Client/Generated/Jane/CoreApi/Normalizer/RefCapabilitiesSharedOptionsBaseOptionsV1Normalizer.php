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
class RefCapabilitiesSharedOptionsBaseOptionsV1Normalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedOptionsBaseOptionsV1::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedOptionsBaseOptionsV1::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedOptionsBaseOptionsV1();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\RefCapabilitiesSharedOptionsBaseOptionsV1Constraint());
        }
        if (\array_key_exists('age_check', $data)) {
            $object->ageCheck = $this->denormalizer->denormalize($data['age_check'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedOptionsBaseOptionV1::class, 'json', $context);
            unset($data['age_check']);
        }
        if (\array_key_exists('cash_on_delivery', $data)) {
            $object->cashOnDelivery = $this->denormalizer->denormalize($data['cash_on_delivery'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedOptionsBaseOptionV1::class, 'json', $context);
            unset($data['cash_on_delivery']);
        }
        if (\array_key_exists('collect', $data)) {
            $object->collect = $this->denormalizer->denormalize($data['collect'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedOptionsBaseOptionV1::class, 'json', $context);
            unset($data['collect']);
        }
        if (\array_key_exists('drop_off_at_postal_point', $data)) {
            $object->dropOffAtPostalPoint = $this->denormalizer->denormalize($data['drop_off_at_postal_point'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedOptionsBaseOptionV1::class, 'json', $context);
            unset($data['drop_off_at_postal_point']);
        }
        if (\array_key_exists('extra_assurance', $data)) {
            $object->extraAssurance = $this->denormalizer->denormalize($data['extra_assurance'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedOptionsBaseOptionV1::class, 'json', $context);
            unset($data['extra_assurance']);
        }
        if (\array_key_exists('fresh_food', $data)) {
            $object->freshFood = $this->denormalizer->denormalize($data['fresh_food'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedOptionsBaseOptionV1::class, 'json', $context);
            unset($data['fresh_food']);
        }
        if (\array_key_exists('frozen', $data)) {
            $object->frozen = $this->denormalizer->denormalize($data['frozen'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedOptionsBaseOptionV1::class, 'json', $context);
            unset($data['frozen']);
        }
        if (\array_key_exists('hide_sender', $data)) {
            $object->hideSender = $this->denormalizer->denormalize($data['hide_sender'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedOptionsBaseOptionV1::class, 'json', $context);
            unset($data['hide_sender']);
        }
        if (\array_key_exists('large_format', $data)) {
            $object->largeFormat = $this->denormalizer->denormalize($data['large_format'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedOptionsBaseOptionV1::class, 'json', $context);
            unset($data['large_format']);
        }
        if (\array_key_exists('no_tracking', $data)) {
            $object->noTracking = $this->denormalizer->denormalize($data['no_tracking'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedOptionsBaseOptionV1::class, 'json', $context);
            unset($data['no_tracking']);
        }
        if (\array_key_exists('only_recipient', $data)) {
            $object->onlyRecipient = $this->denormalizer->denormalize($data['only_recipient'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedOptionsBaseOptionV1::class, 'json', $context);
            unset($data['only_recipient']);
        }
        if (\array_key_exists('printerless_return', $data)) {
            $object->printerlessReturn = $this->denormalizer->denormalize($data['printerless_return'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedOptionsBaseOptionV1::class, 'json', $context);
            unset($data['printerless_return']);
        }
        if (\array_key_exists('priority_delivery', $data)) {
            $object->priorityDelivery = $this->denormalizer->denormalize($data['priority_delivery'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedOptionsBaseOptionV1::class, 'json', $context);
            unset($data['priority_delivery']);
        }
        if (\array_key_exists('receipt_code', $data)) {
            $object->receiptCode = $this->denormalizer->denormalize($data['receipt_code'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedOptionsBaseOptionV1::class, 'json', $context);
            unset($data['receipt_code']);
        }
        if (\array_key_exists('return', $data)) {
            $object->return = $this->denormalizer->denormalize($data['return'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedOptionsBaseOptionV1::class, 'json', $context);
            unset($data['return']);
        }
        if (\array_key_exists('same_day_delivery', $data)) {
            $object->sameDayDelivery = $this->denormalizer->denormalize($data['same_day_delivery'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedOptionsBaseOptionV1::class, 'json', $context);
            unset($data['same_day_delivery']);
        }
        if (\array_key_exists('saturday_delivery', $data)) {
            $object->saturdayDelivery = $this->denormalizer->denormalize($data['saturday_delivery'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedOptionsBaseOptionV1::class, 'json', $context);
            unset($data['saturday_delivery']);
        }
        if (\array_key_exists('signature', $data)) {
            $object->signature = $this->denormalizer->denormalize($data['signature'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedOptionsBaseOptionV1::class, 'json', $context);
            unset($data['signature']);
        }
        if (\array_key_exists('tracked', $data)) {
            $object->tracked = $this->denormalizer->denormalize($data['tracked'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedOptionsBaseOptionV1::class, 'json', $context);
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
            $normalized = $data->ageCheck === null ? null : $this->normalizer->normalize($data->ageCheck, 'json', $context);
            $dataArray['age_check'] = \is_iterable($normalized) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized) : $normalized;
        }
        if (array_key_exists('cashOnDelivery', get_object_vars($data))) {
            $normalized_1 = $data->cashOnDelivery === null ? null : $this->normalizer->normalize($data->cashOnDelivery, 'json', $context);
            $dataArray['cash_on_delivery'] = \is_iterable($normalized_1) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_1) : $normalized_1;
        }
        if (array_key_exists('collect', get_object_vars($data))) {
            $normalized_2 = $data->collect === null ? null : $this->normalizer->normalize($data->collect, 'json', $context);
            $dataArray['collect'] = \is_iterable($normalized_2) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_2) : $normalized_2;
        }
        if (array_key_exists('dropOffAtPostalPoint', get_object_vars($data))) {
            $normalized_3 = $data->dropOffAtPostalPoint === null ? null : $this->normalizer->normalize($data->dropOffAtPostalPoint, 'json', $context);
            $dataArray['drop_off_at_postal_point'] = \is_iterable($normalized_3) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_3) : $normalized_3;
        }
        if (array_key_exists('extraAssurance', get_object_vars($data))) {
            $normalized_4 = $data->extraAssurance === null ? null : $this->normalizer->normalize($data->extraAssurance, 'json', $context);
            $dataArray['extra_assurance'] = \is_iterable($normalized_4) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_4) : $normalized_4;
        }
        if (array_key_exists('freshFood', get_object_vars($data))) {
            $normalized_5 = $data->freshFood === null ? null : $this->normalizer->normalize($data->freshFood, 'json', $context);
            $dataArray['fresh_food'] = \is_iterable($normalized_5) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_5) : $normalized_5;
        }
        if (array_key_exists('frozen', get_object_vars($data))) {
            $normalized_6 = $data->frozen === null ? null : $this->normalizer->normalize($data->frozen, 'json', $context);
            $dataArray['frozen'] = \is_iterable($normalized_6) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_6) : $normalized_6;
        }
        if (array_key_exists('hideSender', get_object_vars($data))) {
            $normalized_7 = $data->hideSender === null ? null : $this->normalizer->normalize($data->hideSender, 'json', $context);
            $dataArray['hide_sender'] = \is_iterable($normalized_7) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_7) : $normalized_7;
        }
        if (array_key_exists('largeFormat', get_object_vars($data))) {
            $normalized_8 = $data->largeFormat === null ? null : $this->normalizer->normalize($data->largeFormat, 'json', $context);
            $dataArray['large_format'] = \is_iterable($normalized_8) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_8) : $normalized_8;
        }
        if (array_key_exists('noTracking', get_object_vars($data))) {
            $normalized_9 = $data->noTracking === null ? null : $this->normalizer->normalize($data->noTracking, 'json', $context);
            $dataArray['no_tracking'] = \is_iterable($normalized_9) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_9) : $normalized_9;
        }
        if (array_key_exists('onlyRecipient', get_object_vars($data))) {
            $normalized_10 = $data->onlyRecipient === null ? null : $this->normalizer->normalize($data->onlyRecipient, 'json', $context);
            $dataArray['only_recipient'] = \is_iterable($normalized_10) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_10) : $normalized_10;
        }
        if (array_key_exists('printerlessReturn', get_object_vars($data))) {
            $normalized_11 = $data->printerlessReturn === null ? null : $this->normalizer->normalize($data->printerlessReturn, 'json', $context);
            $dataArray['printerless_return'] = \is_iterable($normalized_11) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_11) : $normalized_11;
        }
        if (array_key_exists('priorityDelivery', get_object_vars($data))) {
            $normalized_12 = $data->priorityDelivery === null ? null : $this->normalizer->normalize($data->priorityDelivery, 'json', $context);
            $dataArray['priority_delivery'] = \is_iterable($normalized_12) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_12) : $normalized_12;
        }
        if (array_key_exists('receiptCode', get_object_vars($data))) {
            $normalized_13 = $data->receiptCode === null ? null : $this->normalizer->normalize($data->receiptCode, 'json', $context);
            $dataArray['receipt_code'] = \is_iterable($normalized_13) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_13) : $normalized_13;
        }
        if (array_key_exists('return', get_object_vars($data))) {
            $normalized_14 = $data->return === null ? null : $this->normalizer->normalize($data->return, 'json', $context);
            $dataArray['return'] = \is_iterable($normalized_14) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_14) : $normalized_14;
        }
        if (array_key_exists('sameDayDelivery', get_object_vars($data))) {
            $normalized_15 = $data->sameDayDelivery === null ? null : $this->normalizer->normalize($data->sameDayDelivery, 'json', $context);
            $dataArray['same_day_delivery'] = \is_iterable($normalized_15) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_15) : $normalized_15;
        }
        if (array_key_exists('saturdayDelivery', get_object_vars($data))) {
            $normalized_16 = $data->saturdayDelivery === null ? null : $this->normalizer->normalize($data->saturdayDelivery, 'json', $context);
            $dataArray['saturday_delivery'] = \is_iterable($normalized_16) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_16) : $normalized_16;
        }
        if (array_key_exists('signature', get_object_vars($data))) {
            $normalized_17 = $data->signature === null ? null : $this->normalizer->normalize($data->signature, 'json', $context);
            $dataArray['signature'] = \is_iterable($normalized_17) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_17) : $normalized_17;
        }
        if (array_key_exists('tracked', get_object_vars($data))) {
            $normalized_18 = $data->tracked === null ? null : $this->normalizer->normalize($data->tracked, 'json', $context);
            $dataArray['tracked'] = \is_iterable($normalized_18) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_18) : $normalized_18;
        }
        foreach ($data->additionalPropertyEntries() as $key => $value) {
            if (preg_match('/.*/', (string) $key)) {
                $dataArray[$key] = $value;
            }
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\RefCapabilitiesSharedOptionsBaseOptionsV1Constraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedOptionsBaseOptionsV1::class => false];
    }
}