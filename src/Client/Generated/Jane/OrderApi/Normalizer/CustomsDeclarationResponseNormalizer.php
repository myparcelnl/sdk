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
class CustomsDeclarationResponseNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\CustomsDeclarationResponse::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\CustomsDeclarationResponse::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\CustomsDeclarationResponse();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Validator\CustomsDeclarationResponseConstraint());
        }
        if (\array_key_exists('groups', $data)) {
            $values = [];
            foreach ($data['groups'] as $value) {
                $values[] = $this->denormalizer->denormalize($value, \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\CustomsDeclarationResponseGroupsItem::class, 'json', $context);
            }
            $object->groups = $values;
            unset($data['groups']);
        }
        if (\array_key_exists('purpose', $data)) {
            $object->purpose = is_string($data['purpose']) ? \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\CustomsDeclarationResponsePurpose::tryFrom($data['purpose']) ?? $data['purpose'] : $data['purpose'];
            unset($data['purpose']);
        }
        if (\array_key_exists('totalWeight', $data)) {
            $object->totalWeight = $this->denormalizer->denormalize($data['totalWeight'], \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\CustomsDeclarationResponseTotalWeight::class, 'json', $context);
            unset($data['totalWeight']);
        }
        if (\array_key_exists('invoiceNumber', $data)) {
            $object->invoiceNumber = $data['invoiceNumber'];
            unset($data['invoiceNumber']);
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
        if (null !== ($data->groups ?? null)) {
            $values = [];
            foreach ($data->groups as $value) {
                $normalized = $value === null ? null : $this->normalizer->normalize($value, 'json', $context);
                $values[] = \is_iterable($normalized) ? new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\JsonObject($normalized) : $normalized;
            }
            $dataArray['groups'] = $values;
        }
        if (null !== ($data->purpose ?? null)) {
            $dataArray['purpose'] = $data->purpose instanceof \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\CustomsDeclarationResponsePurpose ? $data->purpose->value : $data->purpose;
        }
        if (null !== ($data->totalWeight ?? null)) {
            $normalized_1 = $this->normalizer->normalize($data->totalWeight, 'json', $context);
            $dataArray['totalWeight'] = \is_iterable($normalized_1) ? new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\JsonObject($normalized_1) : $normalized_1;
        }
        if (array_key_exists('invoiceNumber', get_object_vars($data))) {
            $dataArray['invoiceNumber'] = $data->invoiceNumber;
        }
        foreach ($data->additionalPropertyEntries() as $key => $value_1) {
            if (preg_match('/.*/', (string) $key)) {
                $dataArray[$key] = $value_1;
            }
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Validator\CustomsDeclarationResponseConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\CustomsDeclarationResponse::class => false];
    }
}