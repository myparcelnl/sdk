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
class RefShipmentCustomsDeclarationNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentCustomsDeclaration::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentCustomsDeclaration::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentCustomsDeclaration();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\RefShipmentCustomsDeclarationConstraint());
        }
        if (\array_key_exists('contents', $data)) {
            $object->contents = is_int($data['contents']) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsCustomsShipmentType::tryFrom($data['contents']) ?? $data['contents'] : $data['contents'];
        }
        if (\array_key_exists('invoice', $data) && $data['invoice'] !== null) {
            $value = $data['invoice'];
            if (is_string($data['invoice'])) {
                $value = $data['invoice'];
            } elseif (is_null($data['invoice'])) {
                $value = $data['invoice'];
            }
            $object->invoice = $value;
        }
        elseif (\array_key_exists('invoice', $data)) {
            $object->invoice = null;
        }
        if (\array_key_exists('eori_number', $data) && $data['eori_number'] !== null) {
            $value_1 = $data['eori_number'];
            if (is_string($data['eori_number'])) {
                $value_1 = $data['eori_number'];
            } elseif (is_null($data['eori_number'])) {
                $value_1 = $data['eori_number'];
            }
            $object->eoriNumber = $value_1;
        }
        elseif (\array_key_exists('eori_number', $data)) {
            $object->eoriNumber = null;
        }
        if (\array_key_exists('vat_number', $data) && $data['vat_number'] !== null) {
            $value_2 = $data['vat_number'];
            if (is_string($data['vat_number'])) {
                $value_2 = $data['vat_number'];
            } elseif (is_null($data['vat_number'])) {
                $value_2 = $data['vat_number'];
            }
            $object->vatNumber = $value_2;
        }
        elseif (\array_key_exists('vat_number', $data)) {
            $object->vatNumber = null;
        }
        if (\array_key_exists('vat_number_uk', $data) && $data['vat_number_uk'] !== null) {
            $value_3 = $data['vat_number_uk'];
            if (is_string($data['vat_number_uk'])) {
                $value_3 = $data['vat_number_uk'];
            } elseif (is_null($data['vat_number_uk'])) {
                $value_3 = $data['vat_number_uk'];
            }
            $object->vatNumberUk = $value_3;
        }
        elseif (\array_key_exists('vat_number_uk', $data)) {
            $object->vatNumberUk = null;
        }
        if (\array_key_exists('voec_number', $data) && $data['voec_number'] !== null) {
            $value_4 = $data['voec_number'];
            if (is_string($data['voec_number'])) {
                $value_4 = $data['voec_number'];
            } elseif (is_null($data['voec_number'])) {
                $value_4 = $data['voec_number'];
            }
            $object->voecNumber = $value_4;
        }
        elseif (\array_key_exists('voec_number', $data)) {
            $object->voecNumber = null;
        }
        if (\array_key_exists('weight', $data)) {
            $object->weight = $data['weight'];
        }
        if (\array_key_exists('items', $data)) {
            $values = [];
            foreach ($data['items'] as $value_5) {
                $values[] = $this->denormalizer->denormalize($value_5, \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentCustomsDeclarationItem::class, 'json', $context);
            }
            $object->items = $values;
        }
        return $object;
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $dataArray = [];
        if (null !== ($data->contents ?? null)) {
            $dataArray['contents'] = $data->contents instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsCustomsShipmentType ? $data->contents->value : $data->contents;
        }
        if (array_key_exists('invoice', get_object_vars($data))) {
            $value = $data->invoice;
            if (is_string($data->invoice)) {
                $value = $data->invoice;
            } elseif (is_null($data->invoice)) {
                $value = $data->invoice;
            }
            $dataArray['invoice'] = $value;
        }
        if (array_key_exists('eoriNumber', get_object_vars($data))) {
            $value_1 = $data->eoriNumber;
            if (is_string($data->eoriNumber)) {
                $value_1 = $data->eoriNumber;
            } elseif (is_null($data->eoriNumber)) {
                $value_1 = $data->eoriNumber;
            }
            $dataArray['eori_number'] = $value_1;
        }
        if (array_key_exists('vatNumber', get_object_vars($data))) {
            $value_2 = $data->vatNumber;
            if (is_string($data->vatNumber)) {
                $value_2 = $data->vatNumber;
            } elseif (is_null($data->vatNumber)) {
                $value_2 = $data->vatNumber;
            }
            $dataArray['vat_number'] = $value_2;
        }
        if (array_key_exists('vatNumberUk', get_object_vars($data))) {
            $value_3 = $data->vatNumberUk;
            if (is_string($data->vatNumberUk)) {
                $value_3 = $data->vatNumberUk;
            } elseif (is_null($data->vatNumberUk)) {
                $value_3 = $data->vatNumberUk;
            }
            $dataArray['vat_number_uk'] = $value_3;
        }
        if (array_key_exists('voecNumber', get_object_vars($data))) {
            $value_4 = $data->voecNumber;
            if (is_string($data->voecNumber)) {
                $value_4 = $data->voecNumber;
            } elseif (is_null($data->voecNumber)) {
                $value_4 = $data->voecNumber;
            }
            $dataArray['voec_number'] = $value_4;
        }
        if (null !== ($data->weight ?? null)) {
            $dataArray['weight'] = $data->weight;
        }
        if (null !== ($data->items ?? null)) {
            $values = [];
            foreach ($data->items as $value_5) {
                $normalized = $value_5 === null ? null : $this->normalizer->normalize($value_5, 'json', $context);
                $values[] = \is_iterable($normalized) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized) : $normalized;
            }
            $dataArray['items'] = $values;
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\RefShipmentCustomsDeclarationConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentCustomsDeclaration::class => false];
    }
}