<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Client\Jane;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Reads API responses without validation, and validates the requests that the SDK sends.
 *
 * Jane's `validation` option checks both directions, and most Core API models do not allow unknown
 * fields. Without this class, a field that the API adds later breaks every read. The default context
 * of the Symfony Serializer does not reach the generated normalizers, so this class adds
 * `skip_validation` to each deserialize() call.
 *
 * @todo Remove this class when Jane can generate validation for requests only.
 *
 * @internal Built by JaneClientFactory.
 */
final class SkipValidationOnReadSerializer implements SerializerInterface, NormalizerInterface
{
    private Serializer $serializer;

    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * Serialize a request body with validation.
     */
    public function serialize(mixed $data, string $format, array $context = []): string
    {
        return $this->serializer->serialize($data, $format, $context);
    }

    /**
     * Deserialize a response body without validation.
     *
     * A caller that sets `skip_validation` in the context keeps its own value, so a test can still
     * read with validation.
     */
    public function deserialize(mixed $data, string $type, string $format, array $context = []): mixed
    {
        return $this->serializer->deserialize($data, $type, $format, $context + ['skip_validation' => true]);
    }

    /**
     * The generated BaseEndpoint::normalizeBody() needs a NormalizerInterface for a form or a
     * multipart body.
     */
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        return $this->serializer->normalize($data, $format, $context);
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $this->serializer->supportsNormalization($data, $format, $context);
    }

    public function getSupportedTypes(?string $format): array
    {
        return $this->serializer->getSupportedTypes($format);
    }
}
