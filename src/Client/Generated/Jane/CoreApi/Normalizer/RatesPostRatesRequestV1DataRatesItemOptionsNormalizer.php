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
class RatesPostRatesRequestV1DataRatesItemOptionsNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RatesPostRatesRequestV1DataRatesItemOptions::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RatesPostRatesRequestV1DataRatesItemOptions::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RatesPostRatesRequestV1DataRatesItemOptions();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\RatesPostRatesRequestV1DataRatesItemOptionsConstraint());
        }
        if (\array_key_exists('age_check', $data)) {
            $object->ageCheck = $data['age_check'];
        }
        if (\array_key_exists('cash_on_delivery', $data)) {
            $object->cashOnDelivery = $this->denormalizer->denormalize($data['cash_on_delivery'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RatesPostRatesRequestV1DataRatesItemOptionsCashOnDelivery::class, 'json', $context);
        }
        if (\array_key_exists('collect', $data)) {
            $object->collect = $data['collect'];
        }
        if (\array_key_exists('cooled_delivery', $data)) {
            $object->cooledDelivery = $data['cooled_delivery'];
        }
        if (\array_key_exists('drop_off_at_postal_point', $data)) {
            $object->dropOffAtPostalPoint = $data['drop_off_at_postal_point'];
        }
        if (\array_key_exists('extra_assurance', $data)) {
            $object->extraAssurance = $data['extra_assurance'];
        }
        if (\array_key_exists('hide_sender', $data)) {
            $object->hideSender = $data['hide_sender'];
        }
        if (\array_key_exists('large_format', $data)) {
            $object->largeFormat = $data['large_format'];
        }
        if (\array_key_exists('no_tracking', $data)) {
            $object->noTracking = $data['no_tracking'];
        }
        if (\array_key_exists('only_recipient', $data)) {
            $object->onlyRecipient = $data['only_recipient'];
        }
        if (\array_key_exists('priority_delivery', $data)) {
            $object->priorityDelivery = $data['priority_delivery'];
        }
        if (\array_key_exists('receipt_code', $data)) {
            $object->receiptCode = $data['receipt_code'];
        }
        if (\array_key_exists('return', $data)) {
            $object->return = $data['return'];
        }
        if (\array_key_exists('same_day_delivery', $data)) {
            $object->sameDayDelivery = $data['same_day_delivery'];
        }
        if (\array_key_exists('saturday_delivery', $data)) {
            $object->saturdayDelivery = $data['saturday_delivery'];
        }
        if (\array_key_exists('signature', $data)) {
            $object->signature = $data['signature'];
        }
        if (\array_key_exists('tracked', $data)) {
            $object->tracked = $data['tracked'];
        }
        if (\array_key_exists('insurance', $data)) {
            $object->insurance = $data['insurance'];
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
            $dataArray['cash_on_delivery'] = \is_iterable($normalized) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized) : $normalized;
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
        if (array_key_exists('hideSender', get_object_vars($data))) {
            $dataArray['hide_sender'] = $data->hideSender;
        }
        if (array_key_exists('largeFormat', get_object_vars($data))) {
            $dataArray['large_format'] = $data->largeFormat;
        }
        if (array_key_exists('noTracking', get_object_vars($data))) {
            $dataArray['no_tracking'] = $data->noTracking;
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
        if (array_key_exists('insurance', get_object_vars($data))) {
            $dataArray['insurance'] = $data->insurance;
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\RatesPostRatesRequestV1DataRatesItemOptionsConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RatesPostRatesRequestV1DataRatesItemOptions::class => false];
    }
}