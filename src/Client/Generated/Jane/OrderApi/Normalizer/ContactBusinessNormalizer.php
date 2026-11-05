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
class ContactBusinessNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ContactBusiness::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ContactBusiness::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ContactBusiness();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Validator\ContactBusinessConstraint());
        }
        if (\array_key_exists('emailAddress', $data)) {
            $object->emailAddress = $data['emailAddress'];
            unset($data['emailAddress']);
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
            $object->type = is_string($data['type']) ? \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ContactBusinessType::tryFrom($data['type']) ?? $data['type'] : $data['type'];
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
        foreach ($data as $key_1 => $value_1) {
            if (preg_match('/.*/', (string) $key_1)) {
                $object[$key_1] = $value_1;
            }
        }
        return $object;
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $dataArray = [];
        if (array_key_exists('emailAddress', get_object_vars($data))) {
            $dataArray['emailAddress'] = $data->emailAddress;
        }
        if (array_key_exists('locale', get_object_vars($data))) {
            $dataArray['locale'] = $data->locale;
        }
        if (array_key_exists('phoneNumber', get_object_vars($data))) {
            $dataArray['phoneNumber'] = $data->phoneNumber;
        }
        if (null !== ($data->type ?? null)) {
            $dataArray['type'] = $data->type instanceof \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ContactBusinessType ? $data->type->value : $data->type;
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
        foreach ($data->additionalPropertyEntries() as $key_1 => $value_1) {
            if (preg_match('/.*/', (string) $key_1)) {
                $dataArray[$key_1] = $value_1;
            }
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Validator\ContactBusinessConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ContactBusiness::class => false];
    }
}