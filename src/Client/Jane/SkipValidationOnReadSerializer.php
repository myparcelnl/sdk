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
 * @todo janephp/janephp#1147: remove this class when Jane generates validation for requests only.
 *
 * @internal Built by JaneClientFactory.
 */
final class SkipValidationOnReadSerializer implements SerializerInterface, NormalizerInterface
{
    private Serializer $serializer;

    /**
     * Wrap the serializer that the generated Client::create() builds.
     */
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
     */
    public function deserialize(mixed $data, string $type, string $format, array $context = []): mixed
    {
        return $this->serializer->deserialize($data, $type, $format, ['skip_validation' => true] + $context);
    }

    /**
     * Normalize a form or multipart request body.
     */
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        // The generated BaseEndpoint::normalizeBody() needs a NormalizerInterface for such a body.
        return $this->serializer->normalize($data, $format, $context);
    }

    /**
     * Tell whether the wrapped serializer normalizes the data.
     */
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $this->serializer->supportsNormalization($data, $format, $context);
    }

    /**
     * Return the types that the wrapped serializer normalizes.
     */
    public function getSupportedTypes(?string $format): array
    {
        return $this->serializer->getSupportedTypes($format);
    }
}
