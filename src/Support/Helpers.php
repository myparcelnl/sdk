<?php declare(strict_types=1); /** @noinspection PhpUndefinedClassInspection */

namespace MyParcelNL\Sdk\src\Support;

use Closure;
use Exception;
use ReflectionClass;

/**
 * Class Helpers
 * @example https://laravel.com/docs/5.7/helpers
 */
class Helpers {
    /**
     * Assign high numeric IDs to a config item to force appending.
     *
     * @param  array  $array
     * @return array
     */
    public function append_config(array $array)
    {
        $start = 9999;

        foreach ($array as $key => $value) {
            if (is_numeric($key)) {
                $start++;

                $array[$start] = Arr::pull($array, $key);
            }
        }

        return $array;
    }

    /**
     * Add an element to an array using "dot" notation if it doesn't exist.
     *
     * @param  array   $array
     * @param  string  $key
     * @param  mixed   $value
     * @return array
     */
    public function array_add($array, $key, $value)
    {
        return Arr::add($array, $key, $value);
    }

    /**
     * Collapse an array of arrays into a single array.
     *
     * @param  array  $array
     * @return array
     */
    public function array_collapse($array)
    {
        return Arr::collapse($array);
    }

    /**
     * Divide an array into two arrays. One with keys and the other with values.
     *
     * @param  array  $array
     * @return array
     */
    public function array_divide($array)
    {
        return Arr::divide($array);
    }

    /**
     * Flatten a multi-dimensional associative array with dots.
     *
     * @param  array   $array
     * @param  string  $prepend
     * @return array
     */
    public function array_dot($array, $prepend = '')
    {
        return Arr::dot($array, $prepend);
    }

    /**
     * Get all of the given array except for a specified array of keys.
     *
     * @param  array  $array
     * @param  array|string  $keys
     * @return array
     */
    public function array_except($array, $keys)
    {
        return Arr::except($array, $keys);
    }

    /**
     * Return the first element in an array passing a given truth test.
     *
     * @param  array  $array
     * @param  callable|null  $callback
     * @param  mixed  $default
     * @return mixed
     */
    public function array_first($array, callable $callback = null, $default = null)
    {
        return Arr::first($array, $callback, $default);
    }

    /**
     * Flatten a multi-dimensional array into a single level.
     *
     * @param  array  $array
     * @param  int  $depth
     * @return array
     */
    public function array_flatten($array, $depth = INF)
    {
        return Arr::flatten($array, $depth);
    }

    /**
     * Remove one or many array items from a given array using "dot" notation.
     *
     * @deprecated Not implemented
     * @return void
     * @throws Exception
     */
    public function array_forget()
    {
        throw new Exception('Not implemented');
    }

    /**
     * Get an item from an array using "dot" notation.
     *
     * @param  \ArrayAccess|array  $array
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    public function array_get($array, $key, $default = null)
    {
        return Arr::get($array, $key, $default);
    }

    /**
     * Check if an item or items exist in an array using "dot" notation.
     *
     * @param  \ArrayAccess|array  $array
     * @param  string|array  $keys
     * @return bool
     */
    public function array_has($array, $keys)
    {
        return Arr::has($array, $keys);
    }

    /**
     * Return the last element in an array passing a given truth test.
     *
     * @param  array  $array
     * @param  callable|null  $callback
     * @param  mixed  $default
     * @return mixed
     */
    public function array_last($array, callable $callback = null, $default = null)
    {
        return Arr::last($array, $callback, $default);
    }

    /**
     * Get a subset of the items from the given array.
     *
     * @param  array  $array
     * @param  array|string  $keys
     * @return array
     */
    public function array_only($array, $keys)
    {
        return Arr::only($array, $keys);
    }

    /**
     * Pluck an array of values from an array.
     *
     * @param  array   $array
     * @param  string|array  $value
     * @param  string|array|null  $key
     * @return array
     */
    public function array_pluck($array, $value, $key = null)
    {
        return Arr::pluck($array, $value, $key);
    }

    /**
     * Push an item onto the beginning of an array.
     *
     * @param  array  $array
     * @param  mixed  $value
     * @param  mixed  $key
     * @return array
     */
    public function array_prepend($array, $value, $key = null)
    {
        return Arr::prepend($array, $value, $key);
    }

