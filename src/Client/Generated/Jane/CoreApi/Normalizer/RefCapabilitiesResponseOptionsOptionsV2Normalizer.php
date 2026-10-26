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
class RefCapabilitiesResponseOptionsOptionsV2Normalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseOptionsOptionsV2::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseOptionsOptionsV2::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseOptionsOptionsV2();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\RefCapabilitiesResponseOptionsOptionsV2Constraint());
        }
        if (\array_key_exists('additionalInsurance', $data)) {
            $object->additionalInsurance = $this->denormalizer->denormalize($data['additionalInsurance'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseOptionsOptionV2::class, 'json', $context);
            unset($data['additionalInsurance']);
        }
        if (\array_key_exists('deliverAtPostalPoint', $data)) {
            $object->deliverAtPostalPoint = $this->denormalizer->denormalize($data['deliverAtPostalPoint'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseOptionsOptionV2::class, 'json', $context);
            unset($data['deliverAtPostalPoint']);
        }
        if (\array_key_exists('freshFood', $data)) {
            $object->freshFood = $this->denormalizer->denormalize($data['freshFood'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseOptionsOptionV2::class, 'json', $context);
            unset($data['freshFood']);
        }
        if (\array_key_exists('frozen', $data)) {
            $object->frozen = $this->denormalizer->denormalize($data['frozen'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseOptionsOptionV2::class, 'json', $context);
            unset($data['frozen']);
        }
        if (\array_key_exists('hideSender', $data)) {
            $object->hideSender = $this->denormalizer->denormalize($data['hideSender'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseOptionsOptionV2::class, 'json', $context);
            unset($data['hideSender']);
        }
        if (\array_key_exists('noTracking', $data)) {
            $object->noTracking = $this->denormalizer->denormalize($data['noTracking'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseOptionsOptionV2::class, 'json', $context);
            unset($data['noTracking']);
        }
        if (\array_key_exists('oversizedPackage', $data)) {
            $object->oversizedPackage = $this->denormalizer->denormalize($data['oversizedPackage'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseOptionsOptionV2::class, 'json', $context);
            unset($data['oversizedPackage']);
        }
        if (\array_key_exists('priorityDelivery', $data)) {
            $object->priorityDelivery = $this->denormalizer->denormalize($data['priorityDelivery'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseOptionsOptionV2::class, 'json', $context);
            unset($data['priorityDelivery']);
        }
        if (\array_key_exists('printReturnLabelAtDropOff', $data)) {
            $object->printReturnLabelAtDropOff = $this->denormalizer->denormalize($data['printReturnLabelAtDropOff'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseOptionsOptionV2::class, 'json', $context);
            unset($data['printReturnLabelAtDropOff']);
        }
        if (\array_key_exists('recipientOnlyDelivery', $data)) {
            $object->recipientOnlyDelivery = $this->denormalizer->denormalize($data['recipientOnlyDelivery'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseOptionsOptionV2::class, 'json', $context);
            unset($data['recipientOnlyDelivery']);
        }
        if (\array_key_exists('requiresAgeVerification', $data)) {
            $object->requiresAgeVerification = $this->denormalizer->denormalize($data['requiresAgeVerification'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseOptionsOptionV2::class, 'json', $context);
            unset($data['requiresAgeVerification']);
        }
        if (\array_key_exists('requiresCashOnDelivery', $data)) {
            $object->requiresCashOnDelivery = $this->denormalizer->denormalize($data['requiresCashOnDelivery'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseOptionsOptionV2::class, 'json', $context);
            unset($data['requiresCashOnDelivery']);
        }
        if (\array_key_exists('requiresReceiptCode', $data)) {
            $object->requiresReceiptCode = $this->denormalizer->denormalize($data['requiresReceiptCode'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseOptionsOptionV2::class, 'json', $context);
            unset($data['requiresReceiptCode']);
        }
        if (\array_key_exists('requiresSignature', $data)) {
            $object->requiresSignature = $this->denormalizer->denormalize($data['requiresSignature'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseOptionsOptionV2::class, 'json', $context);
            unset($data['requiresSignature']);
        }
        if (\array_key_exists('returnOnFirstFailedDelivery', $data)) {
            $object->returnOnFirstFailedDelivery = $this->denormalizer->denormalize($data['returnOnFirstFailedDelivery'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseOptionsOptionV2::class, 'json', $context);
            unset($data['returnOnFirstFailedDelivery']);
        }
        if (\array_key_exists('sameDayDelivery', $data)) {
            $object->sameDayDelivery = $this->denormalizer->denormalize($data['sameDayDelivery'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseOptionsOptionV2::class, 'json', $context);
            unset($data['sameDayDelivery']);
        }
        if (\array_key_exists('saturdayDelivery', $data)) {
            $object->saturdayDelivery = $this->denormalizer->denormalize($data['saturdayDelivery'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseOptionsOptionV2::class, 'json', $context);
            unset($data['saturdayDelivery']);
        }
        if (\array_key_exists('scheduledCollection', $data)) {
            $object->scheduledCollection = $this->denormalizer->denormalize($data['scheduledCollection'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseOptionsOptionV2::class, 'json', $context);
            unset($data['scheduledCollection']);
        }
        if (\array_key_exists('tracked', $data)) {
            $object->tracked = $this->denormalizer->denormalize($data['tracked'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseOptionsOptionV2::class, 'json', $context);
            unset($data['tracked']);
        }
        if (\array_key_exists('insurance', $data)) {
            $object->insurance = $this->denormalizer->denormalize($data['insurance'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseOptionsInsuranceOptionV2::class, 'json', $context);
            unset($data['insurance']);
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
        if (array_key_exists('additionalInsurance', get_object_vars($data))) {
            $normalized = $data->additionalInsurance === null ? null : $this->normalizer->normalize($data->additionalInsurance, 'json', $context);
            $dataArray['additionalInsurance'] = \is_iterable($normalized) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized) : $normalized;
        }
        if (array_key_exists('deliverAtPostalPoint', get_object_vars($data))) {
            $normalized_1 = $data->deliverAtPostalPoint === null ? null : $this->normalizer->normalize($data->deliverAtPostalPoint, 'json', $context);
            $dataArray['deliverAtPostalPoint'] = \is_iterable($normalized_1) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_1) : $normalized_1;
        }
        if (array_key_exists('freshFood', get_object_vars($data))) {
            $normalized_2 = $data->freshFood === null ? null : $this->normalizer->normalize($data->freshFood, 'json', $context);
            $dataArray['freshFood'] = \is_iterable($normalized_2) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_2) : $normalized_2;
        }
        if (array_key_exists('frozen', get_object_vars($data))) {
            $normalized_3 = $data->frozen === null ? null : $this->normalizer->normalize($data->frozen, 'json', $context);
            $dataArray['frozen'] = \is_iterable($normalized_3) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_3) : $normalized_3;
        }
        if (array_key_exists('hideSender', get_object_vars($data))) {
            $normalized_4 = $data->hideSender === null ? null : $this->normalizer->normalize($data->hideSender, 'json', $context);
            $dataArray['hideSender'] = \is_iterable($normalized_4) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_4) : $normalized_4;
        }
        if (array_key_exists('noTracking', get_object_vars($data))) {
            $normalized_5 = $data->noTracking === null ? null : $this->normalizer->normalize($data->noTracking, 'json', $context);
            $dataArray['noTracking'] = \is_iterable($normalized_5) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_5) : $normalized_5;
        }
        if (array_key_exists('oversizedPackage', get_object_vars($data))) {
            $normalized_6 = $data->oversizedPackage === null ? null : $this->normalizer->normalize($data->oversizedPackage, 'json', $context);
            $dataArray['oversizedPackage'] = \is_iterable($normalized_6) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_6) : $normalized_6;
        }
        if (array_key_exists('priorityDelivery', get_object_vars($data))) {
            $normalized_7 = $data->priorityDelivery === null ? null : $this->normalizer->normalize($data->priorityDelivery, 'json', $context);
            $dataArray['priorityDelivery'] = \is_iterable($normalized_7) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_7) : $normalized_7;
        }
        if (array_key_exists('printReturnLabelAtDropOff', get_object_vars($data))) {
            $normalized_8 = $data->printReturnLabelAtDropOff === null ? null : $this->normalizer->normalize($data->printReturnLabelAtDropOff, 'json', $context);
            $dataArray['printReturnLabelAtDropOff'] = \is_iterable($normalized_8) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_8) : $normalized_8;
        }
        if (array_key_exists('recipientOnlyDelivery', get_object_vars($data))) {
            $normalized_9 = $data->recipientOnlyDelivery === null ? null : $this->normalizer->normalize($data->recipientOnlyDelivery, 'json', $context);
            $dataArray['recipientOnlyDelivery'] = \is_iterable($normalized_9) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_9) : $normalized_9;
        }
        if (array_key_exists('requiresAgeVerification', get_object_vars($data))) {
            $normalized_10 = $data->requiresAgeVerification === null ? null : $this->normalizer->normalize($data->requiresAgeVerification, 'json', $context);
            $dataArray['requiresAgeVerification'] = \is_iterable($normalized_10) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_10) : $normalized_10;
        }
        if (array_key_exists('requiresCashOnDelivery', get_object_vars($data))) {
            $normalized_11 = $data->requiresCashOnDelivery === null ? null : $this->normalizer->normalize($data->requiresCashOnDelivery, 'json', $context);
            $dataArray['requiresCashOnDelivery'] = \is_iterable($normalized_11) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_11) : $normalized_11;
        }
        if (array_key_exists('requiresReceiptCode', get_object_vars($data))) {
            $normalized_12 = $data->requiresReceiptCode === null ? null : $this->normalizer->normalize($data->requiresReceiptCode, 'json', $context);
            $dataArray['requiresReceiptCode'] = \is_iterable($normalized_12) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_12) : $normalized_12;
        }
        if (array_key_exists('requiresSignature', get_object_vars($data))) {
            $normalized_13 = $data->requiresSignature === null ? null : $this->normalizer->normalize($data->requiresSignature, 'json', $context);
            $dataArray['requiresSignature'] = \is_iterable($normalized_13) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_13) : $normalized_13;
        }
        if (array_key_exists('returnOnFirstFailedDelivery', get_object_vars($data))) {
            $normalized_14 = $data->returnOnFirstFailedDelivery === null ? null : $this->normalizer->normalize($data->returnOnFirstFailedDelivery, 'json', $context);
            $dataArray['returnOnFirstFailedDelivery'] = \is_iterable($normalized_14) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_14) : $normalized_14;
        }
        if (array_key_exists('sameDayDelivery', get_object_vars($data))) {
            $normalized_15 = $data->sameDayDelivery === null ? null : $this->normalizer->normalize($data->sameDayDelivery, 'json', $context);
            $dataArray['sameDayDelivery'] = \is_iterable($normalized_15) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_15) : $normalized_15;
        }
        if (array_key_exists('saturdayDelivery', get_object_vars($data))) {
            $normalized_16 = $data->saturdayDelivery === null ? null : $this->normalizer->normalize($data->saturdayDelivery, 'json', $context);
            $dataArray['saturdayDelivery'] = \is_iterable($normalized_16) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_16) : $normalized_16;
        }
        if (array_key_exists('scheduledCollection', get_object_vars($data))) {
            $normalized_17 = $data->scheduledCollection === null ? null : $this->normalizer->normalize($data->scheduledCollection, 'json', $context);
            $dataArray['scheduledCollection'] = \is_iterable($normalized_17) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_17) : $normalized_17;
        }
        if (array_key_exists('tracked', get_object_vars($data))) {
            $normalized_18 = $data->tracked === null ? null : $this->normalizer->normalize($data->tracked, 'json', $context);
            $dataArray['tracked'] = \is_iterable($normalized_18) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_18) : $normalized_18;
        }
        if (array_key_exists('insurance', get_object_vars($data))) {
            $normalized_19 = $data->insurance === null ? null : $this->normalizer->normalize($data->insurance, 'json', $context);
            $dataArray['insurance'] = \is_iterable($normalized_19) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_19) : $normalized_19;
        }
        foreach ($data->additionalPropertyEntries() as $key => $value) {
            if (preg_match('/.*/', (string) $key)) {
                $dataArray[$key] = $value;
            }
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\RefCapabilitiesResponseOptionsOptionsV2Constraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseOptionsOptionsV2::class => false];
    }
}