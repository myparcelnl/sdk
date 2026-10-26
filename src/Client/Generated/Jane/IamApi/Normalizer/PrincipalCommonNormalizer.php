<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Normalizer;

use Jane\Component\JsonSchemaRuntime\Reference;
use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Runtime\Normalizer\CheckArray;
use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Runtime\Normalizer\ValidatorTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
class PrincipalCommonNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model\PrincipalCommon::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model\PrincipalCommon::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model\PrincipalCommon();
        if (null === $data || false === \is_array($data)) {
            return $object;
        }
        if (isset($data['$ref']) && !isset($data['type']) && !isset($data['properties']) && !isset($data['allOf'])) {
            return new Reference($data['$ref'], $context['document-origin']);
        }
        if (isset($data['$recursiveRef'])) {
            return new Reference($data['$recursiveRef'], $context['document-origin']);
        }
        if (\array_key_exists('mfaEnabled', $data) && \is_int($data['mfaEnabled'])) {
            $data['mfaEnabled'] = (bool) $data['mfaEnabled'];
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Validator\PrincipalCommonConstraint());
        }
        if (\array_key_exists('accountId', $data)) {
            $object->accountId = $data['accountId'];
            unset($data['accountId']);
        }
        if (\array_key_exists('mfaEnabled', $data)) {
            $object->mfaEnabled = $data['mfaEnabled'];
            unset($data['mfaEnabled']);
        }
        if (\array_key_exists('platform', $data)) {
            $object->platform = is_string($data['platform']) ? \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model\Platform::tryFrom($data['platform']) ?? $data['platform'] : $data['platform'];
            unset($data['platform']);
        }
        if (\array_key_exists('features', $data)) {
            $values = [];
            foreach ($data['features'] as $value) {
                $values[] = is_string($value) ? \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model\Feature::tryFrom($value) ?? $value : $value;
            }
            $object->features = $values;
            unset($data['features']);
        }
        if (\array_key_exists('fulfilmentPlatforms', $data)) {
            $values_1 = [];
            foreach ($data['fulfilmentPlatforms'] as $value_1) {
                $values_1[] = is_string($value_1) ? \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model\FulfilmentPlatform::tryFrom($value_1) ?? $value_1 : $value_1;
            }
            $object->fulfilmentPlatforms = $values_1;
            unset($data['fulfilmentPlatforms']);
        }
        if (\array_key_exists('impersonator', $data)) {
            $value_2 = $data['impersonator'];
            if (is_string($data['impersonator'])) {
                $value_2 = $data['impersonator'];
            } elseif (is_string($data['impersonator'])) {
                $value_2 = $data['impersonator'];
            }
            $object->impersonator = $value_2;
            unset($data['impersonator']);
        }
        foreach ($data as $key => $value_3) {
            if (preg_match('/.*/', (string) $key)) {
                $object[$key] = $value_3;
            }
        }
        return $object;
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $dataArray = [];
        if (null !== ($data->accountId ?? null)) {
            $dataArray['accountId'] = $data->accountId;
        }
        if (null !== ($data->mfaEnabled ?? null)) {
            $dataArray['mfaEnabled'] = $data->mfaEnabled;
        }
        if (null !== ($data->platform ?? null)) {
            $dataArray['platform'] = $data->platform instanceof \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model\Platform ? $data->platform->value : $data->platform;
        }
        if (array_key_exists('features', get_object_vars($data))) {
            $values = [];
            foreach ($data->features as $value) {
                $values[] = $value instanceof \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model\Feature ? $value->value : $value;
            }
            $dataArray['features'] = $values;
        }
        if (array_key_exists('fulfilmentPlatforms', get_object_vars($data))) {
            $values_1 = [];
            foreach ($data->fulfilmentPlatforms as $value_1) {
                $values_1[] = $value_1 instanceof \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model\FulfilmentPlatform ? $value_1->value : $value_1;
            }
            $dataArray['fulfilmentPlatforms'] = $values_1;
        }
        if (array_key_exists('impersonator', get_object_vars($data))) {
            $value_2 = $data->impersonator;
            if (is_string($data->impersonator)) {
                $value_2 = $data->impersonator;
            } elseif (is_string($data->impersonator)) {
                $value_2 = $data->impersonator;
            }
            $dataArray['impersonator'] = $value_2;
        }
        foreach ($data->additionalPropertyEntries() as $key => $value_3) {
            if (preg_match('/.*/', (string) $key)) {
                $dataArray[$key] = $value_3;
            }
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Validator\PrincipalCommonConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model\PrincipalCommon::class => false];
    }
}