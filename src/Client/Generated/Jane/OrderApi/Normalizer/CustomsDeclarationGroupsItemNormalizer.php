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
class CustomsDeclarationGroupsItemNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\CustomsDeclarationGroupsItem::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\CustomsDeclarationGroupsItem::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\CustomsDeclarationGroupsItem();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Validator\CustomsDeclarationGroupsItemConstraint());
        }
        if (\array_key_exists('description', $data)) {
            $object->description = $data['description'];
            unset($data['description']);
        }
        if (\array_key_exists('hsCode', $data)) {
            $object->hsCode = $data['hsCode'];
            unset($data['hsCode']);
        }
        if (\array_key_exists('numberOfItems', $data)) {
            $object->numberOfItems = $data['numberOfItems'];
            unset($data['numberOfItems']);
        }
        if (\array_key_exists('totalValue', $data)) {
            $object->totalValue = $this->denormalizer->denormalize($data['totalValue'], \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\CustomsDeclarationGroupsItemTotalValue::class, 'json', $context);
            unset($data['totalValue']);
        }
        if (\array_key_exists('totalWeight', $data)) {
            $object->totalWeight = $this->denormalizer->denormalize($data['totalWeight'], \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\CustomsDeclarationGroupsItemTotalWeight::class, 'json', $context);
            unset($data['totalWeight']);
        }
        if (\array_key_exists('countryCodeOfOrigin', $data)) {
            $object->countryCodeOfOrigin = $data['countryCodeOfOrigin'];
            unset($data['countryCodeOfOrigin']);
        }
        if (\array_key_exists('externalProductReference', $data)) {
            $object->externalProductReference = $data['externalProductReference'];
            unset($data['externalProductReference']);
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
        if (null !== ($data->description ?? null)) {
            $dataArray['description'] = $data->description;
        }
        if (null !== ($data->hsCode ?? null)) {
            $dataArray['hsCode'] = $data->hsCode;
        }
        if (null !== ($data->numberOfItems ?? null)) {
            $dataArray['numberOfItems'] = $data->numberOfItems;
        }
        if (null !== ($data->totalValue ?? null)) {
            $normalized = $this->normalizer->normalize($data->totalValue, 'json', $context);
            $dataArray['totalValue'] = \is_iterable($normalized) ? new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\JsonObject($normalized) : $normalized;
        }
        if (null !== ($data->totalWeight ?? null)) {
            $normalized_1 = $this->normalizer->normalize($data->totalWeight, 'json', $context);
            $dataArray['totalWeight'] = \is_iterable($normalized_1) ? new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\JsonObject($normalized_1) : $normalized_1;
        }
        if (array_key_exists('countryCodeOfOrigin', get_object_vars($data))) {
            $dataArray['countryCodeOfOrigin'] = $data->countryCodeOfOrigin;
        }
        if (array_key_exists('externalProductReference', get_object_vars($data))) {
            $dataArray['externalProductReference'] = $data->externalProductReference;
        }
        foreach ($data->additionalPropertyEntries() as $key => $value) {
            if (preg_match('/.*/', (string) $key)) {
                $dataArray[$key] = $value;
            }
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Validator\CustomsDeclarationGroupsItemConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\CustomsDeclarationGroupsItem::class => false];
    }
}