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
class ErrorNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\Error::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\Error::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\Error();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\ErrorConstraint());
        }
        if (\array_key_exists('status', $data)) {
            $value = $data['status'];
            if (is_int($data['status'])) {
                $value = $data['status'];
            } elseif (is_int($data['status'])) {
                $value = $data['status'];
            }
            $object->status = $value;
            unset($data['status']);
        }
        if (\array_key_exists('code', $data)) {
            $value_1 = $data['code'];
            if (is_int($data['code'])) {
                $value_1 = \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonErrorUserCodeAuth::tryFrom($data['code']) ?? $data['code'];
            } elseif (is_int($data['code'])) {
                $value_1 = $data['code'];
            }
            $object->code = $value_1;
            unset($data['code']);
        }
        if (\array_key_exists('title', $data)) {
            $object->title = $data['title'];
            unset($data['title']);
        }
        if (\array_key_exists('message', $data)) {
            $object->message = $data['message'];
            unset($data['message']);
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
        if (array_key_exists('status', get_object_vars($data))) {
            $value = $data->status;
            if (is_int($data->status)) {
                $value = $data->status;
            } elseif (is_int($data->status)) {
                $value = $data->status;
            }
            $dataArray['status'] = $value;
        }
        if (null !== ($data->code ?? null)) {
            $value_1 = $data->code;
            if ($data->code instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonErrorUserCodeAuth || is_int($data->code)) {
                $value_1 = $data->code instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonErrorUserCodeAuth ? $data->code->value : $data->code;
            } elseif (is_int($data->code)) {
                $value_1 = $data->code;
            }
            $dataArray['code'] = $value_1;
        }
        if (array_key_exists('title', get_object_vars($data))) {
            $dataArray['title'] = $data->title;
        }
        if (null !== ($data->message ?? null)) {
            $dataArray['message'] = $data->message;
        }
        foreach ($data->additionalPropertyEntries() as $key => $value_2) {
            if (preg_match('/.*/', (string) $key)) {
                $dataArray[$key] = $value_2;
            }
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\ErrorConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\Error::class => false];
    }
}