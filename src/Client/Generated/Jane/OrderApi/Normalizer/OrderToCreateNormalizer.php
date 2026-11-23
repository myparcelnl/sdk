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
class OrderToCreateNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\OrderToCreate::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\OrderToCreate::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\OrderToCreate();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Validator\OrderToCreateConstraint());
        }
        if (\array_key_exists('billingDetails', $data)) {
            $object->billingDetails = $this->denormalizer->denormalize($data['billingDetails'], \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\OrderToCreateBillingDetails::class, 'json', $context);
        }
        if (\array_key_exists('customerReference', $data)) {
            $object->customerReference = $data['customerReference'];
        }
        if (\array_key_exists('lines', $data)) {
            $values = [];
            foreach ($data['lines'] as $value) {
                $values[] = $this->denormalizer->denormalize($value, \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\OrderLineToCreate::class, 'json', $context);
            }
            $object->lines = $values;
        }
        if (\array_key_exists('orderedAt', $data)) {
            $date = \DateTime::createFromFormat('Y-m-d\TH:i:sP', $data['orderedAt']);
            if (false === $date) {
                if (is_string($data['orderedAt']) and 1 === preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}(?:\.\d+)?(?:Z|[+-]\d{2}:\d{2})$/', $data['orderedAt'])) {
                    try {
                        $date = new \DateTime($data['orderedAt']);
                    } catch (\Exception) {
                        throw new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\Normalizer\InvalidDateException($data['orderedAt'], 'Y-m-d\TH:i:sP');
                    }
                } else {
                    throw new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\Normalizer\InvalidDateException($data['orderedAt'], 'Y-m-d\TH:i:sP');
                }
            }
            $object->orderedAt = $date;
        }
        if (\array_key_exists('shipping', $data)) {
            $object->shipping = $this->denormalizer->denormalize($data['shipping'], \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\OrderToCreateShipping::class, 'json', $context);
        }
        if (\array_key_exists('shopId', $data)) {
            $object->shopId = $data['shopId'];
        }
        if (\array_key_exists('sourceId', $data)) {
            $object->sourceId = $data['sourceId'];
        }
        return $object;
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $dataArray = [];
        if (array_key_exists('billingDetails', get_object_vars($data))) {
            $normalized = $data->billingDetails === null ? null : $this->normalizer->normalize($data->billingDetails, 'json', $context);
            $dataArray['billingDetails'] = \is_iterable($normalized) ? new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\JsonObject($normalized) : $normalized;
        }
        if (array_key_exists('customerReference', get_object_vars($data))) {
            $dataArray['customerReference'] = $data->customerReference;
        }
        if (array_key_exists('lines', get_object_vars($data))) {
            $values = [];
            foreach ($data->lines as $value) {
                $normalized_1 = $value === null ? null : $this->normalizer->normalize($value, 'json', $context);
                $values[] = \is_iterable($normalized_1) ? new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\JsonObject($normalized_1) : $normalized_1;
            }
            $dataArray['lines'] = $values;
        }
        if (null !== ($data->orderedAt ?? null)) {
            $dataArray['orderedAt'] = $data->orderedAt->format('Y-m-d\TH:i:sP');
        }
        if (array_key_exists('shipping', get_object_vars($data))) {
            $normalized_2 = $data->shipping === null ? null : $this->normalizer->normalize($data->shipping, 'json', $context);
            $dataArray['shipping'] = \is_iterable($normalized_2) ? new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\JsonObject($normalized_2) : $normalized_2;
        }
        if (null !== ($data->shopId ?? null)) {
            $dataArray['shopId'] = $data->shopId;
        }
        if (null !== ($data->sourceId ?? null)) {
            $dataArray['sourceId'] = $data->sourceId;
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Validator\OrderToCreateConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\OrderToCreate::class => false];
    }
}