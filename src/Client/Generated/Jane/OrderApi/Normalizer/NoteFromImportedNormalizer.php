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
class NoteFromImportedNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\NoteFromImported::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\NoteFromImported::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\NoteFromImported();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Validator\NoteFromImportedConstraint());
        }
        if (\array_key_exists('type', $data)) {
            $object->type = is_string($data['type']) ? \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\NoteFromImportedType::tryFrom($data['type']) ?? $data['type'] : $data['type'];
            unset($data['type']);
        }
        if (\array_key_exists('id', $data)) {
            $object->id = $data['id'];
            unset($data['id']);
        }
        if (\array_key_exists('notedAt', $data)) {
            $object->notedAt = $data['notedAt'];
            unset($data['notedAt']);
        }
        if (\array_key_exists('origin', $data)) {
            $object->origin = is_string($data['origin']) ? \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\NoteFromImportedOrigin::tryFrom($data['origin']) ?? $data['origin'] : $data['origin'];
            unset($data['origin']);
        }
        if (\array_key_exists('text', $data)) {
            $object->text = $data['text'];
            unset($data['text']);
        }
        if (\array_key_exists('author', $data)) {
            $object->author = $data['author'];
            unset($data['author']);
        }
        if (\array_key_exists('locale', $data)) {
            $object->locale = $data['locale'];
            unset($data['locale']);
        }
        if (\array_key_exists('updatedAt', $data)) {
            $object->updatedAt = $data['updatedAt'];
            unset($data['updatedAt']);
        }
        foreach ($data as $key => $value) {
            if (preg_match('/.*/', (string) $key)) {
                $object[$key] = $value;
            }
        }
        return $object;
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $dataArray = [];
        if (null !== ($data->type ?? null)) {
            $dataArray['type'] = $data->type instanceof \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\NoteFromImportedType ? $data->type->value : $data->type;
        }
        if (null !== ($data->id ?? null)) {
            $dataArray['id'] = $data->id;
        }
        if (null !== ($data->notedAt ?? null)) {
            $dataArray['notedAt'] = $data->notedAt;
        }
        if (null !== ($data->origin ?? null)) {
            $dataArray['origin'] = $data->origin instanceof \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\NoteFromImportedOrigin ? $data->origin->value : $data->origin;
        }
        if (null !== ($data->text ?? null)) {
            $dataArray['text'] = $data->text;
        }
        if (array_key_exists('author', get_object_vars($data))) {
            $dataArray['author'] = $data->author;
        }
        if (array_key_exists('locale', get_object_vars($data))) {
            $dataArray['locale'] = $data->locale;
        }
        if (array_key_exists('updatedAt', get_object_vars($data))) {
            $dataArray['updatedAt'] = $data->updatedAt;
        }
        foreach ($data->additionalPropertyEntries() as $key => $value) {
            if (preg_match('/.*/', (string) $key)) {
                $dataArray[$key] = $value;
            }
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Validator\NoteFromImportedConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\NoteFromImported::class => false];
    }
}