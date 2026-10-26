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
class BillingDefsPaymentDataNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\BillingDefsPaymentData::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\BillingDefsPaymentData::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\BillingDefsPaymentData();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\BillingDefsPaymentDataConstraint());
        }
        if (\array_key_exists('id', $data)) {
            $object->id = $data['id'];
        }
        if (\array_key_exists('hash', $data)) {
            $object->hash = $data['hash'];
        }
        if (\array_key_exists('type', $data)) {
            $object->type = $data['type'];
        }
        if (\array_key_exists('notification_hash', $data)) {
            $object->notificationHash = $data['notification_hash'];
        }
        if (\array_key_exists('original_notification_hash', $data)) {
            $object->originalNotificationHash = $data['original_notification_hash'];
        }
        if (\array_key_exists('paid', $data)) {
            $object->paid = is_int($data['paid']) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\BillingDefsPaymentDataPaid::tryFrom($data['paid']) ?? $data['paid'] : $data['paid'];
        }
        if (\array_key_exists('price', $data)) {
            $object->price = $this->denormalizer->denormalize($data['price'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonDefsPrice::class, 'json', $context);
        }
        if (\array_key_exists('payment_url', $data) && $data['payment_url'] !== null) {
            $value = $data['payment_url'];
            if (is_string($data['payment_url'])) {
                $value = $data['payment_url'];
            } elseif (is_null($data['payment_url'])) {
                $value = $data['payment_url'];
            }
            $object->paymentUrl = $value;
        }
        elseif (\array_key_exists('payment_url', $data)) {
            $object->paymentUrl = null;
        }
        if (\array_key_exists('invoices', $data)) {
            $object->invoices = $this->denormalizer->denormalize($data['invoices'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\BillingDefsPaymentDataInvoices::class, 'json', $context);
        }
        return $object;
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $dataArray = [];
        if (null !== ($data->id ?? null)) {
            $dataArray['id'] = $data->id;
        }
        if (null !== ($data->hash ?? null)) {
            $dataArray['hash'] = $data->hash;
        }
        if (null !== ($data->type ?? null)) {
            $dataArray['type'] = $data->type;
        }
        if (null !== ($data->notificationHash ?? null)) {
            $dataArray['notification_hash'] = $data->notificationHash;
        }
        if (null !== ($data->originalNotificationHash ?? null)) {
            $dataArray['original_notification_hash'] = $data->originalNotificationHash;
        }
        if (null !== ($data->paid ?? null)) {
            $dataArray['paid'] = $data->paid instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\BillingDefsPaymentDataPaid ? $data->paid->value : $data->paid;
        }
        if (null !== ($data->price ?? null)) {
            $normalized = $this->normalizer->normalize($data->price, 'json', $context);
            $dataArray['price'] = \is_iterable($normalized) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized) : $normalized;
        }
        if (null !== ($data->paymentUrl ?? null)) {
            $value = $data->paymentUrl;
            if (is_string($data->paymentUrl)) {
                $value = $data->paymentUrl;
            } elseif (is_null($data->paymentUrl)) {
                $value = $data->paymentUrl;
            }
            $dataArray['payment_url'] = $value;
        }
        if (null !== ($data->invoices ?? null)) {
            $normalized_1 = $this->normalizer->normalize($data->invoices, 'json', $context);
            $dataArray['invoices'] = \is_iterable($normalized_1) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_1) : $normalized_1;
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\BillingDefsPaymentDataConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\BillingDefsPaymentData::class => false];
    }
}