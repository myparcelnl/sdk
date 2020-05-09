<?php declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Support;

/**
 * @mixin Collection
 */
class HigherOrderWhenProxy
{
    /**
     * The collection being operated on.
     *
     * @var Collection
     */
    protected $collection;

    /**
     * The condition for proxying.
     *
     * @var bool
     */
    protected $condition;

    /**
     * Create a new proxy instance.
     *
     * @param  Collection $collection
     * @param  bool  $condition
     * @return void
     */
    public function __construct(Collection $collection, $condition)
    {
        $this->condition = $condition;
        $this->collection = $collection;
    }

    /**
     * Proxy accessing an attribute onto the collection.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->condition ? $this->collection->{$key} : $this->collection;
    }

    /**
     * Proxy a method call onto the collection.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->condition ? $this->collection->{$method}(...$parameters) : $this->collection;
    }
}
