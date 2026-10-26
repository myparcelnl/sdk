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
class ShipmentPostUnrelatedReturnShipmentsRequestDataReturnShipmentsItemNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostUnrelatedReturnShipmentsRequestDataReturnShipmentsItem::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostUnrelatedReturnShipmentsRequestDataReturnShipmentsItem::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostUnrelatedReturnShipmentsRequestDataReturnShipmentsItem();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\ShipmentPostUnrelatedReturnShipmentsRequestDataReturnShipmentsItemConstraint());
        }
        if (\array_key_exists('account_id', $data)) {
            $object->accountId = $data['account_id'];
            unset($data['account_id']);
        }
        if (\array_key_exists('shop_id', $data)) {
            $object->shopId = $data['shop_id'];
            unset($data['shop_id']);
        }
        if (\array_key_exists('carrier', $data)) {
            $object->carrier = is_int($data['carrier']) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesCarrierReturns::tryFrom($data['carrier']) ?? $data['carrier'] : $data['carrier'];
            unset($data['carrier']);
        }
        if (\array_key_exists('email', $data)) {
            $object->email = $data['email'];
            unset($data['email']);
        }
        if (\array_key_exists('name', $data)) {
            $object->name = $data['name'];
            unset($data['name']);
        }
        if (\array_key_exists('cc', $data)) {
            $object->cc = $data['cc'];
            unset($data['cc']);
        }
        if (\array_key_exists('sender', $data)) {
            $object->sender = $this->denormalizer->denormalize($data['sender'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostUnrelatedReturnShipmentsRequestDataReturnShipmentsItemSender::class, 'json', $context);
            unset($data['sender']);
        }
        if (\array_key_exists('physical_properties', $data)) {
            $object->physicalProperties = $this->denormalizer->denormalize($data['physical_properties'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostUnrelatedReturnShipmentsRequestDataReturnShipmentsItemPhysicalProperties::class, 'json', $context);
            unset($data['physical_properties']);
        }
        if (\array_key_exists('customs_declaration', $data)) {
            $object->customsDeclaration = $this->denormalizer->denormalize($data['customs_declaration'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentCustomsDeclaration::class, 'json', $context);
            unset($data['customs_declaration']);
        }
        if (\array_key_exists('general_settings', $data)) {
            $object->generalSettings = $this->denormalizer->denormalize($data['general_settings'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostUnrelatedReturnShipmentsRequestDataReturnShipmentsItemGeneralSettings::class, 'json', $context);
            unset($data['general_settings']);
        }
        if (\array_key_exists('options', $data)) {
            $object->options = $this->denormalizer->denormalize($data['options'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentShipmentOptionsReturnsOptions::class, 'json', $context);
            unset($data['options']);
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
        if (array_key_exists('accountId', get_object_vars($data))) {
            $dataArray['account_id'] = $data->accountId;
        }
        if (array_key_exists('shopId', get_object_vars($data))) {
            $dataArray['shop_id'] = $data->shopId;
        }
        if (null !== ($data->carrier ?? null)) {
            $dataArray['carrier'] = $data->carrier instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesCarrierReturns ? $data->carrier->value : $data->carrier;
        }
        if (array_key_exists('email', get_object_vars($data))) {
            $dataArray['email'] = $data->email;
        }
        if (array_key_exists('name', get_object_vars($data))) {
            $dataArray['name'] = $data->name;
        }
        if (array_key_exists('cc', get_object_vars($data))) {
            $dataArray['cc'] = $data->cc;
        }
        if (array_key_exists('sender', get_object_vars($data))) {
            $normalized = $data->sender === null ? null : $this->normalizer->normalize($data->sender, 'json', $context);
            $dataArray['sender'] = \is_iterable($normalized) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized) : $normalized;
        }
        if (array_key_exists('physicalProperties', get_object_vars($data))) {
            $normalized_1 = $data->physicalProperties === null ? null : $this->normalizer->normalize($data->physicalProperties, 'json', $context);
            $dataArray['physical_properties'] = \is_iterable($normalized_1) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_1) : $normalized_1;
        }
        if (array_key_exists('customsDeclaration', get_object_vars($data))) {
            $normalized_2 = $data->customsDeclaration === null ? null : $this->normalizer->normalize($data->customsDeclaration, 'json', $context);
            $dataArray['customs_declaration'] = \is_iterable($normalized_2) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_2) : $normalized_2;
        }
        if (array_key_exists('generalSettings', get_object_vars($data))) {
            $normalized_3 = $data->generalSettings === null ? null : $this->normalizer->normalize($data->generalSettings, 'json', $context);
            $dataArray['general_settings'] = \is_iterable($normalized_3) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_3) : $normalized_3;
        }
        if (array_key_exists('options', get_object_vars($data))) {
            $normalized_4 = $data->options === null ? null : $this->normalizer->normalize($data->options, 'json', $context);
            $dataArray['options'] = \is_iterable($normalized_4) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_4) : $normalized_4;
        }
        foreach ($data->additionalPropertyEntries() as $key => $value) {
            if (preg_match('/.*/', (string) $key)) {
                $dataArray[$key] = $value;
            }
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\ShipmentPostUnrelatedReturnShipmentsRequestDataReturnShipmentsItemConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostUnrelatedReturnShipmentsRequestDataReturnShipmentsItem::class => false];
    }
}