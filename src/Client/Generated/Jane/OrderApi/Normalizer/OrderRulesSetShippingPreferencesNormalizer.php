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
class OrderRulesSetShippingPreferencesNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\OrderRulesSetShippingPreferences::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\OrderRulesSetShippingPreferences::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\OrderRulesSetShippingPreferences();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Validator\OrderRulesSetShippingPreferencesConstraint());
        }
        if (\array_key_exists('path', $data)) {
            $values = [];
            foreach ($data['path'] as $value) {
                $values[] = $value;
            }
            $object->path = $values;
            unset($data['path']);
        }
        if (\array_key_exists('appliedAt', $data)) {
            $object->appliedAt = $data['appliedAt'];
            unset($data['appliedAt']);
        }
        if (\array_key_exists('result', $data)) {
            $object->result = is_string($data['result']) ? \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\OrderRulesSetShippingPreferencesResult::tryFrom($data['result']) ?? $data['result'] : $data['result'];
            unset($data['result']);
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
        if (null !== ($data->path ?? null)) {
            $values = [];
            foreach ($data->path as $value) {
                $values[] = $value;
            }
            $dataArray['path'] = $values;
        }
        if (null !== ($data->appliedAt ?? null)) {
            $dataArray['appliedAt'] = $data->appliedAt;
        }
        if (null !== ($data->result ?? null)) {
            $dataArray['result'] = $data->result instanceof \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\OrderRulesSetShippingPreferencesResult ? $data->result->value : $data->result;
        }
        foreach ($data->additionalPropertyEntries() as $key => $value_1) {
            if (preg_match('/.*/', (string) $key)) {
                $dataArray[$key] = $value_1;
            }
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Validator\OrderRulesSetShippingPreferencesConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\OrderRulesSetShippingPreferences::class => false];
    }
}