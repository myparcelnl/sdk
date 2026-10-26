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
class ShipmentPostShipmentsRequestV11DataShipmentsItemGeneralSettingsNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemGeneralSettings::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemGeneralSettings::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemGeneralSettings();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\ShipmentPostShipmentsRequestV11DataShipmentsItemGeneralSettingsConstraint());
        }
        if (\array_key_exists('save_recipient_address', $data)) {
            $object->saveRecipientAddress = is_int($data['save_recipient_address']) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemGeneralSettingsSaveRecipientAddress::tryFrom($data['save_recipient_address']) ?? $data['save_recipient_address'] : $data['save_recipient_address'];
        }
        if (\array_key_exists('delivery_notification', $data)) {
            $object->deliveryNotification = is_int($data['delivery_notification']) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemGeneralSettingsDeliveryNotification::tryFrom($data['delivery_notification']) ?? $data['delivery_notification'] : $data['delivery_notification'];
        }
        if (\array_key_exists('delivery_notification_email', $data)) {
            $values = [];
            foreach ($data['delivery_notification_email'] as $value) {
                $values[] = $value;
            }
            $object->deliveryNotificationEmail = $values;
        }
        if (\array_key_exists('disable_auto_detect_pickup', $data)) {
            $object->disableAutoDetectPickup = is_int($data['disable_auto_detect_pickup']) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemGeneralSettingsDisableAutoDetectPickup::tryFrom($data['disable_auto_detect_pickup']) ?? $data['disable_auto_detect_pickup'] : $data['disable_auto_detect_pickup'];
        }
        if (\array_key_exists('printer_identifier', $data)) {
            $object->printerIdentifier = $data['printer_identifier'];
        }
        return $object;
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $dataArray = [];
        if (array_key_exists('saveRecipientAddress', get_object_vars($data))) {
            $dataArray['save_recipient_address'] = $data->saveRecipientAddress instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemGeneralSettingsSaveRecipientAddress ? $data->saveRecipientAddress->value : $data->saveRecipientAddress;
        }
        if (array_key_exists('deliveryNotification', get_object_vars($data))) {
            $dataArray['delivery_notification'] = $data->deliveryNotification instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemGeneralSettingsDeliveryNotification ? $data->deliveryNotification->value : $data->deliveryNotification;
        }
        if (array_key_exists('deliveryNotificationEmail', get_object_vars($data))) {
            $values = [];
            foreach ($data->deliveryNotificationEmail as $value) {
                $values[] = $value;
            }
            $dataArray['delivery_notification_email'] = $values;
        }
        if (array_key_exists('disableAutoDetectPickup', get_object_vars($data))) {
            $dataArray['disable_auto_detect_pickup'] = $data->disableAutoDetectPickup instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemGeneralSettingsDisableAutoDetectPickup ? $data->disableAutoDetectPickup->value : $data->disableAutoDetectPickup;
        }
        if (array_key_exists('printerIdentifier', get_object_vars($data))) {
            $dataArray['printer_identifier'] = $data->printerIdentifier;
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\ShipmentPostShipmentsRequestV11DataShipmentsItemGeneralSettingsConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemGeneralSettings::class => false];
    }
}