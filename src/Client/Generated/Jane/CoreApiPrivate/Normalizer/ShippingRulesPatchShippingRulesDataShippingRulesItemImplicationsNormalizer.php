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
class ShippingRulesPatchShippingRulesDataShippingRulesItemImplicationsNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\ShippingRulesPatchShippingRulesDataShippingRulesItemImplications::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\ShippingRulesPatchShippingRulesDataShippingRulesItemImplications::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\ShippingRulesPatchShippingRulesDataShippingRulesItemImplications();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Validator\ShippingRulesPatchShippingRulesDataShippingRulesItemImplicationsConstraint());
        }
        if (\array_key_exists('carrier_id', $data)) {
            $object->carrierId = is_int($data['carrier_id']) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefTypesCarrier::tryFrom($data['carrier_id']) ?? $data['carrier_id'] : $data['carrier_id'];
        }
        if (\array_key_exists('contract_id', $data) && $data['contract_id'] !== null) {
            $value = $data['contract_id'];
            if (is_int($data['contract_id'])) {
                $value = $data['contract_id'];
            } elseif (is_null($data['contract_id'])) {
                $value = $data['contract_id'];
            }
            $object->contractId = $value;
        }
        elseif (\array_key_exists('contract_id', $data)) {
            $object->contractId = null;
        }
        if (\array_key_exists('shipment_options', $data) && $data['shipment_options'] !== null) {
            $value_1 = $data['shipment_options'];
            if (is_array($data['shipment_options'])) {
                $value_1 = $this->denormalizer->denormalize($data['shipment_options'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefShipmentShipmentOptions::class, 'json', $context);
            } elseif (is_null($data['shipment_options'])) {
                $value_1 = $data['shipment_options'];
            }
            $object->shipmentOptions = $value_1;
        }
        elseif (\array_key_exists('shipment_options', $data)) {
            $object->shipmentOptions = null;
        }
        if (\array_key_exists('physical_properties', $data)) {
            $object->physicalProperties = $this->denormalizer->denormalize($data['physical_properties'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\ShippingRulesPatchShippingRulesDataShippingRulesItemImplicationsPhysicalProperties::class, 'json', $context);
        }
        return $object;
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $dataArray = [];
        if (array_key_exists('carrierId', get_object_vars($data))) {
            $dataArray['carrier_id'] = $data->carrierId instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefTypesCarrier ? $data->carrierId->value : $data->carrierId;
        }
        if (array_key_exists('contractId', get_object_vars($data))) {
            $value = $data->contractId;
            if (is_int($data->contractId)) {
                $value = $data->contractId;
            } elseif (is_null($data->contractId)) {
                $value = $data->contractId;
            }
            $dataArray['contract_id'] = $value;
        }
        if (array_key_exists('shipmentOptions', get_object_vars($data))) {
            $value_1 = $data->shipmentOptions;
            if (is_object($data->shipmentOptions)) {
                $normalized = $this->normalizer->normalize($data->shipmentOptions, 'json', $context);
                $value_1 = \is_iterable($normalized) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Runtime\JsonObject($normalized) : $normalized;
            } elseif (is_null($data->shipmentOptions)) {
                $value_1 = $data->shipmentOptions;
            }
            $dataArray['shipment_options'] = $value_1;
        }
        if (array_key_exists('physicalProperties', get_object_vars($data))) {
            $normalized_1 = $data->physicalProperties === null ? null : $this->normalizer->normalize($data->physicalProperties, 'json', $context);
            $dataArray['physical_properties'] = \is_iterable($normalized_1) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Runtime\JsonObject($normalized_1) : $normalized_1;
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Validator\ShippingRulesPatchShippingRulesDataShippingRulesItemImplicationsConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\ShippingRulesPatchShippingRulesDataShippingRulesItemImplications::class => false];
    }
}