    /**
     * Get a value from the array, and remove it.
     *
     * @param  array   $array
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    public function array_pull(&$array, $key, $default = null)
    {
        return Arr::pull($array, $key, $default);
    }

    /**
     * Get a random value from an array.
     *
     * @param  array  $array
     * @param  int|null  $num
     * @return mixed
     */
    public function array_random($array, $num = null)
    {
        return Arr::random($array, $num);
    }

    /**
     * Set an array item to a given value using "dot" notation.
     *
     * If no key is given to the method, the entire array will be replaced.
     *
     * @param  array   $array
     * @param  string  $key
     * @param  mixed   $value
     * @return array
     */
    public function array_set(&$array, $key, $value)
    {
        return Arr::set($array, $key, $value);
    }

    /**
     * Sort the array by the given callback or attribute name.
     *
     * @param  array  $array
     * @param  callable|string|null  $callback
     * @return array
     */
    public function array_sort($array, $callback = null)
    {
        return Arr::sort($array, $callback);
    }

    /**
     * Recursively sort an array by keys and values.
     *
     * @param  array  $array
     * @return array
     */
    public function array_sort_recursive($array)
    {
        return Arr::sortRecursive($array);
    }

    /**
     * Filter the array using the given callback.
     *
     * @param  array  $array
     * @param  callable  $callback
     * @return array
     */
    public function array_where($array, callable $callback)
    {
        return Arr::where($array, $callback);
    }

    /**
     * If the given value is not an array, wrap it in one.
     *
     * @param  mixed  $value
     * @return array
     */
    public function array_wrap($value)
    {
        return Arr::wrap($value);
    }

    /**
     * Determine if the given value is "blank".
     *
     * @param  mixed  $value
     * @return bool
     */
    public function blank($value)
    {
        if (is_null($value)) {
            return true;
        }

        if (is_string($value)) {
            return trim($value) === '';
        }

        if (is_numeric($value) || is_bool($value)) {
            return false;
        }

        if ($value instanceof Countable) {
            return count($value) === 0;
        }

        return empty($value);
    }

    /**
     * Convert a value to camel case.
     *
     * @param  string  $value
     * @return string
     */
    public function camel_case($value)
    {
        return Str::camel($value);
    }

    /**
     * Get the class "basename" of the given object / class.
     *
     * @param  string|object  $class
     * @return string
     */
    public function class_basename($class)
    {
        $class = is_object($class) ? get_class($class) : $class;

        return basename(str_replace('\\', '/', $class));
    }

    /**
     * Returns all traits used by a class, its parent classes and trait of their traits.
     *
     * @param  object|string  $class
     * @return array
     */
    public function class_uses_recursive($class)
    {
        if (is_object($class)) {
            $class = get_class($class);
        }

        $results = [];

        foreach (array_reverse(class_parents($class)) + [$class => $class] as $class) {
            $results += $this->trait_uses_recursive($class);
        }

        return array_unique($results);
    }

    /**
     * Create a collection from the given value.
     *
     * @param  mixed $value
     * @return CollectionProxy
     */
    public function collect($value = null)
    {
        return new CollectionProxy($value);
    }

    /**
     * Fill in data where it's missing.
     *
     * @param  mixed   $target
     * @param  string|array  $key
     * @param  mixed  $value
     * @return mixed
     */
    public function data_fill(&$target, $key, $value)
    {
        return $this->data_set($target, $key, $value, false);
    }

    /**
     * Get an item from an array or object using "dot" notation.
     *
     * @param  mixed   $target
     * @param  string|array  $key
     * @param  mixed   $default
     * @return mixed
     */
    public function data_get($target, $key, $default = null)
    {
        if (is_null($key)) {
            return $target;
        }

        $key = is_array($key) ? $key : explode('.', $key);

        while (! is_null($segment = array_shift($key))) {
            if ($segment === '*') {
                if ($target instanceof CollectionProxy) {
                    $target = $target->all();
                } elseif (! is_array($target)) {
                    return $this->value($default);
                }

                $result = Arr::pluck($target, $key);

                return in_array('*', $key) ? Arr::collapse($result) : $result;
            }

            if (Arr::accessible($target) && Arr::exists($target, $segment)) {
                $target = $target[$segment];
            } elseif (is_object($target) && isset($target->{$segment})) {
                $target = $target->{$segment};
            } else {
                return $this->value($default);
            }
        }

        return $target;
    }

