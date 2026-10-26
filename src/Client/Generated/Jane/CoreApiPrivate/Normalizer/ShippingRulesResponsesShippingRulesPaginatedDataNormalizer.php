<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer;

use Jane\Component\JsonSchemaRuntime\Reference;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Runtime\Normalizer\CheckArray;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Runtime\Normalizer\ValidatorTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
class ShippingRulesResponsesShippingRulesPaginatedDataNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\ShippingRulesResponsesShippingRulesPaginatedData::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\ShippingRulesResponsesShippingRulesPaginatedData::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\ShippingRulesResponsesShippingRulesPaginatedData();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Validator\ShippingRulesResponsesShippingRulesPaginatedDataConstraint());
        }
        if (\array_key_exists('shipping_rules', $data)) {
            $values = [];
            foreach ($data['shipping_rules'] as $value) {
                $values[] = $this->denormalizer->denormalize($value, \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefShippingRulesShippingRule::class, 'json', $context);
            }
            $object->shippingRules = $values;
        }
        if (\array_key_exists('results', $data)) {
            $object->results = $data['results'];
        }
        if (\array_key_exists('page', $data)) {
            $object->page = $data['page'];
        }
        if (\array_key_exists('size', $data)) {
            $object->size = $data['size'];
        }
        return $object;
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $dataArray = [];
        if (null !== ($data->shippingRules ?? null)) {
            $values = [];
            foreach ($data->shippingRules as $value) {
                $normalized = $value === null ? null : $this->normalizer->normalize($value, 'json', $context);
                $values[] = \is_iterable($normalized) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Runtime\JsonObject($normalized) : $normalized;
            }
            $dataArray['shipping_rules'] = $values;
        }
        if (null !== ($data->results ?? null)) {
            $dataArray['results'] = $data->results;
        }
        if (null !== ($data->page ?? null)) {
            $dataArray['page'] = $data->page;
        }
        if (null !== ($data->size ?? null)) {
            $dataArray['size'] = $data->size;
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Validator\ShippingRulesResponsesShippingRulesPaginatedDataConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\ShippingRulesResponsesShippingRulesPaginatedData::class => false];
    }
}