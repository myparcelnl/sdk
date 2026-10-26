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
class ShipmentNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\Shipment::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\Shipment::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\Shipment();
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
            $data['hidden'] = (bool) $data['hidden'];
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Validator\ShipmentConstraint());
        }
        if (\array_key_exists('id', $data)) {
            $object->id = $data['id'];
            unset($data['id']);
        }
        if (\array_key_exists('carrier', $data)) {
            $object->carrier = is_string($data['carrier']) ? \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\Carrier::tryFrom($data['carrier']) ?? $data['carrier'] : $data['carrier'];
            unset($data['carrier']);
        }
        if (\array_key_exists('createdAt', $data)) {
            $object->createdAt = $data['createdAt'];
            unset($data['createdAt']);
        }
        if (\array_key_exists('direction', $data)) {
            $object->direction = is_string($data['direction']) ? \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ShipmentDirection::tryFrom($data['direction']) ?? $data['direction'] : $data['direction'];
            unset($data['direction']);
        }
        if (\array_key_exists('hidden', $data)) {
            $object->hidden = $data['hidden'];
            unset($data['hidden']);
        }
        if (\array_key_exists('packageType', $data)) {
            $object->packageType = is_string($data['packageType']) ? \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\PackageType::tryFrom($data['packageType']) ?? $data['packageType'] : $data['packageType'];
            unset($data['packageType']);
        }
        if (\array_key_exists('recipient', $data)) {
            $object->recipient = $this->denormalizer->denormalize($data['recipient'], \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ShipmentRecipient::class, 'json', $context);
            unset($data['recipient']);
        }
        if (\array_key_exists('customContractId', $data)) {
            $object->customContractId = $data['customContractId'];
            unset($data['customContractId']);
        }
        if (\array_key_exists('customsDeclaration', $data)) {
            $object->customsDeclaration = $this->denormalizer->denormalize($data['customsDeclaration'], \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\CustomsDeclarationResponse::class, 'json', $context);
            unset($data['customsDeclaration']);
        }
        if (\array_key_exists('delivery', $data)) {
            $object->delivery = $this->denormalizer->denormalize($data['delivery'], \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\Delivery::class, 'json', $context);
            unset($data['delivery']);
        }
        if (\array_key_exists('dropOff', $data)) {
            $object->dropOff = $this->denormalizer->denormalize($data['dropOff'], \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\DropOff::class, 'json', $context);
            unset($data['dropOff']);
        }
        if (\array_key_exists('label', $data)) {
            $value = $data['label'];
            if (is_array($data['label']) and \array_key_exists('credited', $data['label']) and \array_key_exists('barcode', $data['label']) and \array_key_exists('delayed', $data['label'])) {
                $value = $this->denormalizer->denormalize($data['label'], \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\LabelWithBarcode::class, 'json', $context);
            } elseif (is_array($data['label']) and \array_key_exists('credited', $data['label'])) {
                $value = $this->denormalizer->denormalize($data['label'], \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\LabelWithoutBarcode::class, 'json', $context);
            }
            $object->label = $value;
            unset($data['label']);
        }
        if (\array_key_exists('multicollo', $data)) {
            $object->multicollo = $this->denormalizer->denormalize($data['multicollo'], \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ShipmentMulticollo::class, 'json', $context);
            unset($data['multicollo']);
        }
        if (\array_key_exists('options', $data)) {
            $object->options = $this->denormalizer->denormalize($data['options'], \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ShipmentOptions::class, 'json', $context);
            unset($data['options']);
        }
        if (\array_key_exists('pickup', $data)) {
            $object->pickup = $data['pickup'];
            unset($data['pickup']);
        }
        if (\array_key_exists('price', $data)) {
            $object->price = $this->denormalizer->denormalize($data['price'], \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\Money::class, 'json', $context);
            unset($data['price']);
        }
        if (\array_key_exists('sender', $data)) {
            $object->sender = $this->denormalizer->denormalize($data['sender'], \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ShipmentSender::class, 'json', $context);
            unset($data['sender']);
        }
        foreach ($data as $key => $value_1) {
            if (preg_match('/.*/', (string) $key)) {
                $object[$key] = $value_1;
            }
        }
        return $object;
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $dataArray = [];
        if (null !== ($data->id ?? null)) {
            $dataArray['id'] = $data->id;
        }
        if (null !== ($data->carrier ?? null)) {
            $dataArray['carrier'] = $data->carrier instanceof \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\Carrier ? $data->carrier->value : $data->carrier;
        }
        if (null !== ($data->createdAt ?? null)) {
            $dataArray['createdAt'] = $data->createdAt;
        }
        if (null !== ($data->direction ?? null)) {
            $dataArray['direction'] = $data->direction instanceof \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ShipmentDirection ? $data->direction->value : $data->direction;
        }
        if (null !== ($data->hidden ?? null)) {
            $dataArray['hidden'] = $data->hidden;
        }
        if (null !== ($data->packageType ?? null)) {
            $dataArray['packageType'] = $data->packageType instanceof \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\PackageType ? $data->packageType->value : $data->packageType;
        }
        if (null !== ($data->recipient ?? null)) {
            $normalized = $this->normalizer->normalize($data->recipient, 'json', $context);
            $dataArray['recipient'] = \is_iterable($normalized) ? new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\JsonObject($normalized) : $normalized;
        }
        if (array_key_exists('customContractId', get_object_vars($data))) {
            $dataArray['customContractId'] = $data->customContractId;
        }
        if (array_key_exists('customsDeclaration', get_object_vars($data))) {
            $normalized_1 = $data->customsDeclaration === null ? null : $this->normalizer->normalize($data->customsDeclaration, 'json', $context);
            $dataArray['customsDeclaration'] = \is_iterable($normalized_1) ? new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\JsonObject($normalized_1) : $normalized_1;
        }
        if (array_key_exists('delivery', get_object_vars($data))) {
            $normalized_2 = $data->delivery === null ? null : $this->normalizer->normalize($data->delivery, 'json', $context);
            $dataArray['delivery'] = \is_iterable($normalized_2) ? new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\JsonObject($normalized_2) : $normalized_2;
        }
        if (array_key_exists('dropOff', get_object_vars($data))) {
            $normalized_3 = $data->dropOff === null ? null : $this->normalizer->normalize($data->dropOff, 'json', $context);
            $dataArray['dropOff'] = \is_iterable($normalized_3) ? new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\JsonObject($normalized_3) : $normalized_3;
        }
        if (array_key_exists('label', get_object_vars($data))) {
            $value = $data->label;
            if (is_object($data->label)) {
                $normalized_4 = $this->normalizer->normalize($data->label, 'json', $context);
                $value = \is_iterable($normalized_4) ? new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\JsonObject($normalized_4) : $normalized_4;
            } elseif (is_object($data->label)) {
                $normalized_5 = $this->normalizer->normalize($data->label, 'json', $context);
                $value = \is_iterable($normalized_5) ? new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\JsonObject($normalized_5) : $normalized_5;
            }
            $dataArray['label'] = $value;
        }
        if (array_key_exists('multicollo', get_object_vars($data))) {
            $normalized_6 = $data->multicollo === null ? null : $this->normalizer->normalize($data->multicollo, 'json', $context);
            $dataArray['multicollo'] = \is_iterable($normalized_6) ? new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\JsonObject($normalized_6) : $normalized_6;
        }
        if (array_key_exists('options', get_object_vars($data))) {
            $normalized_7 = $data->options === null ? null : $this->normalizer->normalize($data->options, 'json', $context);
            $dataArray['options'] = \is_iterable($normalized_7) ? new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\JsonObject($normalized_7) : $normalized_7;
        }
        if (array_key_exists('pickup', get_object_vars($data))) {
            $dataArray['pickup'] = $data->pickup;
        }
        if (array_key_exists('price', get_object_vars($data))) {
            $normalized_8 = $data->price === null ? null : $this->normalizer->normalize($data->price, 'json', $context);
            $dataArray['price'] = \is_iterable($normalized_8) ? new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\JsonObject($normalized_8) : $normalized_8;
        }
        if (array_key_exists('sender', get_object_vars($data))) {
            $normalized_9 = $data->sender === null ? null : $this->normalizer->normalize($data->sender, 'json', $context);
            $dataArray['sender'] = \is_iterable($normalized_9) ? new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\JsonObject($normalized_9) : $normalized_9;
        }
        foreach ($data->additionalPropertyEntries() as $key => $value_1) {
            if (preg_match('/.*/', (string) $key)) {
                $dataArray[$key] = $value_1;
            }
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Validator\ShipmentConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\Shipment::class => false];
    }
}