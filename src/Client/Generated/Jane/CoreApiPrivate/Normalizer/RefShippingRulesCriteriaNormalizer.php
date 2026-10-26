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
class RefShippingRulesCriteriaNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefShippingRulesCriteria::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefShippingRulesCriteria::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefShippingRulesCriteria();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Validator\RefShippingRulesCriteriaConstraint());
        }
        if (\array_key_exists('country', $data) && $data['country'] !== null) {
            $value = $data['country'];
            if (is_null($data['country'])) {
                $value = $data['country'];
            } elseif (is_string($data['country'])) {
                $value = $data['country'];
            }
            $object->country = $value;
        }
        elseif (\array_key_exists('country', $data)) {
            $object->country = null;
        }
        if (\array_key_exists('region', $data) && $data['region'] !== null) {
            $value_1 = $data['region'];
            if (is_null($data['region'])) {
                $value_1 = $data['region'];
            } elseif (is_string($data['region'])) {
                $value_1 = \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefShippingRulesRegion::tryFrom($data['region']) ?? $data['region'];
            }
            $object->region = $value_1;
        }
        elseif (\array_key_exists('region', $data)) {
            $object->region = null;
        }
        if (\array_key_exists('type', $data)) {
            $object->type = is_string($data['type']) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefShippingRulesType::tryFrom($data['type']) ?? $data['type'] : $data['type'];
        }
        return $object;
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $dataArray = [];
        if (null !== ($data->country ?? null)) {
            $value = $data->country;
            if (is_null($data->country)) {
                $value = $data->country;
            } elseif (is_string($data->country)) {
                $value = $data->country;
            }
            $dataArray['country'] = $value;
        }
        if (null !== ($data->region ?? null)) {
            $value_1 = $data->region;
            if (is_null($data->region)) {
                $value_1 = $data->region;
            } elseif ($data->region instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefShippingRulesRegion || is_string($data->region)) {
                $value_1 = $data->region instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefShippingRulesRegion ? $data->region->value : $data->region;
            }
            $dataArray['region'] = $value_1;
        }
        if (null !== ($data->type ?? null)) {
            $dataArray['type'] = $data->type instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefShippingRulesType ? $data->type->value : $data->type;
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Validator\RefShippingRulesCriteriaConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefShippingRulesCriteria::class => false];
    }
}