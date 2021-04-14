<?php

declare(strict_types=1);

namespace CQ\Helpers;

use ArrayAccess;

final class ArrHelper
{
    /**
     * Determine whether the given value is array accessible.
     */
    public static function accessible($value): bool
    {
        return is_array($value) || $value instanceof ArrayAccess;
    }

    /**
     * Determine if the given key exists in the provided array.
     */
    public static function exists(
        ArrayAccess | array $array,
        string | int $key
    ): bool {
        if ($array instanceof ArrayAccess) {
            return $array->offsetExists(offset: $key);
        }

        return array_key_exists($key, $array);
    }

    /**
     * Get an item from an array using "dot" notation.
     */
    public static function get(
        ArrayAccess | array $array,
        string | int | null $key,
        $default = null
    ): mixed {
        if (! self::accessible(value: $array)) {
            return $default;
        }

        if (is_null($key)) {
            return $array;
        }

        if (self::exists(
            array: $array,
            key: $key
        )) {
            return $array[$key];
        }

        if (strpos(haystack: $key, needle: '.') === false) {
            return $array[$key] ?? $default;
        }

        foreach (explode('.', $key) as $segment) {
            if (self::accessible(value: $array) && self::exists(array: $array, key: $segment)) {
                $array = $array[$segment];
            } else {
                return $default;
            }
        }

        return $array;
    }
}