    /**
     * Set an item on an array or object using dot notation.
     *
     * @param  mixed  $target
     * @param  string|array  $key
     * @param  mixed  $value
     * @param  bool  $overwrite
     * @return mixed
     */
    public function data_set(&$target, $key, $value, $overwrite = true)
    {
        $segments = is_array($key) ? $key : explode('.', $key);

        if (($segment = array_shift($segments)) === '*') {
            if (! Arr::accessible($target)) {
                $target = [];
            }

            if ($segments) {
                foreach ($target as &$inner) {
                    $this->data_set($inner, $segments, $value, $overwrite);
                }
            } elseif ($overwrite) {
                foreach ($target as &$inner) {
                    $inner = $value;
                }
            }
        } elseif (Arr::accessible($target)) {
            if ($segments) {
                if (! Arr::exists($target, $segment)) {
                    $target[$segment] = [];
                }

                $this->data_set($target[$segment], $segments, $value, $overwrite);
            } elseif ($overwrite || ! Arr::exists($target, $segment)) {
                $target[$segment] = $value;
            }
        } elseif (is_object($target)) {
            if ($segments) {
                if (! isset($target->{$segment})) {
                    $target->{$segment} = [];
                }

                $this->data_set($target->{$segment}, $segments, $value, $overwrite);
            } elseif ($overwrite || ! isset($target->{$segment})) {
                $target->{$segment} = $value;
            }
        } else {
            $target = [];

            if ($segments) {
                $this->data_set($target[$segment], $segments, $value, $overwrite);
            } elseif ($overwrite) {
                $target[$segment] = $value;
            }
        }

        return $target;
    }

    /**
     * Dump the passed variables and end the script.
     *
     * @param  mixed  $args
     * @return void
     */
    public function dd(...$args)
    {
        foreach ($args as $x) {
            var_dump($x);
        }

        die(1);
    }

