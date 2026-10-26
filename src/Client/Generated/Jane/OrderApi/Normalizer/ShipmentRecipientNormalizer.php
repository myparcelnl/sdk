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
class ShipmentRecipientNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ShipmentRecipient::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ShipmentRecipient::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ShipmentRecipient();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Validator\ShipmentRecipientConstraint());
        }
        if (\array_key_exists('address', $data)) {
            $object->address = $this->denormalizer->denormalize($data['address'], \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\Address::class, 'json', $context);
            unset($data['address']);
        }
        if (\array_key_exists('contact', $data)) {
            $value = $data['contact'];
            if (is_array($data['contact']) and (\array_key_exists('type', $data['contact']) and $data['contact']['type'] == 'BUSINESS') and \array_key_exists('company', $data['contact']) and (isset($data['contact']['type']) and $data['contact']['type'] == 'BUSINESS')) {
                $value = $this->denormalizer->denormalize($data['contact'], \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ContactMultiEmailBusiness::class, 'json', $context);
            } elseif (is_array($data['contact']) and (\array_key_exists('type', $data['contact']) and $data['contact']['type'] == 'PRIVATE') and \array_key_exists('name', $data['contact']) and (isset($data['contact']['type']) and $data['contact']['type'] == 'PRIVATE')) {
                $value = $this->denormalizer->denormalize($data['contact'], \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ContactMultiEmailPrivate::class, 'json', $context);
            }
            $object->contact = $value;
            unset($data['contact']);
        }
        foreach ($data as $key => $value_1) {
            if (preg_match('/.*/', (string) $key)) {
                $object[$key] = $value_1;
            }
        }
        return $object;
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $dataArray = [];
        if (array_key_exists('address', get_object_vars($data))) {
            $normalized = $data->address === null ? null : $this->normalizer->normalize($data->address, 'json', $context);
            $dataArray['address'] = \is_iterable($normalized) ? new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\JsonObject($normalized) : $normalized;
        }
        if (array_key_exists('contact', get_object_vars($data))) {
            $value = $data->contact;
            if (is_object($data->contact)) {
                $normalized_1 = $this->normalizer->normalize($data->contact, 'json', $context);
                $value = \is_iterable($normalized_1) ? new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\JsonObject($normalized_1) : $normalized_1;
            } elseif (is_object($data->contact)) {
                $normalized_2 = $this->normalizer->normalize($data->contact, 'json', $context);
                $value = \is_iterable($normalized_2) ? new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\JsonObject($normalized_2) : $normalized_2;
            }
            $dataArray['contact'] = $value;
        }
        foreach ($data->additionalPropertyEntries() as $key => $value_1) {
            if (preg_match('/.*/', (string) $key)) {
                $dataArray[$key] = $value_1;
            }
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Validator\ShipmentRecipientConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ShipmentRecipient::class => false];
    }
}