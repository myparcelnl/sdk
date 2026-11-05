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
class RefShippingRulesImplicationsNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefShippingRulesImplications::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefShippingRulesImplications::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefShippingRulesImplications();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Validator\RefShippingRulesImplicationsConstraint());
        }
        if (\array_key_exists('carrier_id', $data)) {
            $object->carrierId = is_int($data['carrier_id']) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefTypesCarrier::tryFrom($data['carrier_id']) ?? $data['carrier_id'] : $data['carrier_id'];
            unset($data['carrier_id']);
        }
        if (\array_key_exists('contract_id', $data) && $data['contract_id'] !== null) {
            $value = $data['contract_id'];
            if (is_null($data['contract_id'])) {
                $value = $data['contract_id'];
            } elseif (is_int($data['contract_id'])) {
                $value = $data['contract_id'];
            }
            $object->contractId = $value;
            unset($data['contract_id']);
        }
        elseif (\array_key_exists('contract_id', $data)) {
            $object->contractId = null;
            unset($data['contract_id']);
        }
        if (\array_key_exists('shipment_options', $data)) {
            $object->shipmentOptions = $this->denormalizer->denormalize($data['shipment_options'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefShippingRulesShipmentOptions::class, 'json', $context);
            unset($data['shipment_options']);
        }
        if (\array_key_exists('physical_properties', $data)) {
            $object->physicalProperties = $this->denormalizer->denormalize($data['physical_properties'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefShippingRulesPhysicalProperties::class, 'json', $context);
            unset($data['physical_properties']);
        }
        if (\array_key_exists('shipping_rule_id', $data) && $data['shipping_rule_id'] !== null) {
            $value_1 = $data['shipping_rule_id'];
            if (is_null($data['shipping_rule_id'])) {
                $value_1 = $data['shipping_rule_id'];
            } elseif (is_int($data['shipping_rule_id'])) {
                $value_1 = $data['shipping_rule_id'];
            }
            $object->shippingRuleId = $value_1;
            unset($data['shipping_rule_id']);
        }
        elseif (\array_key_exists('shipping_rule_id', $data)) {
            $object->shippingRuleId = null;
            unset($data['shipping_rule_id']);
        }
        foreach ($data as $key => $value_2) {
            if (preg_match('/.*/', (string) $key)) {
                $object[$key] = $value_2;
            }
        }
        return $object;
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $dataArray = [];
        if (null !== ($data->carrierId ?? null)) {
            $dataArray['carrier_id'] = $data->carrierId instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefTypesCarrier ? $data->carrierId->value : $data->carrierId;
        }
        if (null !== ($data->contractId ?? null)) {
            $value = $data->contractId;
            if (is_null($data->contractId)) {
                $value = $data->contractId;
            } elseif (is_int($data->contractId)) {
                $value = $data->contractId;
            }
            $dataArray['contract_id'] = $value;
        }
        if (null !== ($data->shipmentOptions ?? null)) {
            $normalized = $this->normalizer->normalize($data->shipmentOptions, 'json', $context);
            $dataArray['shipment_options'] = \is_iterable($normalized) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Runtime\JsonObject($normalized) : $normalized;
        }
        if (null !== ($data->physicalProperties ?? null)) {
            $normalized_1 = $this->normalizer->normalize($data->physicalProperties, 'json', $context);
            $dataArray['physical_properties'] = \is_iterable($normalized_1) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Runtime\JsonObject($normalized_1) : $normalized_1;
        }
        if (null !== ($data->shippingRuleId ?? null)) {
            $value_1 = $data->shippingRuleId;
            if (is_null($data->shippingRuleId)) {
                $value_1 = $data->shippingRuleId;
            } elseif (is_int($data->shippingRuleId)) {
                $value_1 = $data->shippingRuleId;
            }
            $dataArray['shipping_rule_id'] = $value_1;
        }
        foreach ($data->additionalPropertyEntries() as $key => $value_2) {
            if (preg_match('/.*/', (string) $key)) {
                $dataArray[$key] = $value_2;
            }
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Validator\RefShippingRulesImplicationsConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefShippingRulesImplications::class => false];
    }
}