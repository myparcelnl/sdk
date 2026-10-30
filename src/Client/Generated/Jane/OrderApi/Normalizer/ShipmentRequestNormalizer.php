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
class ShipmentRequestNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ShipmentRequest::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ShipmentRequest::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ShipmentRequest();
        if (null === $data || false === \is_array($data)) {
            return $object;
        }
        if (isset($data['$ref']) && !isset($data['type']) && !isset($data['properties']) && !isset($data['allOf'])) {
            return new Reference($data['$ref'], $context['document-origin']);
        }
        if (isset($data['$recursiveRef'])) {
            return new Reference($data['$recursiveRef'], $context['document-origin']);
        }
        if (\array_key_exists('disableAutoDetectPickup', $data) && \is_int($data['disableAutoDetectPickup'])) {
            $data['disableAutoDetectPickup'] = (bool) $data['disableAutoDetectPickup'];
        }
        if (\array_key_exists('saveRecipientAddress', $data) && \is_int($data['saveRecipientAddress'])) {
            $data['saveRecipientAddress'] = (bool) $data['saveRecipientAddress'];
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Validator\ShipmentRequestConstraint());
        }
        if (\array_key_exists('carrier', $data)) {
            $object->carrier = is_string($data['carrier']) ? \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\Carrier::tryFrom($data['carrier']) ?? $data['carrier'] : $data['carrier'];
            unset($data['carrier']);
        }
        if (\array_key_exists('direction', $data)) {
            $object->direction = is_string($data['direction']) ? \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ShipmentDirection::tryFrom($data['direction']) ?? $data['direction'] : $data['direction'];
            unset($data['direction']);
        }
        if (\array_key_exists('disableAutoDetectPickup', $data)) {
            $object->disableAutoDetectPickup = $data['disableAutoDetectPickup'];
            unset($data['disableAutoDetectPickup']);
        }
        if (\array_key_exists('packageType', $data)) {
            $object->packageType = is_string($data['packageType']) ? \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\PackageType::tryFrom($data['packageType']) ?? $data['packageType'] : $data['packageType'];
            unset($data['packageType']);
        }
        if (\array_key_exists('recipient', $data)) {
            $object->recipient = $this->denormalizer->denormalize($data['recipient'], \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ShipmentRequestRecipient::class, 'json', $context);
            unset($data['recipient']);
        }
        if (\array_key_exists('customContractId', $data)) {
            $object->customContractId = $data['customContractId'];
            unset($data['customContractId']);
        }
        if (\array_key_exists('delivery', $data)) {
            $object->delivery = $this->denormalizer->denormalize($data['delivery'], \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\Delivery::class, 'json', $context);
            unset($data['delivery']);
        }
        if (\array_key_exists('options', $data)) {
            $object->options = $this->denormalizer->denormalize($data['options'], \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ShipmentOptionsCommon::class, 'json', $context);
            unset($data['options']);
        }
        if (\array_key_exists('pickup', $data)) {
            $object->pickup = $data['pickup'];
            unset($data['pickup']);
        }
        if (\array_key_exists('sender', $data)) {
            $object->sender = $this->denormalizer->denormalize($data['sender'], \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ShipmentRequestSender::class, 'json', $context);
            unset($data['sender']);
        }
        if (\array_key_exists('saveRecipientAddress', $data)) {
            $object->saveRecipientAddress = $data['saveRecipientAddress'];
            unset($data['saveRecipientAddress']);
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
        if (null !== ($data->carrier ?? null)) {
            $dataArray['carrier'] = $data->carrier instanceof \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\Carrier ? $data->carrier->value : $data->carrier;
        }
        if (null !== ($data->direction ?? null)) {
            $dataArray['direction'] = $data->direction instanceof \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ShipmentDirection ? $data->direction->value : $data->direction;
        }
        if (null !== ($data->disableAutoDetectPickup ?? null)) {
            $dataArray['disableAutoDetectPickup'] = $data->disableAutoDetectPickup;
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
        if (array_key_exists('delivery', get_object_vars($data))) {
            $normalized_1 = $data->delivery === null ? null : $this->normalizer->normalize($data->delivery, 'json', $context);
            $dataArray['delivery'] = \is_iterable($normalized_1) ? new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\JsonObject($normalized_1) : $normalized_1;
        }
        if (array_key_exists('options', get_object_vars($data))) {
            $normalized_2 = $data->options === null ? null : $this->normalizer->normalize($data->options, 'json', $context);
            $dataArray['options'] = \is_iterable($normalized_2) ? new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\JsonObject($normalized_2) : $normalized_2;
        }
        if (array_key_exists('pickup', get_object_vars($data))) {
            $dataArray['pickup'] = $data->pickup;
        }
        if (array_key_exists('sender', get_object_vars($data))) {
            $normalized_3 = $data->sender === null ? null : $this->normalizer->normalize($data->sender, 'json', $context);
            $dataArray['sender'] = \is_iterable($normalized_3) ? new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\JsonObject($normalized_3) : $normalized_3;
        }
        if (array_key_exists('saveRecipientAddress', get_object_vars($data))) {
            $dataArray['saveRecipientAddress'] = $data->saveRecipientAddress;
        }
        foreach ($data->additionalPropertyEntries() as $key => $value) {
            if (preg_match('/.*/', (string) $key)) {
                $dataArray[$key] = $value;
            }
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Validator\ShipmentRequestConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ShipmentRequest::class => false];
    }
}