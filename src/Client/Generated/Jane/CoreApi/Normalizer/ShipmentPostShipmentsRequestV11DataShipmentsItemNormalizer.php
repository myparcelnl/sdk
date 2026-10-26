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
class ShipmentPostShipmentsRequestV11DataShipmentsItemNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItem::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItem::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItem();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\ShipmentPostShipmentsRequestV11DataShipmentsItemConstraint());
        }
        if (\array_key_exists('account_id', $data)) {
            $object->accountId = $data['account_id'];
        }
        if (\array_key_exists('shop_id', $data)) {
            $object->shopId = $data['shop_id'];
        }
        if (\array_key_exists('contract_id', $data)) {
            $object->contractId = $data['contract_id'];
        }
        if (\array_key_exists('reference_identifier', $data)) {
            $value = $data['reference_identifier'];
            if (is_int($data['reference_identifier'])) {
                $value = $data['reference_identifier'];
            } elseif (is_string($data['reference_identifier'])) {
                $value = $data['reference_identifier'];
            }
            $object->referenceIdentifier = $value;
        }
        if (\array_key_exists('recipient', $data)) {
            $object->recipient = $this->denormalizer->denormalize($data['recipient'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemRecipient::class, 'json', $context);
        }
        if (\array_key_exists('sender', $data)) {
            $object->sender = $this->denormalizer->denormalize($data['sender'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentSender::class, 'json', $context);
        }
        if (\array_key_exists('recipients', $data)) {
            $values = [];
            foreach ($data['recipients'] as $value_1) {
                $values[] = $value_1;
            }
            $object->recipients = $values;
        }
        if (\array_key_exists('physical_properties', $data)) {
            $object->physicalProperties = $this->denormalizer->denormalize($data['physical_properties'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemPhysicalProperties::class, 'json', $context);
        }
        if (\array_key_exists('options', $data)) {
            $object->options = $this->denormalizer->denormalize($data['options'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemOptions::class, 'json', $context);
        }
        if (\array_key_exists('customs_declaration', $data)) {
            $object->customsDeclaration = $this->denormalizer->denormalize($data['customs_declaration'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentCustomsDeclaration::class, 'json', $context);
        }
        if (\array_key_exists('carrier', $data)) {
            $object->carrier = is_int($data['carrier']) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesCarrier::tryFrom($data['carrier']) ?? $data['carrier'] : $data['carrier'];
        }
        if (\array_key_exists('pickup', $data) && $data['pickup'] !== null) {
            $object->pickup = $this->denormalizer->denormalize($data['pickup'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentPickup::class, 'json', $context);
        }
        elseif (\array_key_exists('pickup', $data)) {
            $object->pickup = null;
        }
        if (\array_key_exists('drop_off_point', $data) && $data['drop_off_point'] !== null) {
            $object->dropOffPoint = $this->denormalizer->denormalize($data['drop_off_point'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemDropOffPoint::class, 'json', $context);
        }
        elseif (\array_key_exists('drop_off_point', $data)) {
            $object->dropOffPoint = null;
        }
        if (\array_key_exists('note', $data)) {
            $object->note = $data['note'];
        }
        if (\array_key_exists('status', $data)) {
            $object->status = is_int($data['status']) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemStatus::tryFrom($data['status']) ?? $data['status'] : $data['status'];
        }
        if (\array_key_exists('delivered', $data)) {
            $object->delivered = is_int($data['delivered']) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemDelivered::tryFrom($data['delivered']) ?? $data['delivered'] : $data['delivered'];
        }
        if (\array_key_exists('general_settings', $data)) {
            $object->generalSettings = $this->denormalizer->denormalize($data['general_settings'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemGeneralSettings::class, 'json', $context);
        }
        if (\array_key_exists('secondary_shipments', $data)) {
            $values_1 = [];
            foreach ($data['secondary_shipments'] as $value_2) {
                $values_1[] = $this->denormalizer->denormalize($value_2, \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItem::class, 'json', $context);
            }
            $object->secondaryShipments = $values_1;
        }
        if (\array_key_exists('collection_contact', $data)) {
            $object->collectionContact = $this->denormalizer->denormalize($data['collection_contact'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemCollectionContact::class, 'json', $context);
        }
        if (\array_key_exists('hidden', $data)) {
            $object->hidden = is_int($data['hidden']) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemHidden::tryFrom($data['hidden']) ?? $data['hidden'] : $data['hidden'];
        }
        if (\array_key_exists('shipped_items', $data)) {
            $values_2 = [];
            foreach ($data['shipped_items'] as $value_3) {
                $values_2[] = $this->denormalizer->denormalize($value_3, \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemShippedItemsItem::class, 'json', $context);
            }
            $object->shippedItems = $values_2;
        }
        return $object;
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $dataArray = [];
        if (array_key_exists('accountId', get_object_vars($data))) {
            $dataArray['account_id'] = $data->accountId;
        }
        if (array_key_exists('shopId', get_object_vars($data))) {
            $dataArray['shop_id'] = $data->shopId;
        }
        if (array_key_exists('contractId', get_object_vars($data))) {
            $dataArray['contract_id'] = $data->contractId;
        }
        if (array_key_exists('referenceIdentifier', get_object_vars($data))) {
            $value = $data->referenceIdentifier;
            if (is_int($data->referenceIdentifier)) {
                $value = $data->referenceIdentifier;
            } elseif (is_string($data->referenceIdentifier)) {
                $value = $data->referenceIdentifier;
            }
            $dataArray['reference_identifier'] = $value;
        }
        if (null !== ($data->recipient ?? null)) {
            $normalized = $this->normalizer->normalize($data->recipient, 'json', $context);
            $dataArray['recipient'] = \is_iterable($normalized) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized) : $normalized;
        }
        if (array_key_exists('sender', get_object_vars($data))) {
            $normalized_1 = $data->sender === null ? null : $this->normalizer->normalize($data->sender, 'json', $context);
            $dataArray['sender'] = \is_iterable($normalized_1) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_1) : $normalized_1;
        }
        if (array_key_exists('recipients', get_object_vars($data))) {
            $values = [];
            foreach ($data->recipients as $value_1) {
                $values[] = $value_1;
            }
            $dataArray['recipients'] = $values;
        }
        if (array_key_exists('physicalProperties', get_object_vars($data))) {
            $normalized_2 = $data->physicalProperties === null ? null : $this->normalizer->normalize($data->physicalProperties, 'json', $context);
            $dataArray['physical_properties'] = \is_iterable($normalized_2) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_2) : $normalized_2;
        }
        if (null !== ($data->options ?? null)) {
            $normalized_3 = $this->normalizer->normalize($data->options, 'json', $context);
            $dataArray['options'] = \is_iterable($normalized_3) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_3) : $normalized_3;
        }
        if (array_key_exists('customsDeclaration', get_object_vars($data))) {
            $normalized_4 = $data->customsDeclaration === null ? null : $this->normalizer->normalize($data->customsDeclaration, 'json', $context);
            $dataArray['customs_declaration'] = \is_iterable($normalized_4) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_4) : $normalized_4;
        }
        if (null !== ($data->carrier ?? null)) {
            $dataArray['carrier'] = $data->carrier instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesCarrier ? $data->carrier->value : $data->carrier;
        }
        if (array_key_exists('pickup', get_object_vars($data))) {
            $normalized_5 = $data->pickup === null ? null : $this->normalizer->normalize($data->pickup, 'json', $context);
            $dataArray['pickup'] = \is_iterable($normalized_5) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_5) : $normalized_5;
        }
        if (array_key_exists('dropOffPoint', get_object_vars($data))) {
            $normalized_6 = $data->dropOffPoint === null ? null : $this->normalizer->normalize($data->dropOffPoint, 'json', $context);
            $dataArray['drop_off_point'] = \is_iterable($normalized_6) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_6) : $normalized_6;
        }
        if (array_key_exists('note', get_object_vars($data))) {
            $dataArray['note'] = $data->note;
        }
        if (array_key_exists('status', get_object_vars($data))) {
            $dataArray['status'] = $data->status instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemStatus ? $data->status->value : $data->status;
        }
        if (array_key_exists('delivered', get_object_vars($data))) {
            $dataArray['delivered'] = $data->delivered instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemDelivered ? $data->delivered->value : $data->delivered;
        }
        if (array_key_exists('generalSettings', get_object_vars($data))) {
            $normalized_7 = $data->generalSettings === null ? null : $this->normalizer->normalize($data->generalSettings, 'json', $context);
            $dataArray['general_settings'] = \is_iterable($normalized_7) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_7) : $normalized_7;
        }
        if (array_key_exists('secondaryShipments', get_object_vars($data))) {
            $values_1 = [];
            foreach ($data->secondaryShipments as $value_2) {
                $normalized_8 = $value_2 === null ? null : $this->normalizer->normalize($value_2, 'json', $context);
                $values_1[] = \is_iterable($normalized_8) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_8) : $normalized_8;
            }
            $dataArray['secondary_shipments'] = $values_1;
        }
        if (array_key_exists('collectionContact', get_object_vars($data))) {
            $normalized_9 = $data->collectionContact === null ? null : $this->normalizer->normalize($data->collectionContact, 'json', $context);
            $dataArray['collection_contact'] = \is_iterable($normalized_9) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_9) : $normalized_9;
        }
        if (array_key_exists('hidden', get_object_vars($data))) {
            $dataArray['hidden'] = $data->hidden instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemHidden ? $data->hidden->value : $data->hidden;
        }
        if (array_key_exists('shippedItems', get_object_vars($data))) {
            $values_2 = [];
            foreach ($data->shippedItems as $value_3) {
                $normalized_10 = $value_3 === null ? null : $this->normalizer->normalize($value_3, 'json', $context);
                $values_2[] = \is_iterable($normalized_10) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_10) : $normalized_10;
            }
            $dataArray['shipped_items'] = $values_2;
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\ShipmentPostShipmentsRequestV11DataShipmentsItemConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItem::class => false];
    }
}