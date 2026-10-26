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
class RefShipmentCustomsDeclarationItemNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentCustomsDeclarationItem::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentCustomsDeclarationItem::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentCustomsDeclarationItem();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\RefShipmentCustomsDeclarationItemConstraint());
        }
        if (\array_key_exists('description', $data)) {
            $object->description = $data['description'];
        }
        if (\array_key_exists('amount', $data)) {
            $object->amount = $data['amount'];
        }
        if (\array_key_exists('weight', $data)) {
            $object->weight = $data['weight'];
        }
        if (\array_key_exists('item_value', $data)) {
            $object->itemValue = $this->denormalizer->denormalize($data['item_value'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesMoney::class, 'json', $context);
        }
        if (\array_key_exists('classification', $data)) {
            $object->classification = $data['classification'];
        }
        if (\array_key_exists('country', $data)) {
            $value = $data['country'];
            if (isset($data['country'])) {
                $value = $data['country'];
            } elseif (isset($data['country'])) {
                $value = $data['country'];
            }
            $object->country = $value;
        }
        if (\array_key_exists('external_product_reference', $data) && $data['external_product_reference'] !== null) {
            $value_1 = $data['external_product_reference'];
            if (is_string($data['external_product_reference'])) {
                $value_1 = $data['external_product_reference'];
            } elseif (is_null($data['external_product_reference'])) {
                $value_1 = $data['external_product_reference'];
            }
            $object->externalProductReference = $value_1;
        }
        elseif (\array_key_exists('external_product_reference', $data)) {
            $object->externalProductReference = null;
        }
        return $object;
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $dataArray = [];
        if (null !== ($data->description ?? null)) {
            $dataArray['description'] = $data->description;
        }
        if (null !== ($data->amount ?? null)) {
            $dataArray['amount'] = $data->amount;
        }
        if (null !== ($data->weight ?? null)) {
            $dataArray['weight'] = $data->weight;
        }
        if (null !== ($data->itemValue ?? null)) {
            $normalized = $this->normalizer->normalize($data->itemValue, 'json', $context);
            $dataArray['item_value'] = \is_iterable($normalized) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized) : $normalized;
        }
        if (null !== ($data->classification ?? null)) {
            $dataArray['classification'] = $data->classification;
        }
        if (array_key_exists('country', get_object_vars($data))) {
            $value = $data->country;
            if (!is_null($data->country)) {
                $value = $data->country;
            } elseif (!is_null($data->country)) {
                $value = $data->country;
            }
            $dataArray['country'] = $value;
        }
        if (array_key_exists('externalProductReference', get_object_vars($data))) {
            $value_1 = $data->externalProductReference;
            if (is_string($data->externalProductReference)) {
                $value_1 = $data->externalProductReference;
            } elseif (is_null($data->externalProductReference)) {
                $value_1 = $data->externalProductReference;
            }
            $dataArray['external_product_reference'] = $value_1;
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\RefShipmentCustomsDeclarationItemConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentCustomsDeclarationItem::class => false];
    }
}