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
class RefShippingRulesShippingRuleNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefShippingRulesShippingRule::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefShippingRulesShippingRule::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefShippingRulesShippingRule();
        if (null === $data || false === \is_array($data)) {
            return $object;
        }
        if (isset($data['$ref']) && !isset($data['type']) && !isset($data['properties']) && !isset($data['allOf'])) {
            return new Reference($data['$ref'], $context['document-origin']);
        }
        if (isset($data['$recursiveRef'])) {
            return new Reference($data['$recursiveRef'], $context['document-origin']);
        }
        if (\array_key_exists('active', $data) && \is_int($data['active'])) {
            $data['active'] = (float) $data['active'];
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Validator\RefShippingRulesShippingRuleConstraint());
        }
        if (\array_key_exists('id', $data) && $data['id'] !== null) {
            $value = $data['id'];
            if (is_null($data['id'])) {
                $value = $data['id'];
            } elseif (is_int($data['id'])) {
                $value = $data['id'];
            }
            $object->id = $value;
        }
        elseif (\array_key_exists('id', $data)) {
            $object->id = null;
        }
        if (\array_key_exists('account_id', $data)) {
            $object->accountId = $data['account_id'];
        }
        if (\array_key_exists('shop_id', $data)) {
            $object->shopId = $data['shop_id'];
        }
        if (\array_key_exists('active', $data)) {
            $object->active = $data['active'];
        }
        if (\array_key_exists('criteria', $data)) {
            $object->criteria = $this->denormalizer->denormalize($data['criteria'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefShippingRulesCriteria::class, 'json', $context);
        }
        if (\array_key_exists('implications', $data)) {
            $object->implications = $this->denormalizer->denormalize($data['implications'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefShippingRulesImplications::class, 'json', $context);
        }
        if (\array_key_exists('created', $data) && $data['created'] !== null) {
            $value_1 = $data['created'];
            if (is_null($data['created'])) {
                $value_1 = $data['created'];
            } elseif (is_string($data['created'])) {
                $value_1 = $data['created'];
            }
            $object->created = $value_1;
        }
        elseif (\array_key_exists('created', $data)) {
            $object->created = null;
        }
        return $object;
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $dataArray = [];
        if (null !== ($data->id ?? null)) {
            $value = $data->id;
            if (is_null($data->id)) {
                $value = $data->id;
            } elseif (is_int($data->id)) {
                $value = $data->id;
            }
            $dataArray['id'] = $value;
        }
        if (null !== ($data->accountId ?? null)) {
            $dataArray['account_id'] = $data->accountId;
        }
        if (null !== ($data->shopId ?? null)) {
            $dataArray['shop_id'] = $data->shopId;
        }
        if (null !== ($data->active ?? null)) {
            $dataArray['active'] = $data->active;
        }
        if (null !== ($data->criteria ?? null)) {
            $normalized = $this->normalizer->normalize($data->criteria, 'json', $context);
            $dataArray['criteria'] = \is_iterable($normalized) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Runtime\JsonObject($normalized) : $normalized;
        }
        if (null !== ($data->implications ?? null)) {
            $normalized_1 = $this->normalizer->normalize($data->implications, 'json', $context);
            $dataArray['implications'] = \is_iterable($normalized_1) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Runtime\JsonObject($normalized_1) : $normalized_1;
        }
        if (null !== ($data->created ?? null)) {
            $value_1 = $data->created;
            if (is_null($data->created)) {
                $value_1 = $data->created;
            } elseif (is_string($data->created)) {
                $value_1 = $data->created;
            }
            $dataArray['created'] = $value_1;
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Validator\RefShippingRulesShippingRuleConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefShippingRulesShippingRule::class => false];
    }
}