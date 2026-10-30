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
class RefRatesResponseRateV2Normalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefRatesResponseRateV2::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefRatesResponseRateV2::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefRatesResponseRateV2();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\RefRatesResponseRateV2Constraint());
        }
        if (\array_key_exists('carrier', $data)) {
            $object->carrier = is_string($data['carrier']) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedCarrierV2::tryFrom($data['carrier']) ?? $data['carrier'] : $data['carrier'];
        }
        if (\array_key_exists('contract', $data)) {
            $object->contract = $this->denormalizer->denormalize($data['contract'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefRatesResponseContractV2::class, 'json', $context);
        }
        if (\array_key_exists('packageType', $data)) {
            $object->packageType = is_string($data['packageType']) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentPackageTypeV2::tryFrom($data['packageType']) ?? $data['packageType'] : $data['packageType'];
        }
        if (\array_key_exists('deliveryType', $data)) {
            $object->deliveryType = is_string($data['deliveryType']) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesDeliveryTypeV2::tryFrom($data['deliveryType']) ?? $data['deliveryType'] : $data['deliveryType'];
        }
        if (\array_key_exists('shipmentType', $data)) {
            $object->shipmentType = is_string($data['shipmentType']) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentShipmentTypeV2::tryFrom($data['shipmentType']) ?? $data['shipmentType'] : $data['shipmentType'];
        }
        if (\array_key_exists('priceComposition', $data)) {
            $object->priceComposition = $this->denormalizer->denormalize($data['priceComposition'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefRatesResponsePriceCompositionPriceCompositionV2::class, 'json', $context);
        }
        return $object;
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $dataArray = [];
        if (null !== ($data->carrier ?? null)) {
            $dataArray['carrier'] = $data->carrier instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedCarrierV2 ? $data->carrier->value : $data->carrier;
        }
        if (array_key_exists('contract', get_object_vars($data))) {
            $normalized = $data->contract === null ? null : $this->normalizer->normalize($data->contract, 'json', $context);
            $dataArray['contract'] = \is_iterable($normalized) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized) : $normalized;
        }
        if (null !== ($data->packageType ?? null)) {
            $dataArray['packageType'] = $data->packageType instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentPackageTypeV2 ? $data->packageType->value : $data->packageType;
        }
        if (array_key_exists('deliveryType', get_object_vars($data))) {
            $dataArray['deliveryType'] = $data->deliveryType instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesDeliveryTypeV2 ? $data->deliveryType->value : $data->deliveryType;
        }
        if (null !== ($data->shipmentType ?? null)) {
            $dataArray['shipmentType'] = $data->shipmentType instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentShipmentTypeV2 ? $data->shipmentType->value : $data->shipmentType;
        }
        if (null !== ($data->priceComposition ?? null)) {
            $normalized_1 = $this->normalizer->normalize($data->priceComposition, 'json', $context);
            $dataArray['priceComposition'] = \is_iterable($normalized_1) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_1) : $normalized_1;
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\RefRatesResponseRateV2Constraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefRatesResponseRateV2::class => false];
    }
}