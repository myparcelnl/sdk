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
class ExternalReferencesSalesChannelNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ExternalReferencesSalesChannel::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ExternalReferencesSalesChannel::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ExternalReferencesSalesChannel();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Validator\ExternalReferencesSalesChannelConstraint());
        }
        if (\array_key_exists('source', $data)) {
            $object->source = is_string($data['source']) ? \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ExternalReferencesSalesChannelSource::tryFrom($data['source']) ?? $data['source'] : $data['source'];
            unset($data['source']);
        }
        if (\array_key_exists('salesChannel', $data)) {
            $object->salesChannel = $this->denormalizer->denormalize($data['salesChannel'], \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ExternalReferenceSalesChannel::class, 'json', $context);
            unset($data['salesChannel']);
        }
        if (\array_key_exists('ecommercePlatform', $data)) {
            $object->ecommercePlatform = $this->denormalizer->denormalize($data['ecommercePlatform'], \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ExternalReferenceEcommercePlatform::class, 'json', $context);
            unset($data['ecommercePlatform']);
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
        if (null !== ($data->source ?? null)) {
            $dataArray['source'] = $data->source instanceof \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ExternalReferencesSalesChannelSource ? $data->source->value : $data->source;
        }
        if (null !== ($data->salesChannel ?? null)) {
            $normalized = $this->normalizer->normalize($data->salesChannel, 'json', $context);
            $dataArray['salesChannel'] = \is_iterable($normalized) ? new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\JsonObject($normalized) : $normalized;
        }
        if (null !== ($data->ecommercePlatform ?? null)) {
            $normalized_1 = $this->normalizer->normalize($data->ecommercePlatform, 'json', $context);
            $dataArray['ecommercePlatform'] = \is_iterable($normalized_1) ? new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\JsonObject($normalized_1) : $normalized_1;
        }
        foreach ($data->additionalPropertyEntries() as $key => $value) {
            if (preg_match('/.*/', (string) $key)) {
                $dataArray[$key] = $value;
            }
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Validator\ExternalReferencesSalesChannelConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ExternalReferencesSalesChannel::class => false];
    }
}