    /**
     * Escape HTML special characters in a string.
     *
     * @param  string  $value
     * @param  bool  $doubleEncode
     * @return string
     */
    public function e($value, $doubleEncode = true)
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8', $doubleEncode);
    }

    /**
     * Determine if a given string ends with a given substring.
     *
     * @param  string  $haystack
     * @param  string|array  $needles
     * @return bool
     */
    public function ends_with($haystack, $needles)
    {
        return Str::endsWith($haystack, $needles);
    }

    /**
     * Gets the value of an environment variable.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    public function env($key, $default = null)
    {
        $value = getenv($key);

        if ($value === false) {
            return $this->value($default);
        }

        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return null;
        }

        if (($valueLength = strlen($value)) > 1 && $value[0] === '"' && $value[$valueLength - 1] === '"') {
            return substr($value, 1, -1);
        }

        return $value;
    }

    /**
     * Determine if a value is "filled".
     *
     * @param  mixed  $value
     * @return bool
     */
    public function filled($value)
    {
        return ! $this->blank($value);
    }

    /**
     * Get the first element of an array. Useful for method chaining.
     *
     * @param  array  $array
     * @return mixed
     */
    public function head($array)
    {
        return reset($array);
    }

    /**
     * Convert a string to kebab case.
     *
     * @param  string  $value
     * @return string
     */
    public function kebab_case($value)
    {
        return Str::kebab($value);
    }

    /**
     * Get the last element from an array.
     *
     * @param  array  $array
     * @return mixed
     */
    public function last($array)
    {
        return end($array);
    }

    /**
     * Get an item from an object using "dot" notation.
     *
     * @param  object  $object
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    public function object_get($object, $key, $default = null)
    {
        if (is_null($key) || trim($key) == '') {
            return $object;
        }

        foreach (explode('.', $key) as $segment) {
            if (! is_object($object) || ! isset($object->{$segment})) {
                return $this->value($default);
            }

            $object = $object->{$segment};
        }

        return $object;
    }

    /**
     * Provide access to optional objects.
     *
     * @param  mixed  $value
     * @param  callable|null  $callback
     * @return mixed
     */
    public function optional($value = null, callable $callback = null)
    {
        if (is_null($callback)) {
            return new Optional($value);
        } elseif (! is_null($value)) {
            return $callback($value);
        }
    }

    /**
     * Replace a given pattern with each value in the array in sequentially.
     *
     * @param  string  $pattern
     * @param  array   $replacements
     * @param  string  $subject
     * @return string
     */
    public function preg_replace_array($pattern, array $replacements, $subject)
    {
        return preg_replace_callback($pattern, function () use (&$replacements) {
            foreach ($replacements as $key => $value) {
                return array_shift($replacements);
            }
        }, $subject);
    }

    /**
     * Retry an operation a given number of times.
     *
     * @param  int  $times
     * @param  callable  $callback
     * @param  int  $sleep
     * @return mixed
     *
     * @throws Exception
     */
    public function retry($times, callable $callback, $sleep = 0)
    {
        $times--;

        beginning:
        try {
            return $callback();
        } catch (Exception $e) {
            if (! $times) {
                throw $e;
            }

            $times--;

            if ($sleep) {
                usleep($sleep * 1000);
            }

            goto beginning;
        }
    }

    /**
     * Convert a string to snake case.
     *
     * @param  string  $value
     * @param  string  $delimiter
     * @return string
     */
    public function snake_case($value, $delimiter = '_')
    {
        return Str::snake($value, $delimiter);
    }

    /**
     * Determine if a given string starts with a given substring.
     *
     * @param  string  $haystack
     * @param  string|array  $needles
     * @return bool
     */
    public function starts_with($haystack, $needles)
    {
        return Str::startsWith($haystack, $needles);
    }

    /**
     * Return the remainder of a string after a given value.
     *
     * @param  string  $subject
     * @param  string  $search
     * @return string
     */
    public function str_after($subject, $search)
    {
        return Str::after($subject, $search);
    }

    /**
     * Get the portion of a string before a given value.
     *
     * @param  string  $subject
     * @param  string  $search
     * @return string
     */
    public function str_before($subject, $search)
    {
        return Str::before($subject, $search);
    }

    /**
     * Determine if a given string contains a given substring.
     *
     * @param  string  $haystack
     * @param  string|array  $needles
     * @return bool
     */
    public function str_contains($haystack, $needles)
    {
        return Str::contains($haystack, $needles);
    }

    /**
     * Cap a string with a single instance of a given value.
     *
     * @param  string  $value
     * @param  string  $cap
     * @return string
     */
    public function str_finish($value, $cap)
    {
        return Str::finish($value, $cap);
    }

    /**
     * Determine if a given string matches a given pattern.
     *
     * @param  string|array  $pattern
     * @param  string  $value
     * @return bool
     */
    public function str_is($pattern, $value)
    {
        return Str::is($pattern, $value);
    }

    /**
     * Limit the number of characters in a string.
     *
     * @param  string  $value
     * @param  int     $limit
     * @param  string  $end
     * @return string
     */
    public function str_limit($value, $limit = 100, $end = '...')
    {
        return Str::limit($value, $limit, $end);
    }

    /**
     * Get the plural form of an English word.
     *
     * @deprecated Not implemented
     * @return string
     * @throws Exception
     */
    public function str_plural()
    {
        throw new Exception('Not implemented');
    }

    /**
     * Generate a more truly "random" alpha-numeric string.
     *
     * @param  int $length
     * @return string
     *
     * @throws \RuntimeException
     * @throws Exception
     */
    public function str_random($length = 16)
    {
        return Str::random($length);
    }

    /**
     * Replace a given value in the string sequentially with an array.
     *
     * @param  string  $search
     * @param  array   $replace
     * @param  string  $subject
     * @return string
     */
    public function str_replace_array($search, array $replace, $subject)
    {
        return Str::replaceArray($search, $replace, $subject);
    }

    /**
     * Replace the first occurrence of a given value in the string.
     *
     * @param  string  $search
     * @param  string  $replace
     * @param  string  $subject
     * @return string
     */
    public function str_replace_first($search, $replace, $subject)
    {
        return Str::replaceFirst($search, $replace, $subject);
    }

    /**
     * Replace the last occurrence of a given value in the string.
     *
     * @param  string  $search
     * @param  string  $replace
     * @param  string  $subject
     * @return string
     */
    public function str_replace_last($search, $replace, $subject)
    {
        return Str::replaceLast($search, $replace, $subject);
    }

    /**
     * Get the singular form of an English word.
     *
     * @deprecated Not implemented
     * @return string
     * @throws Exception
     */
    public function str_singular()
    {
        throw new Exception('Not implemented');
    }

    /**
     * Generate a URL friendly "slug" from a given string.
     *
     * @param  string  $title
     * @param  string  $separator
     * @param  string  $language
     * @return string
     */
    public function str_slug($title, $separator = '-', $language = 'en')
    {
        return Str::slug($title, $separator, $language);
    }

    /**
     * Begin a string with a single instance of a given value.
     *
     * @param  string  $value
     * @param  string  $prefix
     * @return string
     */
    public function str_start($value, $prefix)
    {
        return Str::start($value, $prefix);
    }

    /**
     * Convert a value to studly caps case.
     *
     * @param  string  $value
     * @return string
     */
    public function studly_case($value)
    {
        return Str::studly($value);
    }

    /**
     * Call the given Closure with the given value then return the value.
     *
     * @param  mixed  $value
     * @param  callable|null  $callback
     * @return mixed
     */
    public function tap($value, $callback = null)
    {
        if (is_null($callback)) {
            return new HigherOrderTapProxy($value);
        }

        $callback($value);

        return $value;
    }

    /**
     * Throw the given exception if the given condition is true.
     *
     * @param  mixed  $condition
     * @param  \Throwable|string  $exception
     * @param  array  ...$parameters
     * @return mixed
     * @throws \Throwable
     */
    public function throw_if($condition, $exception, ...$parameters)
    {
        if ($condition) {
            throw (is_string($exception) ? new $exception(...$parameters) : $exception);
        }

        return $condition;
    }

    /**
     * Throw the given exception unless the given condition is true.
     *
     * @param  mixed  $condition
     * @param  \Throwable|string  $exception
     * @param  array  ...$parameters
     * @return mixed
     * @throws \Throwable
     */
    public function throw_unless($condition, $exception, ...$parameters)
    {
        if (! $condition) {
            throw (is_string($exception) ? new $exception(...$parameters) : $exception);
        }

        return $condition;
    }

    /**
     * Convert a value to title case.
     *
     * @param  string  $value
     * @return string
     */
    public function title_case($value)
    {
        return Str::title($value);
    }

    /**
     * Returns all traits used by a trait and its traits.
     *
     * @param  string  $trait
     * @return array
     */
    public function trait_uses_recursive($trait)
    {
        $traits = class_uses($trait);

        foreach ($traits as $trait) {
            $traits += $this->trait_uses_recursive($trait);
        }

        return $traits;
    }

    /**
     * Transform the given value if it is present.
     *
     * @param  mixed  $value
     * @param  callable  $callback
     * @param  mixed  $default
     * @return mixed|null
     */
    public function transform($value, callable $callback, $default = null)
    {
        if ($this->filled($value)) {
            return $callback($value);
        }

        if (is_callable($default)) {
            return $default($value);
        }

        return $default;
    }

    /**
     * Return the default value of the given value.
     *
     * @param  mixed  $value
     * @return mixed
     */
    public function value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }

    /**
     * Determine whether the current environment is Windows based.
     *
     * @return bool
     */
    public function windows_os()
    {
        return strtolower(substr(PHP_OS, 0, 3)) === 'win';
    }

    /**
     * Return the given value, optionally passed through the given callback.
     *
     * @param  mixed  $value
     * @param  callable|null  $callback
     * @return mixed
     */
    public function with($value, callable $callback = null)
    {
        return is_null($callback) ? $value : $callback($value);
    }

    /**
     * Convert value to int if it's truthy, return null otherwise.
     *
     * @param mixed $param
     *
     * @return int|null
     */
    public static function intOrNull($param): ?int
    {
        if ($param) {
            return (int) $param;
        }

        return null;
    }

    /**
     * Filter null values from an array
     *
     * @param  array $array
     *
     * @return array
     */
    public static function toArrayWithoutNull(array $array): array
    {
        return array_filter(
            $array,
            static function ($item) {
                return null !== $item;
            }
        );
    }
}
