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
class ContactMultiEmailPrivateNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ContactMultiEmailPrivate::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ContactMultiEmailPrivate::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ContactMultiEmailPrivate();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Validator\ContactMultiEmailPrivateConstraint());
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
            $object->type = is_string($data['type']) ? \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ContactMultiEmailPrivateType::tryFrom($data['type']) ?? $data['type'] : $data['type'];
            unset($data['type']);
        }
        if (\array_key_exists('name', $data)) {
            $object->name = $data['name'];
            unset($data['name']);
        }
        if (\array_key_exists('emailAddresses', $data)) {
            $values = [];
            foreach ($data['emailAddresses'] as $value) {
                $values[] = $value;
            }
            $object->emailAddresses = $values;
            unset($data['emailAddresses']);
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
        if (array_key_exists('locale', get_object_vars($data))) {
            $dataArray['locale'] = $data->locale;
        }
        if (array_key_exists('phoneNumber', get_object_vars($data))) {
            $dataArray['phoneNumber'] = $data->phoneNumber;
        }
        if (null !== ($data->type ?? null)) {
            $dataArray['type'] = $data->type instanceof \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ContactMultiEmailPrivateType ? $data->type->value : $data->type;
        }
        if (null !== ($data->name ?? null)) {
            $dataArray['name'] = $data->name;
        }
        if (array_key_exists('emailAddresses', get_object_vars($data))) {
            $values = [];
            foreach ($data->emailAddresses as $value) {
                $values[] = $value;
            }
            $dataArray['emailAddresses'] = $values;
        }
        foreach ($data->additionalPropertyEntries() as $key => $value_1) {
            if (preg_match('/.*/', (string) $key)) {
                $dataArray[$key] = $value_1;
            }
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Validator\ContactMultiEmailPrivateConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ContactMultiEmailPrivate::class => false];
    }
}