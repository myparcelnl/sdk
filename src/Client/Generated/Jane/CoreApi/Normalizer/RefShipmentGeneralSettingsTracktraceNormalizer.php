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
class RefShipmentGeneralSettingsTracktraceNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentGeneralSettingsTracktrace::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentGeneralSettingsTracktrace::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentGeneralSettingsTracktrace();
        if (null === $data || false === \is_array($data)) {
            return $object;
        }
        if (isset($data['$ref']) && !isset($data['type']) && !isset($data['properties']) && !isset($data['allOf'])) {
            return new Reference($data['$ref'], $context['document-origin']);
        }
        if (isset($data['$recursiveRef'])) {
            return new Reference($data['$recursiveRef'], $context['document-origin']);
        }
        if (\array_key_exists('bcc', $data)) {
            $object->bcc = is_int($data['bcc']) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesIntBoolean::tryFrom($data['bcc']) ?? $data['bcc'] : $data['bcc'];
            unset($data['bcc']);
        }
        if (\array_key_exists('bcc_email', $data) && $data['bcc_email'] !== null) {
            $value = $data['bcc_email'];
            if (is_null($data['bcc_email'])) {
                $value = $data['bcc_email'];
            } elseif (is_string($data['bcc_email'])) {
                $value = $data['bcc_email'];
            }
            $object->bccEmail = $value;
            unset($data['bcc_email']);
        }
        elseif (\array_key_exists('bcc_email', $data)) {
            $object->bccEmail = null;
            unset($data['bcc_email']);
        }
        if (\array_key_exists('carrier_email_basic_notification', $data)) {
            $object->carrierEmailBasicNotification = is_int($data['carrier_email_basic_notification']) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesIntBoolean::tryFrom($data['carrier_email_basic_notification']) ?? $data['carrier_email_basic_notification'] : $data['carrier_email_basic_notification'];
            unset($data['carrier_email_basic_notification']);
        }
        if (\array_key_exists('delivery_notification', $data)) {
            $object->deliveryNotification = is_int($data['delivery_notification']) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesIntBoolean::tryFrom($data['delivery_notification']) ?? $data['delivery_notification'] : $data['delivery_notification'];
            unset($data['delivery_notification']);
        }
        if (\array_key_exists('delivery_notification_email', $data)) {
            $values = [];
            foreach ($data['delivery_notification_email'] as $value_1) {
                $values[] = $value_1;
            }
            $object->deliveryNotificationEmail = $values;
            unset($data['delivery_notification_email']);
        }
        if (\array_key_exists('disable_auto_detect_pickup', $data)) {
            $object->disableAutoDetectPickup = is_int($data['disable_auto_detect_pickup']) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesIntBoolean::tryFrom($data['disable_auto_detect_pickup']) ?? $data['disable_auto_detect_pickup'] : $data['disable_auto_detect_pickup'];
            unset($data['disable_auto_detect_pickup']);
        }
        if (\array_key_exists('email_on_handed_to_courier', $data)) {
            $object->emailOnHandedToCourier = is_int($data['email_on_handed_to_courier']) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesIntBoolean::tryFrom($data['email_on_handed_to_courier']) ?? $data['email_on_handed_to_courier'] : $data['email_on_handed_to_courier'];
            unset($data['email_on_handed_to_courier']);
        }
        if (\array_key_exists('from_address_company', $data)) {
            $object->fromAddressCompany = $data['from_address_company'];
            unset($data['from_address_company']);
        }
        if (\array_key_exists('from_address_email', $data)) {
            $object->fromAddressEmail = $data['from_address_email'];
            unset($data['from_address_email']);
        }
        if (\array_key_exists('printer_identifier', $data)) {
            $object->printerIdentifier = $data['printer_identifier'];
            unset($data['printer_identifier']);
        }
        if (\array_key_exists('save_recipient_address', $data)) {
            $object->saveRecipientAddress = is_int($data['save_recipient_address']) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesIntBoolean::tryFrom($data['save_recipient_address']) ?? $data['save_recipient_address'] : $data['save_recipient_address'];
            unset($data['save_recipient_address']);
        }
        if (\array_key_exists('send_track_trace_emails', $data)) {
            $object->sendTrackTraceEmails = is_int($data['send_track_trace_emails']) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesIntBoolean::tryFrom($data['send_track_trace_emails']) ?? $data['send_track_trace_emails'] : $data['send_track_trace_emails'];
            unset($data['send_track_trace_emails']);
        }
        foreach ($data as $key => $value_2) {
            if (preg_match('/.*/', (string) $key)) {
                $object[$key] = $value_2;
            }
        }
        return $object;
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $dataArray = [];
        if (array_key_exists('bcc', get_object_vars($data))) {
            $dataArray['bcc'] = $data->bcc instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesIntBoolean ? $data->bcc->value : $data->bcc;
        }
        if (array_key_exists('bccEmail', get_object_vars($data))) {
            $value = $data->bccEmail;
            if (is_null($data->bccEmail)) {
                $value = $data->bccEmail;
            } elseif (is_string($data->bccEmail)) {
                $value = $data->bccEmail;
            }
            $dataArray['bcc_email'] = $value;
        }
        if (array_key_exists('carrierEmailBasicNotification', get_object_vars($data))) {
            $dataArray['carrier_email_basic_notification'] = $data->carrierEmailBasicNotification instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesIntBoolean ? $data->carrierEmailBasicNotification->value : $data->carrierEmailBasicNotification;
        }
        if (array_key_exists('deliveryNotification', get_object_vars($data))) {
            $dataArray['delivery_notification'] = $data->deliveryNotification instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesIntBoolean ? $data->deliveryNotification->value : $data->deliveryNotification;
        }
        if (array_key_exists('deliveryNotificationEmail', get_object_vars($data))) {
            $values = null;
            if (null !== $data->deliveryNotificationEmail) {
                $values = [];
                foreach ($data->deliveryNotificationEmail as $value_1) {
                    $values[] = $value_1;
                }
            }
            $dataArray['delivery_notification_email'] = $values;
        }
        if (array_key_exists('disableAutoDetectPickup', get_object_vars($data))) {
            $dataArray['disable_auto_detect_pickup'] = $data->disableAutoDetectPickup instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesIntBoolean ? $data->disableAutoDetectPickup->value : $data->disableAutoDetectPickup;
        }
        if (array_key_exists('emailOnHandedToCourier', get_object_vars($data))) {
            $dataArray['email_on_handed_to_courier'] = $data->emailOnHandedToCourier instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesIntBoolean ? $data->emailOnHandedToCourier->value : $data->emailOnHandedToCourier;
        }
        if (array_key_exists('fromAddressCompany', get_object_vars($data))) {
            $dataArray['from_address_company'] = $data->fromAddressCompany;
        }
        if (array_key_exists('fromAddressEmail', get_object_vars($data))) {
            $dataArray['from_address_email'] = $data->fromAddressEmail;
        }
        if (array_key_exists('printerIdentifier', get_object_vars($data))) {
            $dataArray['printer_identifier'] = $data->printerIdentifier;
        }
        if (array_key_exists('saveRecipientAddress', get_object_vars($data))) {
            $dataArray['save_recipient_address'] = $data->saveRecipientAddress instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesIntBoolean ? $data->saveRecipientAddress->value : $data->saveRecipientAddress;
        }
        if (array_key_exists('sendTrackTraceEmails', get_object_vars($data))) {
            $dataArray['send_track_trace_emails'] = $data->sendTrackTraceEmails instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesIntBoolean ? $data->sendTrackTraceEmails->value : $data->sendTrackTraceEmails;
        }
        foreach ($data->additionalPropertyEntries() as $key => $value_2) {
            if (preg_match('/.*/', (string) $key)) {
                $dataArray[$key] = $value_2;
            }
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\RefShipmentGeneralSettingsTracktraceConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentGeneralSettingsTracktrace::class => false];
    }
}