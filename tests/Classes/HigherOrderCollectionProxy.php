<?php

namespace Tests\Classes;

/**
 * @template T
 * @template S
 */
class HigherOrderCollectionProxy
{
    /**
     * @var Collection<T>
     */
    private Collection $collection;

    private string $method;

    /**
     * @param Collection<T> $collection
     */
    public function __construct(Collection $collection, string $method)
    {
        $this->collection = $collection;
        $this->method = $method;
    }

    /**
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->collection->{$this->method}(fn ($e) => $e->$name);
    }

    /**
     * @param mixed[] $arguments
     * @return mixed
     */
    public function __call(string $method, array $arguments = [])
    {
        return $this->collection->{$this->method}(fn ($item) => $item->{$method}(...$arguments));
    }
}
