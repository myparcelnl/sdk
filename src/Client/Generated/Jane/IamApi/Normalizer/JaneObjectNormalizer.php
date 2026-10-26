<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Normalizer;

use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Runtime\Normalizer\CheckArray;
use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Runtime\Normalizer\ValidatorTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
class JaneObjectNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    protected $normalizers = [
        
        \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model\PrincipalCommon::class => \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Normalizer\PrincipalCommonNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model\PrincipalShop::class => \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Normalizer\PrincipalShopNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model\PrincipalUser::class => \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Normalizer\PrincipalUserNormalizer::class,
        
        \Jane\Component\JsonSchemaRuntime\Reference::class => \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Runtime\Normalizer\ReferenceNormalizer::class,
    ], $normalizersCache = [];
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return array_key_exists($type, $this->normalizers);
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && array_key_exists(get_class($data), $this->normalizers);
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $normalizerClass = $this->normalizers[get_class($data)];
        $normalizer = $this->getNormalizer($normalizerClass);
        return $normalizer->normalize($data, $format, $context);
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $denormalizerClass = $this->normalizers[$type];
        $denormalizer = $this->getNormalizer($denormalizerClass);
        return $denormalizer->denormalize($data, $type, $format, $context);
    }
    private function getNormalizer(string $normalizerClass)
    {
        return $this->normalizersCache[$normalizerClass] ?? $this->initNormalizer($normalizerClass);
    }
    private function initNormalizer(string $normalizerClass)
    {
        $normalizer = match ($normalizerClass) {
            \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Normalizer\PrincipalCommonNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Normalizer\PrincipalCommonNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Normalizer\PrincipalShopNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Normalizer\PrincipalShopNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Normalizer\PrincipalUserNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Normalizer\PrincipalUserNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Runtime\Normalizer\ReferenceNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Runtime\Normalizer\ReferenceNormalizer(),
            default => throw new \InvalidArgumentException('Unknown normalizer class: ' . $normalizerClass),
        };
        if ($normalizer instanceof \Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface) {
            $normalizer->setNormalizer($this->normalizer);
        }
        if ($normalizer instanceof \Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface) {
            $normalizer->setDenormalizer($this->denormalizer);
        }
        $this->normalizersCache[$normalizerClass] = $normalizer;
        return $normalizer;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return array_combine(array_keys($this->normalizers), array_fill(0, count($this->normalizers), false));
    }
}