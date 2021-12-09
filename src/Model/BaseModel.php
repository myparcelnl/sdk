<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model;

use ArrayAccess;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\ForwardsCalls;
use MyParcelNL\Sdk\Helper\Utils;

class BaseModel implements Arrayable, ArrayAccess
{
    use HasAttributes;
    use HidesAttributes;
    use ForwardsCalls;

    /**
     * @var array
     */
    protected static $booted;

    /**
     * @var array
     */
    protected static $traitInitializers = [];

    /**
     * @var object[]|array[]
     */
    private $data;

    /**
     * @param  array $data
     */
    public function __construct(array $data = [])
    {
        $this->bootIfNotBooted();
        $this->initializeTraits();
        $this->fill($data + $this->attributes);
    }

    /**
     * Handle dynamic method calls into the model.
     *
     * @param  string $method
     * @param  array  $parameters
     *
     * @return mixed
     */
    public function __call(string $method, $parameters)
    {
        $trimmed = str_replace(['get', 'set', 'Attribute'], '', $method);
        $attribute = Str::camel($trimmed);

        if (Str::contains($method, 'get')) {
            return $this->attributes[$attribute];
        }

        if (Str::contains($method, 'set')) {
            $this->attributes[$attribute] = $parameters;
        }

        return $this;
    }

    /**
     * Handle dynamic static method calls into the model.
     *
     * @param  string $method
     * @param  array  $parameters
     *
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        return (new static)->$method(...$parameters);
    }

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param  string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    /**
     * Determine if an attribute or relation exists on the model.
     *
     * @param  string $key
     *
     * @return bool
     */
    public function __isset(string $key)
    {
        return $this->offsetExists($key);
    }

    /**
     * Dynamically set attributes on the model.
     *
     * @param  string $key
     * @param  mixed  $value
     *
     * @return void
     */
    public function __set(string $key, $value)
    {
        $this->setAttribute($key, $value);
    }

    /**
     * @param  array $attributes
     *
     * @return static
     */
    public function fill(array $attributes): self
    {
        foreach ($attributes as $key => $value) {
            if (is_string($value) && Str::contains($value, '\\') && class_exists($value)) {
                $value = new $value();
            }

            $this->setAttribute($key, $value);
        }

        return $this;
    }

    /**
     * Determine if the given attribute exists.
     *
     * @param  mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return null !== $this->getAttribute($offset);
    }

    /**
     * Get the value for a given offset.
     *
     * @param  mixed $offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->getAttribute($offset);
    }

    /**
     * Set the value for a given offset.
     *
     * @param  mixed $offset
     * @param  mixed $value
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->setAttribute($offset, $value);
    }

    /**
     * Unset the value for a given offset.
     *
     * @param  mixed $offset
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->attributes[$offset]);
    }

    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->attributesToArray();
    }

    /**
     * @return array
     */
    public function toArrayWithoutNull(): array
    {
        return array_filter(
            $this->toArray(),
            static function ($item) {
                return null !== $item;
            }
        );
    }

    /**
     * Check if the model needs to be booted and if so, do it.
     *
     * @return void
     */
    protected function bootIfNotBooted(): void
    {
        if (! isset(static::$booted[static::class])) {
            static::$booted[static::class] = true;
            static::bootTraits();
        }
    }

    /**
     * Boot all bootable traits on the model.
     *
     * @return void
     */
    protected static function bootTraits(): void
    {
        $class = static::class;

        static::$traitInitializers[$class] = [];

        foreach (Utils::getClassParentsRecursive($class) as $trait) {
            $classBasename = Utils::classBasename($trait);
            $method        = "initialize$classBasename";
            if (method_exists($class, $method)) {
                static::$traitInitializers[$class][] = $method;

                static::$traitInitializers[$class] = array_unique(
                    static::$traitInitializers[$class]
                );
            }
        }
    }

    /**
     * Initialize any initializable traits on the model.
     *
     * @return void
     */
    protected function initializeTraits(): void
    {
        foreach (static::$traitInitializers[static::class] as $method) {
            $this->{$method}();
        }
    }

    /**
     * @param  string $key
     * @param         $value
     *
     * @return mixed
     */
    protected function mutateAttribute(string $key, $value)
    {
        return $this->{'get' . Str::studly($key) . 'Attribute'}($value);
    }
}
