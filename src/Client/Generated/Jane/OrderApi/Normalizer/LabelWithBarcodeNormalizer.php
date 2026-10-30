<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer;

use Jane\Component\JsonSchemaRuntime\Reference;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\Normalizer\CheckArray;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\Normalizer\ValidatorTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
class LabelWithBarcodeNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\LabelWithBarcode::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\LabelWithBarcode::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\LabelWithBarcode();
        if (null === $data || false === \is_array($data)) {
            return $object;
        }
        if (isset($data['$ref']) && !isset($data['type']) && !isset($data['properties']) && !isset($data['allOf'])) {
            return new Reference($data['$ref'], $context['document-origin']);
        }
        if (isset($data['$recursiveRef'])) {
            return new Reference($data['$recursiveRef'], $context['document-origin']);
        }
        if (\array_key_exists('credited', $data) && \is_int($data['credited'])) {
            $data['credited'] = (bool) $data['credited'];
        }
        if (\array_key_exists('delayed', $data) && \is_int($data['delayed'])) {
            $data['delayed'] = (bool) $data['delayed'];
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Validator\LabelWithBarcodeConstraint());
        }
        if (\array_key_exists('credited', $data)) {
            $object->credited = $data['credited'];
            unset($data['credited']);
        }
        if (\array_key_exists('createdBy', $data)) {
            $object->createdBy = $data['createdBy'];
            unset($data['createdBy']);
        }
        if (\array_key_exists('paymentStatus', $data)) {
            $object->paymentStatus = is_string($data['paymentStatus']) ? \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\PaymentStatus::tryFrom($data['paymentStatus']) ?? $data['paymentStatus'] : $data['paymentStatus'];
            unset($data['paymentStatus']);
        }
        if (\array_key_exists('barcode', $data)) {
            $object->barcode = $data['barcode'];
            unset($data['barcode']);
        }
        if (\array_key_exists('delayed', $data)) {
            $object->delayed = $data['delayed'];
            unset($data['delayed']);
        }
        if (\array_key_exists('additionalIdentifiers', $data)) {
            $values = [];
            foreach ($data['additionalIdentifiers'] as $value) {
                $values[] = $value;
            }
            $object->additionalIdentifiers = $values;
            unset($data['additionalIdentifiers']);
        }
        if (\array_key_exists('partnerBarcodes', $data)) {
            $values_1 = [];
            foreach ($data['partnerBarcodes'] as $value_1) {
                $values_1[] = $value_1;
            }
            $object->partnerBarcodes = $values_1;
            unset($data['partnerBarcodes']);
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
        if (null !== ($data->credited ?? null)) {
            $dataArray['credited'] = $data->credited;
        }
        if (array_key_exists('createdBy', get_object_vars($data))) {
            $dataArray['createdBy'] = $data->createdBy;
        }
        if (array_key_exists('paymentStatus', get_object_vars($data))) {
            $dataArray['paymentStatus'] = $data->paymentStatus instanceof \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\PaymentStatus ? $data->paymentStatus->value : $data->paymentStatus;
        }
        if (null !== ($data->barcode ?? null)) {
            $dataArray['barcode'] = $data->barcode;
        }
        if (null !== ($data->delayed ?? null)) {
            $dataArray['delayed'] = $data->delayed;
        }
        if (array_key_exists('additionalIdentifiers', get_object_vars($data))) {
            $values = [];
            foreach ($data->additionalIdentifiers as $value) {
                $values[] = $value;
            }
            $dataArray['additionalIdentifiers'] = $values;
        }
        if (array_key_exists('partnerBarcodes', get_object_vars($data))) {
            $values_1 = [];
            foreach ($data->partnerBarcodes as $value_1) {
                $values_1[] = $value_1;
            }
            $dataArray['partnerBarcodes'] = $values_1;
        }
        foreach ($data->additionalPropertyEntries() as $key => $value_2) {
            if (preg_match('/.*/', (string) $key)) {
                $dataArray[$key] = $value_2;
            }
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Validator\LabelWithBarcodeConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\LabelWithBarcode::class => false];
    }
}