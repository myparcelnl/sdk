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
class PrincipalShopNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model\PrincipalShop::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model\PrincipalShop::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model\PrincipalShop();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Validator\PrincipalShopConstraint());
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
        if (\array_key_exists('id', $data)) {
            $object->id = $data['id'];
            unset($data['id']);
        }
        if (\array_key_exists('type', $data)) {
            $object->type = is_string($data['type']) ? \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model\PrincipalShopType::tryFrom($data['type']) ?? $data['type'] : $data['type'];
            unset($data['type']);
        }
        if (\array_key_exists('role', $data)) {
            $object->role = is_string($data['role']) ? \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model\RoleShop::tryFrom($data['role']) ?? $data['role'] : $data['role'];
            unset($data['role']);
        }
        if (\array_key_exists('shopIds', $data)) {
            $values_2 = [];
            foreach ($data['shopIds'] as $value_3) {
                $values_2[] = $value_3;
            }
            $object->shopIds = $values_2;
            unset($data['shopIds']);
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
        if (null !== ($data->id ?? null)) {
            $dataArray['id'] = $data->id;
        }
        if (null !== ($data->type ?? null)) {
            $dataArray['type'] = $data->type instanceof \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model\PrincipalShopType ? $data->type->value : $data->type;
        }
        if (null !== ($data->role ?? null)) {
            $dataArray['role'] = $data->role instanceof \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model\RoleShop ? $data->role->value : $data->role;
        }
        if (null !== ($data->shopIds ?? null)) {
            $values_2 = [];
            foreach ($data->shopIds as $value_3) {
                $values_2[] = $value_3;
            }
            $dataArray['shopIds'] = $values_2;
        }
        foreach ($data->additionalPropertyEntries() as $key => $value_4) {
            if (preg_match('/.*/', (string) $key)) {
                $dataArray[$key] = $value_4;
            }
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Validator\PrincipalShopConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model\PrincipalShop::class => false];
    }
}