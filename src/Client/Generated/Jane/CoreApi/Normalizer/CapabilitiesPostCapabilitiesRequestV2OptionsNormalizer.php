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
class CapabilitiesPostCapabilitiesRequestV2OptionsNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2Options::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2Options::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2Options();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\CapabilitiesPostCapabilitiesRequestV2OptionsConstraint());
        }
        if (\array_key_exists('additionalInsurance', $data)) {
            $object->additionalInsurance = $data['additionalInsurance'];
        }
        if (\array_key_exists('deliverAtPostalPoint', $data)) {
            $object->deliverAtPostalPoint = $data['deliverAtPostalPoint'];
        }
        if (\array_key_exists('freshFood', $data)) {
            $object->freshFood = $data['freshFood'];
        }
        if (\array_key_exists('frozen', $data)) {
            $object->frozen = $data['frozen'];
        }
        if (\array_key_exists('hideSender', $data)) {
            $object->hideSender = $data['hideSender'];
        }
        if (\array_key_exists('insurance', $data)) {
            $object->insurance = $data['insurance'];
        }
        if (\array_key_exists('noTracking', $data)) {
            $object->noTracking = $data['noTracking'];
        }
        if (\array_key_exists('oversizedPackage', $data)) {
            $object->oversizedPackage = $data['oversizedPackage'];
        }
        if (\array_key_exists('printReturnLabelAtDropOff', $data)) {
            $object->printReturnLabelAtDropOff = $data['printReturnLabelAtDropOff'];
        }
        if (\array_key_exists('priorityDelivery', $data)) {
            $object->priorityDelivery = $data['priorityDelivery'];
        }
        if (\array_key_exists('recipientOnlyDelivery', $data)) {
            $object->recipientOnlyDelivery = $data['recipientOnlyDelivery'];
        }
        if (\array_key_exists('returnOnFirstFailedDelivery', $data)) {
            $object->returnOnFirstFailedDelivery = $data['returnOnFirstFailedDelivery'];
        }
        if (\array_key_exists('requiresAgeVerification', $data)) {
            $object->requiresAgeVerification = $data['requiresAgeVerification'];
        }
        if (\array_key_exists('requiresCashOnDelivery', $data)) {
            $object->requiresCashOnDelivery = $data['requiresCashOnDelivery'];
        }
        if (\array_key_exists('requiresReceiptCode', $data)) {
            $object->requiresReceiptCode = $data['requiresReceiptCode'];
        }
        if (\array_key_exists('requiresSignature', $data)) {
            $object->requiresSignature = $data['requiresSignature'];
        }
        if (\array_key_exists('sameDayDelivery', $data)) {
            $object->sameDayDelivery = $data['sameDayDelivery'];
        }
        if (\array_key_exists('saturdayDelivery', $data)) {
            $object->saturdayDelivery = $data['saturdayDelivery'];
        }
        if (\array_key_exists('scheduledCollection', $data)) {
            $object->scheduledCollection = $data['scheduledCollection'];
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
            $dataArray['insurance'] = $data->insurance;
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
        if (array_key_exists('returnOnFirstFailedDelivery', get_object_vars($data))) {
            $dataArray['returnOnFirstFailedDelivery'] = $data->returnOnFirstFailedDelivery;
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
        if (array_key_exists('sameDayDelivery', get_object_vars($data))) {
            $dataArray['sameDayDelivery'] = $data->sameDayDelivery;
        }
        if (array_key_exists('saturdayDelivery', get_object_vars($data))) {
            $dataArray['saturdayDelivery'] = $data->saturdayDelivery;
        }
        if (array_key_exists('scheduledCollection', get_object_vars($data))) {
            $dataArray['scheduledCollection'] = $data->scheduledCollection;
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\CapabilitiesPostCapabilitiesRequestV2OptionsConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2Options::class => false];
    }
}