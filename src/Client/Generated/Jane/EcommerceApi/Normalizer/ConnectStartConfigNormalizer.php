<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer;

use Jane\Component\JsonSchemaRuntime\Reference;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\Normalizer\CheckArray;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\Normalizer\ValidatorTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
class ConnectStartConfigNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ConnectStartConfig::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ConnectStartConfig::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ConnectStartConfig();
        if (null === $data || false === \is_array($data)) {
            return $object;
        }
        if (isset($data['$ref']) && !isset($data['type']) && !isset($data['properties']) && !isset($data['allOf'])) {
            return new Reference($data['$ref'], $context['document-origin']);
        }
        if (isset($data['$recursiveRef'])) {
            return new Reference($data['$recursiveRef'], $context['document-origin']);
        }
        if (\array_key_exists('platform', $data)) {
            $object->platform = is_string($data['platform']) ? \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ConnectStartConfigPlatform::tryFrom($data['platform']) ?? $data['platform'] : $data['platform'];
        }
        if (\array_key_exists('shopName', $data)) {
            $object->shopName = $data['shopName'];
        }
        if (\array_key_exists('shopUrl', $data)) {
            $object->shopUrl = $data['shopUrl'];
        }
        if (\array_key_exists('version', $data)) {
            $object->version = $data['version'];
        }
        if (\array_key_exists('commandEndpoint', $data)) {
            $object->commandEndpoint = $data['commandEndpoint'];
        }
        return $object;
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $dataArray = [];
        if (array_key_exists('platform', get_object_vars($data))) {
            $dataArray['platform'] = $data->platform instanceof \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ConnectStartConfigPlatform ? $data->platform->value : $data->platform;
        }
        if (null !== ($data->shopName ?? null)) {
            $dataArray['shopName'] = $data->shopName;
        }
        if (null !== ($data->shopUrl ?? null)) {
            $dataArray['shopUrl'] = $data->shopUrl;
        }
        if (array_key_exists('version', get_object_vars($data))) {
            $dataArray['version'] = $data->version;
        }
        if (array_key_exists('commandEndpoint', get_object_vars($data))) {
            $dataArray['commandEndpoint'] = $data->commandEndpoint;
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Validator\ConnectStartConfigConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ConnectStartConfig::class => false];
    }
}