<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Client\Jane;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\JaneObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Converts a Jane Core API model to an array with the API field names, such as `postal_code`.
 *
 * The conversion skips the validation, because the model comes from the API.
 *
 * @internal
 */
final class CoreApiModels
{
    private const CONTEXT = ['skip_validation' => true];

    private static ?Serializer $serializer = null;

    /**
     * @return array<string, mixed> The fields that are set, with the API field names.
     */
    public static function toArray(object $model): array
    {
        return (array) self::serializer()->normalize($model, 'json', self::CONTEXT);
    }

    private static function serializer(): Serializer
    {
        return self::$serializer ??= new Serializer([new ArrayDenormalizer(), new JaneObjectNormalizer()]);
    }
}
