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
class ProductToCreateNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ProductToCreate::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ProductToCreate::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ProductToCreate();
        if (null === $data || false === \is_array($data)) {
            return $object;
        }
        if (isset($data['$ref']) && !isset($data['type']) && !isset($data['properties']) && !isset($data['allOf'])) {
            return new Reference($data['$ref'], $context['document-origin']);
        }
        if (isset($data['$recursiveRef'])) {
            return new Reference($data['$recursiveRef'], $context['document-origin']);
        }
        if (\array_key_exists('containsDangerousSubstances', $data) && \is_int($data['containsDangerousSubstances'])) {
            $data['containsDangerousSubstances'] = (bool) $data['containsDangerousSubstances'];
        }
        if (\array_key_exists('hasUnlimitedShelfLife', $data) && \is_int($data['hasUnlimitedShelfLife'])) {
            $data['hasUnlimitedShelfLife'] = (bool) $data['hasUnlimitedShelfLife'];
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Validator\ProductToCreateConstraint());
        }
        if (\array_key_exists('barcode', $data)) {
            $object->barcode = $data['barcode'];
            unset($data['barcode']);
        }
        if (\array_key_exists('containsDangerousSubstances', $data)) {
            $object->containsDangerousSubstances = $data['containsDangerousSubstances'];
            unset($data['containsDangerousSubstances']);
        }
        if (\array_key_exists('countryCodeOfOrigin', $data)) {
            $object->countryCodeOfOrigin = $data['countryCodeOfOrigin'];
            unset($data['countryCodeOfOrigin']);
        }
        if (\array_key_exists('descriptions', $data)) {
            $values = [];
            foreach ($data['descriptions'] as $value) {
                $values[] = $this->denormalizer->denormalize($value, \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ProductToCreateDescriptionsItem::class, 'json', $context);
            }
            $object->descriptions = $values;
            unset($data['descriptions']);
        }
        if (\array_key_exists('ean', $data)) {
            $object->ean = $data['ean'];
            unset($data['ean']);
        }
        if (\array_key_exists('fulfilment', $data)) {
            $object->fulfilment = $this->denormalizer->denormalize($data['fulfilment'], \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ProductToCreateFulfilment::class, 'json', $context);
            unset($data['fulfilment']);
        }
        if (\array_key_exists('hasUnlimitedShelfLife', $data)) {
            $object->hasUnlimitedShelfLife = $data['hasUnlimitedShelfLife'];
            unset($data['hasUnlimitedShelfLife']);
        }
        if (\array_key_exists('hsCode', $data)) {
            $object->hsCode = $data['hsCode'];
            unset($data['hsCode']);
        }
        if (\array_key_exists('name', $data)) {
            $object->name = $data['name'];
            unset($data['name']);
        }
        if (\array_key_exists('physicalProperties', $data)) {
            $object->physicalProperties = $this->denormalizer->denormalize($data['physicalProperties'], \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ProductToCreatePhysicalProperties::class, 'json', $context);
            unset($data['physicalProperties']);
        }
        if (\array_key_exists('purchasePrice', $data)) {
            $object->purchasePrice = $this->denormalizer->denormalize($data['purchasePrice'], \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\Money::class, 'json', $context);
            unset($data['purchasePrice']);
        }
        if (\array_key_exists('salesPrice', $data)) {
            $object->salesPrice = $this->denormalizer->denormalize($data['salesPrice'], \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ProductToCreateSalesPrice::class, 'json', $context);
            unset($data['salesPrice']);
        }
        if (\array_key_exists('sku', $data)) {
            $object->sku = $data['sku'];
            unset($data['sku']);
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
        if (array_key_exists('barcode', get_object_vars($data))) {
            $dataArray['barcode'] = $data->barcode;
        }
        if (array_key_exists('containsDangerousSubstances', get_object_vars($data))) {
            $dataArray['containsDangerousSubstances'] = $data->containsDangerousSubstances;
        }
        if (array_key_exists('countryCodeOfOrigin', get_object_vars($data))) {
            $dataArray['countryCodeOfOrigin'] = $data->countryCodeOfOrigin;
        }
        if (array_key_exists('descriptions', get_object_vars($data))) {
            $values = [];
            foreach ($data->descriptions as $value) {
                $normalized = $value === null ? null : $this->normalizer->normalize($value, 'json', $context);
                $values[] = \is_iterable($normalized) ? new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\JsonObject($normalized) : $normalized;
            }
            $dataArray['descriptions'] = $values;
        }
        if (array_key_exists('ean', get_object_vars($data))) {
            $dataArray['ean'] = $data->ean;
        }
        if (array_key_exists('fulfilment', get_object_vars($data))) {
            $normalized_1 = $data->fulfilment === null ? null : $this->normalizer->normalize($data->fulfilment, 'json', $context);
            $dataArray['fulfilment'] = \is_iterable($normalized_1) ? new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\JsonObject($normalized_1) : $normalized_1;
        }
        if (array_key_exists('hasUnlimitedShelfLife', get_object_vars($data))) {
            $dataArray['hasUnlimitedShelfLife'] = $data->hasUnlimitedShelfLife;
        }
        if (array_key_exists('hsCode', get_object_vars($data))) {
            $dataArray['hsCode'] = $data->hsCode;
        }
        if (null !== ($data->name ?? null)) {
            $dataArray['name'] = $data->name;
        }
        if (array_key_exists('physicalProperties', get_object_vars($data))) {
            $normalized_2 = $data->physicalProperties === null ? null : $this->normalizer->normalize($data->physicalProperties, 'json', $context);
            $dataArray['physicalProperties'] = \is_iterable($normalized_2) ? new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\JsonObject($normalized_2) : $normalized_2;
        }
        if (array_key_exists('purchasePrice', get_object_vars($data))) {
            $normalized_3 = $data->purchasePrice === null ? null : $this->normalizer->normalize($data->purchasePrice, 'json', $context);
            $dataArray['purchasePrice'] = \is_iterable($normalized_3) ? new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\JsonObject($normalized_3) : $normalized_3;
        }
        if (array_key_exists('salesPrice', get_object_vars($data))) {
            $normalized_4 = $data->salesPrice === null ? null : $this->normalizer->normalize($data->salesPrice, 'json', $context);
            $dataArray['salesPrice'] = \is_iterable($normalized_4) ? new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\JsonObject($normalized_4) : $normalized_4;
        }
        if (array_key_exists('sku', get_object_vars($data))) {
            $dataArray['sku'] = $data->sku;
        }
        foreach ($data->additionalPropertyEntries() as $key => $value_1) {
            if (preg_match('/.*/', (string) $key)) {
                $dataArray[$key] = $value_1;
            }
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Validator\ProductToCreateConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ProductToCreate::class => false];
    }
}