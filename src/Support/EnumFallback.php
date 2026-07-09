<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Support;

/**
 * Handles enum values that the API returns but the generated enum classes do
 * not (yet) know about.
 *
 * The generated ObjectSerializer::deserialize() delegates here instead of
 * throwing, so a newly added API enum value (e.g. a new carrier) never breaks
 * response parsing for clients that have not updated the SDK yet. The raw value
 * is passed through unchanged; an optional listener can observe it so a new
 * value does not disappear silently.
 *
 * This only affects the read path. Request serialization stays strict: sending
 * an unknown enum value still throws, because that can only happen through a
 * value that was never supported.
 */
final class EnumFallback
{
    /**
     * @var callable|null Receives (string $enumClass, mixed $value).
     */
    private static $listener;

    /**
     * Register a listener notified whenever an unknown enum value is passed
     * through. Pass null to remove it.
     *
     * @param  callable|null $listener
     */
    public static function setListener(?callable $listener): void
    {
        self::$listener = $listener;
    }

    /**
     * Called by the generated ObjectSerializer when a deserialized value is not
     * part of the enum's known values. Returns the value unchanged.
     *
     * @param  string $enumClass
     * @param  mixed  $value
     * @return mixed
     */
    public static function onUnknown(string $enumClass, $value)
    {
        if (self::$listener !== null) {
            call_user_func(self::$listener, $enumClass, $value);
        }

        return $value;
    }
}
