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
class ContactMultiEmailBusinessNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ContactMultiEmailBusiness::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ContactMultiEmailBusiness::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ContactMultiEmailBusiness();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Validator\ContactMultiEmailBusinessConstraint());
        }
        if (\array_key_exists('locale', $data)) {
            $object->locale = $data['locale'];
            unset($data['locale']);
        }
        if (\array_key_exists('phoneNumber', $data)) {
            $object->phoneNumber = $data['phoneNumber'];
            unset($data['phoneNumber']);
        }
        if (\array_key_exists('type', $data)) {
            $object->type = is_string($data['type']) ? \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ContactMultiEmailBusinessType::tryFrom($data['type']) ?? $data['type'] : $data['type'];
            unset($data['type']);
        }
        if (\array_key_exists('company', $data)) {
            $object->company = $data['company'];
            unset($data['company']);
        }
        if (\array_key_exists('attention', $data)) {
            $object->attention = $data['attention'];
            unset($data['attention']);
        }
        if (\array_key_exists('vatNumbersByCountry', $data)) {
            $values = new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\JsonObject();
            foreach ($data['vatNumbersByCountry'] as $key => $value) {
                $values[$key] = $value;
            }
            $object->vatNumbersByCountry = $values;
            unset($data['vatNumbersByCountry']);
        }
        if (\array_key_exists('voecNumber', $data)) {
            $object->voecNumber = $data['voecNumber'];
            unset($data['voecNumber']);
        }
        if (\array_key_exists('eoriNumber', $data)) {
            $object->eoriNumber = $data['eoriNumber'];
            unset($data['eoriNumber']);
        }
        if (\array_key_exists('emailAddresses', $data)) {
            $values_1 = [];
            foreach ($data['emailAddresses'] as $value_1) {
                $values_1[] = $value_1;
            }
            $object->emailAddresses = $values_1;
            unset($data['emailAddresses']);
        }
        foreach ($data as $key_1 => $value_2) {
            if (preg_match('/.*/', (string) $key_1)) {
                $object[$key_1] = $value_2;
            }
        }
        return $object;
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $dataArray = [];
        if (array_key_exists('locale', get_object_vars($data))) {
            $dataArray['locale'] = $data->locale;
        }
        if (array_key_exists('phoneNumber', get_object_vars($data))) {
            $dataArray['phoneNumber'] = $data->phoneNumber;
        }
        if (null !== ($data->type ?? null)) {
            $dataArray['type'] = $data->type instanceof \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ContactMultiEmailBusinessType ? $data->type->value : $data->type;
        }
        if (null !== ($data->company ?? null)) {
            $dataArray['company'] = $data->company;
        }
        if (array_key_exists('attention', get_object_vars($data))) {
            $dataArray['attention'] = $data->attention;
        }
        if (array_key_exists('vatNumbersByCountry', get_object_vars($data))) {
            $values = new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\JsonObject();
            foreach ($data->vatNumbersByCountry as $key => $value) {
                $values[$key] = $value;
            }
            $dataArray['vatNumbersByCountry'] = $values;
        }
        if (array_key_exists('voecNumber', get_object_vars($data))) {
            $dataArray['voecNumber'] = $data->voecNumber;
        }
        if (array_key_exists('eoriNumber', get_object_vars($data))) {
            $dataArray['eoriNumber'] = $data->eoriNumber;
        }
        if (array_key_exists('emailAddresses', get_object_vars($data))) {
            $values_1 = [];
            foreach ($data->emailAddresses as $value_1) {
                $values_1[] = $value_1;
            }
            $dataArray['emailAddresses'] = $values_1;
        }
        foreach ($data->additionalPropertyEntries() as $key_1 => $value_2) {
            if (preg_match('/.*/', (string) $key_1)) {
                $dataArray[$key_1] = $value_2;
            }
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Validator\ContactMultiEmailBusinessConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ContactMultiEmailBusiness::class => false];
    }
}