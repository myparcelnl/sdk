<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer;

use Jane\Component\JsonSchemaRuntime\Reference;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\Normalizer\CheckArray;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\Normalizer\ValidatorTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
class ShipmentOptionsNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ShipmentOptions::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ShipmentOptions::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ShipmentOptions();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Validator\ShipmentOptionsConstraint());
        }
        if (\array_key_exists('additionalInsurance', $data)) {
            $object->additionalInsurance = $data['additionalInsurance'];
            unset($data['additionalInsurance']);
        }
        if (\array_key_exists('deliverAtPostalPoint', $data)) {
            $object->deliverAtPostalPoint = $data['deliverAtPostalPoint'];
            unset($data['deliverAtPostalPoint']);
        }
        if (\array_key_exists('freshFood', $data)) {
            $object->freshFood = $data['freshFood'];
            unset($data['freshFood']);
        }
        if (\array_key_exists('frozen', $data)) {
            $object->frozen = $data['frozen'];
            unset($data['frozen']);
        }
        if (\array_key_exists('hideSender', $data)) {
            $object->hideSender = $data['hideSender'];
            unset($data['hideSender']);
        }
        if (\array_key_exists('insurance', $data)) {
            $object->insurance = $this->denormalizer->denormalize($data['insurance'], \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ShipmentOptionsCommonInsurance::class, 'json', $context);
            unset($data['insurance']);
        }
        if (\array_key_exists('noTracking', $data)) {
            $object->noTracking = $data['noTracking'];
            unset($data['noTracking']);
        }
        if (\array_key_exists('oversizedPackage', $data)) {
            $object->oversizedPackage = $data['oversizedPackage'];
            unset($data['oversizedPackage']);
        }
        if (\array_key_exists('printReturnLabelAtDropOff', $data)) {
            $object->printReturnLabelAtDropOff = $data['printReturnLabelAtDropOff'];
            unset($data['printReturnLabelAtDropOff']);
        }
        if (\array_key_exists('priorityDelivery', $data)) {
            $object->priorityDelivery = $data['priorityDelivery'];
            unset($data['priorityDelivery']);
        }
        if (\array_key_exists('recipientOnlyDelivery', $data)) {
            $object->recipientOnlyDelivery = $data['recipientOnlyDelivery'];
            unset($data['recipientOnlyDelivery']);
        }
        if (\array_key_exists('requiresAgeVerification', $data)) {
            $object->requiresAgeVerification = $data['requiresAgeVerification'];
            unset($data['requiresAgeVerification']);
        }
        if (\array_key_exists('requiresCashOnDelivery', $data)) {
            $object->requiresCashOnDelivery = $data['requiresCashOnDelivery'];
            unset($data['requiresCashOnDelivery']);
        }
        if (\array_key_exists('requiresReceiptCode', $data)) {
            $object->requiresReceiptCode = $data['requiresReceiptCode'];
            unset($data['requiresReceiptCode']);
        }
        if (\array_key_exists('requiresSignature', $data)) {
            $object->requiresSignature = $data['requiresSignature'];
            unset($data['requiresSignature']);
        }
        if (\array_key_exists('returnContributionFee', $data)) {
            $object->returnContributionFee = $this->denormalizer->denormalize($data['returnContributionFee'], \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ShipmentOptionsCommonReturnContributionFee::class, 'json', $context);
            unset($data['returnContributionFee']);
        }
        if (\array_key_exists('returnOnFirstFailedDelivery', $data)) {
            $object->returnOnFirstFailedDelivery = $data['returnOnFirstFailedDelivery'];
            unset($data['returnOnFirstFailedDelivery']);
        }
        if (\array_key_exists('sameDayDelivery', $data)) {
            $object->sameDayDelivery = $data['sameDayDelivery'];
            unset($data['sameDayDelivery']);
        }
        if (\array_key_exists('saturdayDelivery', $data)) {
            $object->saturdayDelivery = $data['saturdayDelivery'];
            unset($data['saturdayDelivery']);
        }
        if (\array_key_exists('scheduledCollection', $data)) {
            $object->scheduledCollection = $data['scheduledCollection'];
            unset($data['scheduledCollection']);
        }
        if (\array_key_exists('customLabelText', $data)) {
            $object->customLabelText = $this->denormalizer->denormalize($data['customLabelText'], \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ShipmentOptionsPackageCustomLabelText::class, 'json', $context);
            unset($data['customLabelText']);
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
            $dataArray['additionalInsurance'] = $data->additionalInsurance;
        }
        if (array_key_exists('deliverAtPostalPoint', get_object_vars($data))) {
            $dataArray['deliverAtPostalPoint'] = $data->deliverAtPostalPoint;
        }
        if (array_key_exists('freshFood', get_object_vars($data))) {
            $dataArray['freshFood'] = $data->freshFood;
        }
        if (array_key_exists('frozen', get_object_vars($data))) {
            $dataArray['frozen'] = $data->frozen;
        }
        if (array_key_exists('hideSender', get_object_vars($data))) {
            $dataArray['hideSender'] = $data->hideSender;
        }
        if (array_key_exists('insurance', get_object_vars($data))) {
            $normalized = $data->insurance === null ? null : $this->normalizer->normalize($data->insurance, 'json', $context);
            $dataArray['insurance'] = \is_iterable($normalized) ? new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\JsonObject($normalized) : $normalized;
        }
        if (array_key_exists('noTracking', get_object_vars($data))) {
            $dataArray['noTracking'] = $data->noTracking;
        }
        if (array_key_exists('oversizedPackage', get_object_vars($data))) {
            $dataArray['oversizedPackage'] = $data->oversizedPackage;
        }
        if (array_key_exists('printReturnLabelAtDropOff', get_object_vars($data))) {
            $dataArray['printReturnLabelAtDropOff'] = $data->printReturnLabelAtDropOff;
        }
        if (array_key_exists('priorityDelivery', get_object_vars($data))) {
            $dataArray['priorityDelivery'] = $data->priorityDelivery;
        }
        if (array_key_exists('recipientOnlyDelivery', get_object_vars($data))) {
            $dataArray['recipientOnlyDelivery'] = $data->recipientOnlyDelivery;
        }
        if (array_key_exists('requiresAgeVerification', get_object_vars($data))) {
            $dataArray['requiresAgeVerification'] = $data->requiresAgeVerification;
        }
        if (array_key_exists('requiresCashOnDelivery', get_object_vars($data))) {
            $dataArray['requiresCashOnDelivery'] = $data->requiresCashOnDelivery;
        }
        if (array_key_exists('requiresReceiptCode', get_object_vars($data))) {
            $dataArray['requiresReceiptCode'] = $data->requiresReceiptCode;
        }
        if (array_key_exists('requiresSignature', get_object_vars($data))) {
            $dataArray['requiresSignature'] = $data->requiresSignature;
        }
        if (array_key_exists('returnContributionFee', get_object_vars($data))) {
            $normalized_1 = $data->returnContributionFee === null ? null : $this->normalizer->normalize($data->returnContributionFee, 'json', $context);
            $dataArray['returnContributionFee'] = \is_iterable($normalized_1) ? new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\JsonObject($normalized_1) : $normalized_1;
        }
        if (array_key_exists('returnOnFirstFailedDelivery', get_object_vars($data))) {
            $dataArray['returnOnFirstFailedDelivery'] = $data->returnOnFirstFailedDelivery;
        }
        if (array_key_exists('sameDayDelivery', get_object_vars($data))) {
            $dataArray['sameDayDelivery'] = $data->sameDayDelivery;
        }
        if (array_key_exists('saturdayDelivery', get_object_vars($data))) {
            $dataArray['saturdayDelivery'] = $data->saturdayDelivery;
        }
        if (array_key_exists('scheduledCollection', get_object_vars($data))) {
            $dataArray['scheduledCollection'] = $data->scheduledCollection;
        }
        if (array_key_exists('customLabelText', get_object_vars($data))) {
            $normalized_2 = $data->customLabelText === null ? null : $this->normalizer->normalize($data->customLabelText, 'json', $context);
            $dataArray['customLabelText'] = \is_iterable($normalized_2) ? new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\JsonObject($normalized_2) : $normalized_2;
        }
        foreach ($data->additionalPropertyEntries() as $key => $value) {
            if (preg_match('/.*/', (string) $key)) {
                $dataArray[$key] = $value;
            }
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Validator\ShipmentOptionsConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ShipmentOptions::class => false];
    }
}