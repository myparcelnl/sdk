<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer;

use Jane\Component\JsonSchemaRuntime\Reference;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Normalizer\CheckArray;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Normalizer\ValidatorTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
class RefRatesResponseRateV1Normalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefRatesResponseRateV1::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefRatesResponseRateV1::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefRatesResponseRateV1();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\RefRatesResponseRateV1Constraint());
        }
        if (\array_key_exists('carrier_id', $data)) {
            $object->carrierId = is_int($data['carrier_id']) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedCarrier::tryFrom($data['carrier_id']) ?? $data['carrier_id'] : $data['carrier_id'];
        }
        if (\array_key_exists('contract', $data)) {
            $object->contract = $this->denormalizer->denormalize($data['contract'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefRatesResponseContract::class, 'json', $context);
        }
        if (\array_key_exists('package_type', $data)) {
            $object->packageType = is_int($data['package_type']) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentPackageType::tryFrom($data['package_type']) ?? $data['package_type'] : $data['package_type'];
        }
        if (\array_key_exists('delivery_type', $data)) {
            $object->deliveryType = is_int($data['delivery_type']) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesDeliveryType::tryFrom($data['delivery_type']) ?? $data['delivery_type'] : $data['delivery_type'];
        }
        if (\array_key_exists('shipment_type', $data)) {
            $object->shipmentType = is_int($data['shipment_type']) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesShipmentType::tryFrom($data['shipment_type']) ?? $data['shipment_type'] : $data['shipment_type'];
        }
        if (\array_key_exists('price_composition', $data)) {
            $object->priceComposition = $this->denormalizer->denormalize($data['price_composition'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefRatesResponsePriceCompositionPriceCompositionV1::class, 'json', $context);
        }
        return $object;
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $dataArray = [];
        if (null !== ($data->carrierId ?? null)) {
            $dataArray['carrier_id'] = $data->carrierId instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedCarrier ? $data->carrierId->value : $data->carrierId;
        }
        if (array_key_exists('contract', get_object_vars($data))) {
            $normalized = $data->contract === null ? null : $this->normalizer->normalize($data->contract, 'json', $context);
            $dataArray['contract'] = \is_iterable($normalized) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized) : $normalized;
        }
        if (null !== ($data->packageType ?? null)) {
            $dataArray['package_type'] = $data->packageType instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentPackageType ? $data->packageType->value : $data->packageType;
        }
        if (array_key_exists('deliveryType', get_object_vars($data))) {
            $dataArray['delivery_type'] = $data->deliveryType instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesDeliveryType ? $data->deliveryType->value : $data->deliveryType;
        }
        if (null !== ($data->shipmentType ?? null)) {
            $dataArray['shipment_type'] = $data->shipmentType instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesShipmentType ? $data->shipmentType->value : $data->shipmentType;
        }
        if (null !== ($data->priceComposition ?? null)) {
            $normalized_1 = $this->normalizer->normalize($data->priceComposition, 'json', $context);
            $dataArray['price_composition'] = \is_iterable($normalized_1) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_1) : $normalized_1;
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\RefRatesResponseRateV1Constraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefRatesResponseRateV1::class => false];
    }
}