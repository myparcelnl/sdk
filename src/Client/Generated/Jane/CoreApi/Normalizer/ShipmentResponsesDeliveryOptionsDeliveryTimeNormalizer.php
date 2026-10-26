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
class ShipmentResponsesDeliveryOptionsDeliveryTimeNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesDeliveryOptionsDeliveryTime::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesDeliveryOptionsDeliveryTime::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesDeliveryOptionsDeliveryTime();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\ShipmentResponsesDeliveryOptionsDeliveryTimeConstraint());
        }
        if (\array_key_exists('start', $data)) {
            $object->start = $data['start'];
        }
        if (\array_key_exists('end', $data)) {
            $object->end = $data['end'];
        }
        if (\array_key_exists('price', $data)) {
            $object->price = $this->denormalizer->denormalize($data['price'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonDefsPrice::class, 'json', $context);
        }
        if (\array_key_exists('price_comment', $data)) {
            $object->priceComment = $data['price_comment'];
        }
        if (\array_key_exists('comment', $data)) {
            $object->comment = $data['comment'];
        }
        if (\array_key_exists('type', $data)) {
            $object->type = $data['type'];
        }
        if (\array_key_exists('collect_date', $data)) {
            $object->collectDate = $this->denormalizer->denormalize($data['collect_date'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonDefsDateTime::class, 'json', $context);
        }
        if (\array_key_exists('identifier', $data)) {
            $object->identifier = $data['identifier'];
        }
        if (\array_key_exists('transporter', $data)) {
            $object->transporter = $data['transporter'];
        }
        if (\array_key_exists('label_type', $data)) {
            $object->labelType = $data['label_type'];
        }
        return $object;
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $dataArray = [];
        if (null !== ($data->start ?? null)) {
            $dataArray['start'] = $data->start;
        }
        if (null !== ($data->end ?? null)) {
            $dataArray['end'] = $data->end;
        }
        if (null !== ($data->price ?? null)) {
            $normalized = $this->normalizer->normalize($data->price, 'json', $context);
            $dataArray['price'] = \is_iterable($normalized) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized) : $normalized;
        }
        if (null !== ($data->priceComment ?? null)) {
            $dataArray['price_comment'] = $data->priceComment;
        }
        if (null !== ($data->comment ?? null)) {
            $dataArray['comment'] = $data->comment;
        }
        if (null !== ($data->type ?? null)) {
            $dataArray['type'] = $data->type;
        }
        if (array_key_exists('collectDate', get_object_vars($data))) {
            $normalized_1 = $data->collectDate === null ? null : $this->normalizer->normalize($data->collectDate, 'json', $context);
            $dataArray['collect_date'] = \is_iterable($normalized_1) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_1) : $normalized_1;
        }
        if (array_key_exists('identifier', get_object_vars($data))) {
            $dataArray['identifier'] = $data->identifier;
        }
        if (array_key_exists('transporter', get_object_vars($data))) {
            $dataArray['transporter'] = $data->transporter;
        }
        if (array_key_exists('labelType', get_object_vars($data))) {
            $dataArray['label_type'] = $data->labelType;
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\ShipmentResponsesDeliveryOptionsDeliveryTimeConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesDeliveryOptionsDeliveryTime::class => false];
    }
}