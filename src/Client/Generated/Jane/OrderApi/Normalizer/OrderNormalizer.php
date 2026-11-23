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
class OrderNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\Order::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\Order::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\Order();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Validator\OrderConstraint());
        }
        if (\array_key_exists('id', $data)) {
            $object->id = $data['id'];
            unset($data['id']);
        }
        if (\array_key_exists('orderedAt', $data)) {
            $object->orderedAt = $data['orderedAt'];
            unset($data['orderedAt']);
        }
        if (\array_key_exists('price', $data)) {
            $object->price = $this->denormalizer->denormalize($data['price'], \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\Money::class, 'json', $context);
            unset($data['price']);
        }
        if (\array_key_exists('shopId', $data)) {
            $object->shopId = $data['shopId'];
            unset($data['shopId']);
        }
        if (\array_key_exists('shortId', $data)) {
            $object->shortId = $data['shortId'];
            unset($data['shortId']);
        }
        if (\array_key_exists('status', $data)) {
            $object->status = is_string($data['status']) ? \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\OrderStatus::tryFrom($data['status']) ?? $data['status'] : $data['status'];
            unset($data['status']);
        }
        if (\array_key_exists('assignedUserId', $data)) {
            $object->assignedUserId = $data['assignedUserId'];
            unset($data['assignedUserId']);
        }
        if (\array_key_exists('billingDetails', $data)) {
            $object->billingDetails = $this->denormalizer->denormalize($data['billingDetails'], \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\BillingDetails::class, 'json', $context);
            unset($data['billingDetails']);
        }
        if (\array_key_exists('customerReference', $data)) {
            $object->customerReference = $data['customerReference'];
            unset($data['customerReference']);
        }
        if (\array_key_exists('externalReferences', $data)) {
            $object->externalReferences = $data['externalReferences'];
            unset($data['externalReferences']);
        }
        if (\array_key_exists('lines', $data)) {
            $values = [];
            foreach ($data['lines'] as $value) {
                $values[] = $this->denormalizer->denormalize($value, \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\Line::class, 'json', $context);
            }
            $object->lines = $values;
            unset($data['lines']);
        }
        if (\array_key_exists('notes', $data)) {
            $values_1 = [];
            foreach ($data['notes'] as $value_1) {
                $value_2 = $value_1;
                if (is_array($value_1) and (\array_key_exists('type', $value_1) and $value_1['type'] == 'IMPORTED') and \array_key_exists('id', $value_1) and \array_key_exists('notedAt', $value_1) and (\array_key_exists('origin', $value_1) and $value_1['origin'] == 'SALES_CHANNEL') and \array_key_exists('text', $value_1) and (isset($value_1['type']) and $value_1['type'] == 'IMPORTED')) {
                    $value_2 = $this->denormalizer->denormalize($value_1, \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\NoteFromImported::class, 'json', $context);
                } elseif (is_array($value_1) and (\array_key_exists('type', $value_1) and $value_1['type'] == 'PRINCIPAL') and \array_key_exists('id', $value_1) and \array_key_exists('notedAt', $value_1) and \array_key_exists('principal', $value_1) and \array_key_exists('text', $value_1) and (isset($value_1['type']) and $value_1['type'] == 'PRINCIPAL')) {
                    $value_2 = $this->denormalizer->denormalize($value_1, \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\NoteFromPrincipal::class, 'json', $context);
                }
                $values_1[] = $value_2;
            }
            $object->notes = $values_1;
            unset($data['notes']);
        }
        if (\array_key_exists('packages', $data)) {
            $values_2 = [];
            foreach ($data['packages'] as $value_3) {
                $values_2[] = $this->denormalizer->denormalize($value_3, \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\PackageResponse::class, 'json', $context);
            }
            $object->packages = $values_2;
            unset($data['packages']);
        }
        if (\array_key_exists('rules', $data)) {
            $object->rules = $this->denormalizer->denormalize($data['rules'], \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\OrderRules::class, 'json', $context);
            unset($data['rules']);
        }
        if (\array_key_exists('shipping', $data)) {
            $object->shipping = $this->denormalizer->denormalize($data['shipping'], \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\Shipping::class, 'json', $context);
            unset($data['shipping']);
        }
        foreach ($data as $key => $value_4) {
            if (preg_match('/.*/', (string) $key)) {
                $object[$key] = $value_4;
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
        if (null !== ($data->orderedAt ?? null)) {
            $dataArray['orderedAt'] = $data->orderedAt;
        }
        if (null !== ($data->price ?? null)) {
            $normalized = $this->normalizer->normalize($data->price, 'json', $context);
            $dataArray['price'] = \is_iterable($normalized) ? new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\JsonObject($normalized) : $normalized;
        }
        if (null !== ($data->shopId ?? null)) {
            $dataArray['shopId'] = $data->shopId;
        }
        if (null !== ($data->shortId ?? null)) {
            $dataArray['shortId'] = $data->shortId;
        }
        if (null !== ($data->status ?? null)) {
            $dataArray['status'] = $data->status instanceof \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\OrderStatus ? $data->status->value : $data->status;
        }
        if (array_key_exists('assignedUserId', get_object_vars($data))) {
            $dataArray['assignedUserId'] = $data->assignedUserId;
        }
        if (array_key_exists('billingDetails', get_object_vars($data))) {
            $normalized_1 = $data->billingDetails === null ? null : $this->normalizer->normalize($data->billingDetails, 'json', $context);
            $dataArray['billingDetails'] = \is_iterable($normalized_1) ? new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\JsonObject($normalized_1) : $normalized_1;
        }
        if (array_key_exists('customerReference', get_object_vars($data))) {
            $dataArray['customerReference'] = $data->customerReference;
        }
        if (array_key_exists('externalReferences', get_object_vars($data))) {
            $dataArray['externalReferences'] = $data->externalReferences;
        }
        if (array_key_exists('lines', get_object_vars($data))) {
            $values = [];
            foreach ($data->lines as $value) {
                $normalized_2 = $value === null ? null : $this->normalizer->normalize($value, 'json', $context);
                $values[] = \is_iterable($normalized_2) ? new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\JsonObject($normalized_2) : $normalized_2;
            }
            $dataArray['lines'] = $values;
        }
        if (array_key_exists('notes', get_object_vars($data))) {
            $values_1 = [];
            foreach ($data->notes as $value_1) {
                $value_2 = $value_1;
                if (is_object($value_1)) {
                    $normalized_3 = $this->normalizer->normalize($value_1, 'json', $context);
                    $value_2 = \is_iterable($normalized_3) ? new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\JsonObject($normalized_3) : $normalized_3;
                } elseif (is_object($value_1)) {
                    $normalized_4 = $this->normalizer->normalize($value_1, 'json', $context);
                    $value_2 = \is_iterable($normalized_4) ? new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\JsonObject($normalized_4) : $normalized_4;
                }
                $values_1[] = $value_2;
            }
            $dataArray['notes'] = $values_1;
        }
        if (array_key_exists('packages', get_object_vars($data))) {
            $values_2 = [];
            foreach ($data->packages as $value_3) {
                $normalized_5 = $value_3 === null ? null : $this->normalizer->normalize($value_3, 'json', $context);
                $values_2[] = \is_iterable($normalized_5) ? new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\JsonObject($normalized_5) : $normalized_5;
            }
            $dataArray['packages'] = $values_2;
        }
        if (array_key_exists('rules', get_object_vars($data))) {
            $normalized_6 = $data->rules === null ? null : $this->normalizer->normalize($data->rules, 'json', $context);
            $dataArray['rules'] = \is_iterable($normalized_6) ? new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\JsonObject($normalized_6) : $normalized_6;
        }
        if (array_key_exists('shipping', get_object_vars($data))) {
            $normalized_7 = $data->shipping === null ? null : $this->normalizer->normalize($data->shipping, 'json', $context);
            $dataArray['shipping'] = \is_iterable($normalized_7) ? new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\JsonObject($normalized_7) : $normalized_7;
        }
        foreach ($data->additionalPropertyEntries() as $key => $value_4) {
            if (preg_match('/.*/', (string) $key)) {
                $dataArray[$key] = $value_4;
            }
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Validator\OrderConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\Order::class => false];
    }
}