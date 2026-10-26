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
class ShipmentDefsShipmentSecondaryShipmentsItemNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentSecondaryShipmentsItem::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentSecondaryShipmentsItem::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentSecondaryShipmentsItem();
        if (null === $data || false === \is_array($data)) {
            return $object;
        }
        if (isset($data['$ref']) && !isset($data['type']) && !isset($data['properties']) && !isset($data['allOf'])) {
            return new Reference($data['$ref'], $context['document-origin']);
        }
        if (isset($data['$recursiveRef'])) {
            return new Reference($data['$recursiveRef'], $context['document-origin']);
        }
        if (\array_key_exists('hidden', $data) && \is_int($data['hidden'])) {
            $data['hidden'] = (float) $data['hidden'];
        }
        if (\array_key_exists('delayed', $data) && \is_int($data['delayed'])) {
            $data['delayed'] = (bool) $data['delayed'];
        }
        if (\array_key_exists('delivered', $data) && \is_int($data['delivered'])) {
            $data['delivered'] = (bool) $data['delivered'];
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\ShipmentDefsShipmentSecondaryShipmentsItemConstraint());
        }
        if (\array_key_exists('id', $data)) {
            $object->id = $data['id'];
        }
        if (\array_key_exists('parent_id', $data) && $data['parent_id'] !== null) {
            $value = $data['parent_id'];
            if (is_null($data['parent_id'])) {
                $value = $data['parent_id'];
            } elseif (is_int($data['parent_id'])) {
                $value = $data['parent_id'];
            }
            $object->parentId = $value;
        }
        elseif (\array_key_exists('parent_id', $data)) {
            $object->parentId = null;
        }
        if (\array_key_exists('account_id', $data)) {
            $object->accountId = $data['account_id'];
        }
        if (\array_key_exists('shop_id', $data)) {
            $object->shopId = $data['shop_id'];
        }
        if (\array_key_exists('shipment_type', $data)) {
            $object->shipmentType = $data['shipment_type'];
        }
        if (\array_key_exists('recipient', $data)) {
            $object->recipient = $this->denormalizer->denormalize($data['recipient'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentPropertiesRecipient::class, 'json', $context);
        }
        if (\array_key_exists('sender', $data)) {
            $object->sender = $data['sender'];
        }
        if (\array_key_exists('status', $data)) {
            $object->status = $data['status'];
        }
        if (\array_key_exists('options', $data)) {
            $object->options = $this->denormalizer->denormalize($data['options'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentPropertiesOptions::class, 'json', $context);
        }
        if (\array_key_exists('general_settings', $data) && $data['general_settings'] !== null) {
            $object->generalSettings = $data['general_settings'];
        }
        elseif (\array_key_exists('general_settings', $data)) {
            $object->generalSettings = null;
        }
        if (\array_key_exists('pickup', $data) && $data['pickup'] !== null) {
            $object->pickup = $data['pickup'];
        }
        elseif (\array_key_exists('pickup', $data)) {
            $object->pickup = null;
        }
        if (\array_key_exists('customs_declaration', $data) && $data['customs_declaration'] !== null) {
            $value_1 = $data['customs_declaration'];
            if (is_null($data['customs_declaration'])) {
                $value_1 = $data['customs_declaration'];
            } elseif (is_array($data['customs_declaration']) and (\array_key_exists('contents', $data['customs_declaration']) and ($data['customs_declaration']['contents'] == '1' or $data['customs_declaration']['contents'] == '2' or $data['customs_declaration']['contents'] == '3' or $data['customs_declaration']['contents'] == '4' or $data['customs_declaration']['contents'] == '5')) and \array_key_exists('weight', $data['customs_declaration']) and \array_key_exists('items', $data['customs_declaration'])) {
                $value_1 = $this->denormalizer->denormalize($data['customs_declaration'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentCustomsDeclaration::class, 'json', $context);
            }
            $object->customsDeclaration = $value_1;
        }
        elseif (\array_key_exists('customs_declaration', $data)) {
            $object->customsDeclaration = null;
        }
        if (\array_key_exists('physical_properties', $data) && $data['physical_properties'] !== null) {
            $object->physicalProperties = $data['physical_properties'];
        }
        elseif (\array_key_exists('physical_properties', $data)) {
            $object->physicalProperties = null;
        }
        if (\array_key_exists('reference_identifier', $data) && $data['reference_identifier'] !== null) {
            $value_2 = $data['reference_identifier'];
            if (is_null($data['reference_identifier'])) {
                $value_2 = $data['reference_identifier'];
            } elseif (is_int($data['reference_identifier'])) {
                $value_2 = $data['reference_identifier'];
            } elseif (is_string($data['reference_identifier'])) {
                $value_2 = $data['reference_identifier'];
            }
            $object->referenceIdentifier = $value_2;
        }
        elseif (\array_key_exists('reference_identifier', $data)) {
            $object->referenceIdentifier = null;
        }
        if (\array_key_exists('transaction_status', $data)) {
            $object->transactionStatus = $data['transaction_status'];
        }
        if (\array_key_exists('drop_off_point', $data)) {
            $object->dropOffPoint = $this->denormalizer->denormalize($data['drop_off_point'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentPickup::class, 'json', $context);
        }
        if (\array_key_exists('hidden', $data)) {
            $object->hidden = $data['hidden'];
        }
        if (\array_key_exists('price', $data)) {
            $object->price = $data['price'];
        }
        if (\array_key_exists('barcode', $data)) {
            $object->barcode = $data['barcode'];
        }
        if (\array_key_exists('region', $data)) {
            $value_3 = $data['region'];
            if (isset($data['region'])) {
                $value_3 = $data['region'];
            } elseif (isset($data['region'])) {
                $value_3 = $data['region'];
            }
            $object->region = $value_3;
        }
        if (\array_key_exists('external_provider', $data) && $data['external_provider'] !== null) {
            $value_4 = $data['external_provider'];
            if (is_null($data['external_provider'])) {
                $value_4 = $data['external_provider'];
            } elseif (is_string($data['external_provider'])) {
                $value_4 = \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsExternalProviderPropertiesDisplayName::tryFrom($data['external_provider']) ?? $data['external_provider'];
            }
            $object->externalProvider = $value_4;
        }
        elseif (\array_key_exists('external_provider', $data)) {
            $object->externalProvider = null;
        }
        if (\array_key_exists('external_provider_id', $data) && $data['external_provider_id'] !== null) {
            $value_5 = $data['external_provider_id'];
            if (is_null($data['external_provider_id'])) {
                $value_5 = $data['external_provider_id'];
            } elseif (is_int($data['external_provider_id'])) {
                $value_5 = $data['external_provider_id'];
            }
            $object->externalProviderId = $value_5;
        }
        elseif (\array_key_exists('external_provider_id', $data)) {
            $object->externalProviderId = null;
        }
        if (\array_key_exists('payment_status', $data)) {
            $object->paymentStatus = $data['payment_status'];
        }
        if (\array_key_exists('carrier_id', $data)) {
            $object->carrierId = $data['carrier_id'];
        }
        if (\array_key_exists('platform_id', $data)) {
            $object->platformId = $data['platform_id'];
        }
        if (\array_key_exists('origin', $data) && $data['origin'] !== null) {
            $value_6 = $data['origin'];
            if (is_null($data['origin'])) {
                $value_6 = $data['origin'];
            } elseif (is_string($data['origin'])) {
                $value_6 = $data['origin'];
            }
            $object->origin = $value_6;
        }
        elseif (\array_key_exists('origin', $data)) {
            $object->origin = null;
        }
        if (\array_key_exists('user_agent', $data) && $data['user_agent'] !== null) {
            $value_7 = $data['user_agent'];
            if (is_null($data['user_agent'])) {
                $value_7 = $data['user_agent'];
            } elseif (is_string($data['user_agent'])) {
                $value_7 = $data['user_agent'];
            }
            $object->userAgent = $value_7;
        }
        elseif (\array_key_exists('user_agent', $data)) {
            $object->userAgent = null;
        }
        if (\array_key_exists('secondary_shipments', $data) && $data['secondary_shipments'] !== null) {
            $values = [];
            foreach ($data['secondary_shipments'] as $value_8) {
                $values[] = $value_8;
            }
            $object->secondaryShipments = $values;
        }
        elseif (\array_key_exists('secondary_shipments', $data)) {
            $object->secondaryShipments = null;
        }
        if (\array_key_exists('collection_contact', $data) && $data['collection_contact'] !== null) {
            $value_9 = $data['collection_contact'];
            if (is_null($data['collection_contact'])) {
                $value_9 = $data['collection_contact'];
            } elseif (is_array($data['collection_contact']) and \array_key_exists('last_name', $data['collection_contact']) and \array_key_exists('email', $data['collection_contact']) and \array_key_exists('phone', $data['collection_contact']) and \array_key_exists('company', $data['collection_contact']) and \array_key_exists('street', $data['collection_contact']) and \array_key_exists('number', $data['collection_contact']) and \array_key_exists('postal_code', $data['collection_contact']) and \array_key_exists('city', $data['collection_contact']) and \array_key_exists('cc', $data['collection_contact'])) {
                $value_9 = $this->denormalizer->denormalize($data['collection_contact'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\AccountDefsContact::class, 'json', $context);
            }
            $object->collectionContact = $value_9;
        }
        elseif (\array_key_exists('collection_contact', $data)) {
            $object->collectionContact = null;
        }
        if (\array_key_exists('multi_collo_main_shipment_id', $data) && $data['multi_collo_main_shipment_id'] !== null) {
            $value_10 = $data['multi_collo_main_shipment_id'];
            if (is_null($data['multi_collo_main_shipment_id'])) {
                $value_10 = $data['multi_collo_main_shipment_id'];
            } elseif (is_int($data['multi_collo_main_shipment_id'])) {
                $value_10 = $data['multi_collo_main_shipment_id'];
            }
            $object->multiColloMainShipmentId = $value_10;
        }
        elseif (\array_key_exists('multi_collo_main_shipment_id', $data)) {
            $object->multiColloMainShipmentId = null;
        }
        if (\array_key_exists('external_identifier', $data) && $data['external_identifier'] !== null) {
            $value_11 = $data['external_identifier'];
            if (is_null($data['external_identifier'])) {
                $value_11 = $data['external_identifier'];
            } elseif (is_string($data['external_identifier'])) {
                $value_11 = $data['external_identifier'];
            }
            $object->externalIdentifier = $value_11;
        }
        elseif (\array_key_exists('external_identifier', $data)) {
            $object->externalIdentifier = null;
        }
        if (\array_key_exists('delayed', $data)) {
            $object->delayed = $data['delayed'];
        }
        if (\array_key_exists('delivered', $data)) {
            $object->delivered = $data['delivered'];
        }
        if (\array_key_exists('amount', $data) && $data['amount'] !== null) {
            $value_12 = $data['amount'];
            if (is_int($data['amount'])) {
                $value_12 = $data['amount'];
            } elseif (is_null($data['amount'])) {
                $value_12 = $data['amount'];
            }
            $object->amount = $value_12;
        }
        elseif (\array_key_exists('amount', $data)) {
            $object->amount = null;
        }
        if (\array_key_exists('currency', $data) && $data['currency'] !== null) {
            $value_13 = $data['currency'];
            if (is_null($data['currency'])) {
                $value_13 = $data['currency'];
            } elseif (is_string($data['currency'])) {
                $value_13 = \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\BillingDefsCurrency::tryFrom($data['currency']) ?? $data['currency'];
            }
            $object->currency = $value_13;
        }
        elseif (\array_key_exists('currency', $data)) {
            $object->currency = null;
        }
        if (\array_key_exists('contract_id', $data) && $data['contract_id'] !== null) {
            $value_14 = $data['contract_id'];
            if (is_int($data['contract_id'])) {
                $value_14 = $data['contract_id'];
            } elseif (is_null($data['contract_id'])) {
                $value_14 = $data['contract_id'];
            }
            $object->contractId = $value_14;
        }
        elseif (\array_key_exists('contract_id', $data)) {
            $object->contractId = null;
        }
        if (\array_key_exists('link_consumer_portal', $data) && $data['link_consumer_portal'] !== null) {
            $value_15 = $data['link_consumer_portal'];
            if (isset($data['link_consumer_portal'])) {
                $value_15 = $data['link_consumer_portal'];
            } elseif (isset($data['link_consumer_portal'])) {
                $value_15 = $data['link_consumer_portal'];
            }
            $object->linkConsumerPortal = $value_15;
        }
        elseif (\array_key_exists('link_consumer_portal', $data)) {
            $object->linkConsumerPortal = null;
        }
        if (\array_key_exists('partner_tracktraces', $data)) {
            $values_1 = [];
            foreach ($data['partner_tracktraces'] as $value_16) {
                $values_1[] = $value_16;
            }
            $object->partnerTracktraces = $values_1;
        }
        if (\array_key_exists('pickup_request_number', $data) && $data['pickup_request_number'] !== null) {
            $value_17 = $data['pickup_request_number'];
            if (is_null($data['pickup_request_number'])) {
                $value_17 = $data['pickup_request_number'];
            } elseif (is_string($data['pickup_request_number'])) {
                $value_17 = $data['pickup_request_number'];
            }
            $object->pickupRequestNumber = $value_17;
        }
        elseif (\array_key_exists('pickup_request_number', $data)) {
            $object->pickupRequestNumber = null;
        }
        if (\array_key_exists('order_shipment_identifier', $data) && $data['order_shipment_identifier'] !== null) {
            $value_18 = $data['order_shipment_identifier'];
            if (is_null($data['order_shipment_identifier'])) {
                $value_18 = $data['order_shipment_identifier'];
            } elseif (is_string($data['order_shipment_identifier'])) {
                $value_18 = $data['order_shipment_identifier'];
            }
            $object->orderShipmentIdentifier = $value_18;
        }
        elseif (\array_key_exists('order_shipment_identifier', $data)) {
            $object->orderShipmentIdentifier = null;
        }
        if (\array_key_exists('shipped_items', $data) && $data['shipped_items'] !== null) {
            $values_2 = [];
            foreach ($data['shipped_items'] as $value_19) {
                $values_2[] = $this->denormalizer->denormalize($value_19, \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentShippedItem::class, 'json', $context);
            }
            $object->shippedItems = $values_2;
        }
        elseif (\array_key_exists('shipped_items', $data)) {
            $object->shippedItems = null;
        }
        if (\array_key_exists('created', $data)) {
            $object->created = $data['created'];
        }
        if (\array_key_exists('modified', $data)) {
            $object->modified = $data['modified'];
        }
        if (\array_key_exists('created_by', $data) && $data['created_by'] !== null) {
            $object->createdBy = $data['created_by'];
        }
        elseif (\array_key_exists('created_by', $data)) {
            $object->createdBy = null;
        }
        if (\array_key_exists('modified_by', $data) && $data['modified_by'] !== null) {
            $object->modifiedBy = $data['modified_by'];
        }
        elseif (\array_key_exists('modified_by', $data)) {
            $object->modifiedBy = null;
        }
        return $object;
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $dataArray = [];
        if (null !== ($data->id ?? null)) {
            $dataArray['id'] = $data->id;
        }
        if (null !== ($data->parentId ?? null)) {
            $value = $data->parentId;
            if (is_null($data->parentId)) {
                $value = $data->parentId;
            } elseif (is_int($data->parentId)) {
                $value = $data->parentId;
            }
            $dataArray['parent_id'] = $value;
        }
        if (null !== ($data->accountId ?? null)) {
            $dataArray['account_id'] = $data->accountId;
        }
        if (null !== ($data->shopId ?? null)) {
            $dataArray['shop_id'] = $data->shopId;
        }
        if (null !== ($data->shipmentType ?? null)) {
            $dataArray['shipment_type'] = $data->shipmentType;
        }
        if (null !== ($data->recipient ?? null)) {
            $normalized = $this->normalizer->normalize($data->recipient, 'json', $context);
            $dataArray['recipient'] = \is_iterable($normalized) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized) : $normalized;
        }
        if (null !== ($data->sender ?? null)) {
            $dataArray['sender'] = $data->sender;
        }
        if (null !== ($data->status ?? null)) {
            $dataArray['status'] = $data->status;
        }
        if (null !== ($data->options ?? null)) {
            $normalized_1 = $this->normalizer->normalize($data->options, 'json', $context);
            $dataArray['options'] = \is_iterable($normalized_1) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_1) : $normalized_1;
        }
        if (null !== ($data->generalSettings ?? null)) {
            $dataArray['general_settings'] = $data->generalSettings;
        }
        if (null !== ($data->pickup ?? null)) {
            $dataArray['pickup'] = $data->pickup;
        }
        if (null !== ($data->customsDeclaration ?? null)) {
            $value_1 = $data->customsDeclaration;
            if (is_null($data->customsDeclaration)) {
                $value_1 = $data->customsDeclaration;
            } elseif (is_object($data->customsDeclaration)) {
                $normalized_2 = $this->normalizer->normalize($data->customsDeclaration, 'json', $context);
                $value_1 = \is_iterable($normalized_2) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_2) : $normalized_2;
            }
            $dataArray['customs_declaration'] = $value_1;
        }
        if (null !== ($data->physicalProperties ?? null)) {
            $dataArray['physical_properties'] = $data->physicalProperties;
        }
        if (null !== ($data->referenceIdentifier ?? null)) {
            $value_2 = $data->referenceIdentifier;
            if (is_null($data->referenceIdentifier)) {
                $value_2 = $data->referenceIdentifier;
            } elseif (is_int($data->referenceIdentifier)) {
                $value_2 = $data->referenceIdentifier;
            } elseif (is_string($data->referenceIdentifier)) {
                $value_2 = $data->referenceIdentifier;
            }
            $dataArray['reference_identifier'] = $value_2;
        }
        if (null !== ($data->transactionStatus ?? null)) {
            $dataArray['transaction_status'] = $data->transactionStatus;
        }
        if (null !== ($data->dropOffPoint ?? null)) {
            $normalized_3 = $this->normalizer->normalize($data->dropOffPoint, 'json', $context);
            $dataArray['drop_off_point'] = \is_iterable($normalized_3) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_3) : $normalized_3;
        }
        if (null !== ($data->hidden ?? null)) {
            $dataArray['hidden'] = $data->hidden;
        }
        if (null !== ($data->price ?? null)) {
            $dataArray['price'] = $data->price;
        }
        if (null !== ($data->barcode ?? null)) {
            $dataArray['barcode'] = $data->barcode;
        }
        if (null !== ($data->region ?? null)) {
            $value_3 = $data->region;
            if (!is_null($data->region)) {
                $value_3 = $data->region;
            } elseif (!is_null($data->region)) {
                $value_3 = $data->region;
            }
            $dataArray['region'] = $value_3;
        }
        if (null !== ($data->externalProvider ?? null)) {
            $value_4 = $data->externalProvider;
            if (is_null($data->externalProvider)) {
                $value_4 = $data->externalProvider;
            } elseif ($data->externalProvider instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsExternalProviderPropertiesDisplayName || is_string($data->externalProvider)) {
                $value_4 = $data->externalProvider instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsExternalProviderPropertiesDisplayName ? $data->externalProvider->value : $data->externalProvider;
            }
            $dataArray['external_provider'] = $value_4;
        }
        if (null !== ($data->externalProviderId ?? null)) {
            $value_5 = $data->externalProviderId;
            if (is_null($data->externalProviderId)) {
                $value_5 = $data->externalProviderId;
            } elseif (is_int($data->externalProviderId)) {
                $value_5 = $data->externalProviderId;
            }
            $dataArray['external_provider_id'] = $value_5;
        }
        if (null !== ($data->paymentStatus ?? null)) {
            $dataArray['payment_status'] = $data->paymentStatus;
        }
        if (null !== ($data->carrierId ?? null)) {
            $dataArray['carrier_id'] = $data->carrierId;
        }
        if (null !== ($data->platformId ?? null)) {
            $dataArray['platform_id'] = $data->platformId;
        }
        if (null !== ($data->origin ?? null)) {
            $value_6 = $data->origin;
            if (is_null($data->origin)) {
                $value_6 = $data->origin;
            } elseif (is_string($data->origin)) {
                $value_6 = $data->origin;
            }
            $dataArray['origin'] = $value_6;
        }
        if (null !== ($data->userAgent ?? null)) {
            $value_7 = $data->userAgent;
            if (is_null($data->userAgent)) {
                $value_7 = $data->userAgent;
            } elseif (is_string($data->userAgent)) {
                $value_7 = $data->userAgent;
            }
            $dataArray['user_agent'] = $value_7;
        }
        if (array_key_exists('secondaryShipments', get_object_vars($data))) {
            $values = [];
            foreach ($data->secondaryShipments as $value_8) {
                $values[] = $value_8;
            }
            $dataArray['secondary_shipments'] = $values;
        }
        if (null !== ($data->collectionContact ?? null)) {
            $value_9 = $data->collectionContact;
            if (is_null($data->collectionContact)) {
                $value_9 = $data->collectionContact;
            } elseif (is_object($data->collectionContact)) {
                $normalized_4 = $this->normalizer->normalize($data->collectionContact, 'json', $context);
                $value_9 = \is_iterable($normalized_4) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_4) : $normalized_4;
            }
            $dataArray['collection_contact'] = $value_9;
        }
        if (null !== ($data->multiColloMainShipmentId ?? null)) {
            $value_10 = $data->multiColloMainShipmentId;
            if (is_null($data->multiColloMainShipmentId)) {
                $value_10 = $data->multiColloMainShipmentId;
            } elseif (is_int($data->multiColloMainShipmentId)) {
                $value_10 = $data->multiColloMainShipmentId;
            }
            $dataArray['multi_collo_main_shipment_id'] = $value_10;
        }
        if (null !== ($data->externalIdentifier ?? null)) {
            $value_11 = $data->externalIdentifier;
            if (is_null($data->externalIdentifier)) {
                $value_11 = $data->externalIdentifier;
            } elseif (is_string($data->externalIdentifier)) {
                $value_11 = $data->externalIdentifier;
            }
            $dataArray['external_identifier'] = $value_11;
        }
        if (null !== ($data->delayed ?? null)) {
            $dataArray['delayed'] = $data->delayed;
        }
        if (null !== ($data->delivered ?? null)) {
            $dataArray['delivered'] = $data->delivered;
        }
        if (array_key_exists('amount', get_object_vars($data))) {
            $value_12 = $data->amount;
            if (is_int($data->amount)) {
                $value_12 = $data->amount;
            } elseif (is_null($data->amount)) {
                $value_12 = $data->amount;
            }
            $dataArray['amount'] = $value_12;
        }
        if (array_key_exists('currency', get_object_vars($data))) {
            $value_13 = $data->currency;
            if (is_null($data->currency)) {
                $value_13 = $data->currency;
            } elseif ($data->currency instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\BillingDefsCurrency || is_string($data->currency)) {
                $value_13 = $data->currency instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\BillingDefsCurrency ? $data->currency->value : $data->currency;
            }
            $dataArray['currency'] = $value_13;
        }
        if (null !== ($data->contractId ?? null)) {
            $value_14 = $data->contractId;
            if (is_int($data->contractId)) {
                $value_14 = $data->contractId;
            } elseif (is_null($data->contractId)) {
                $value_14 = $data->contractId;
            }
            $dataArray['contract_id'] = $value_14;
        }
        if (array_key_exists('linkConsumerPortal', get_object_vars($data))) {
            $value_15 = $data->linkConsumerPortal;
            if (!is_null($data->linkConsumerPortal)) {
                $value_15 = $data->linkConsumerPortal;
            } elseif (!is_null($data->linkConsumerPortal)) {
                $value_15 = $data->linkConsumerPortal;
            }
            $dataArray['link_consumer_portal'] = $value_15;
        }
        if (array_key_exists('partnerTracktraces', get_object_vars($data))) {
            $values_1 = [];
            foreach ($data->partnerTracktraces as $value_16) {
                $values_1[] = $value_16;
            }
            $dataArray['partner_tracktraces'] = $values_1;
        }
        if (array_key_exists('pickupRequestNumber', get_object_vars($data))) {
            $value_17 = $data->pickupRequestNumber;
            if (is_null($data->pickupRequestNumber)) {
                $value_17 = $data->pickupRequestNumber;
            } elseif (is_string($data->pickupRequestNumber)) {
                $value_17 = $data->pickupRequestNumber;
            }
            $dataArray['pickup_request_number'] = $value_17;
        }
        if (array_key_exists('orderShipmentIdentifier', get_object_vars($data))) {
            $value_18 = $data->orderShipmentIdentifier;
            if (is_null($data->orderShipmentIdentifier)) {
                $value_18 = $data->orderShipmentIdentifier;
            } elseif (is_string($data->orderShipmentIdentifier)) {
                $value_18 = $data->orderShipmentIdentifier;
            }
            $dataArray['order_shipment_identifier'] = $value_18;
        }
        if (array_key_exists('shippedItems', get_object_vars($data))) {
            $values_2 = [];
            foreach ($data->shippedItems as $value_19) {
                $normalized_5 = $value_19 === null ? null : $this->normalizer->normalize($value_19, 'json', $context);
                $values_2[] = \is_iterable($normalized_5) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_5) : $normalized_5;
            }
            $dataArray['shipped_items'] = $values_2;
        }
        if (null !== ($data->created ?? null)) {
            $dataArray['created'] = $data->created;
        }
        if (null !== ($data->modified ?? null)) {
            $dataArray['modified'] = $data->modified;
        }
        if (null !== ($data->createdBy ?? null)) {
            $dataArray['created_by'] = $data->createdBy;
        }
        if (null !== ($data->modifiedBy ?? null)) {
            $dataArray['modified_by'] = $data->modifiedBy;
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\ShipmentDefsShipmentSecondaryShipmentsItemConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentSecondaryShipmentsItem::class => false];
    }
}