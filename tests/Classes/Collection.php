<?php

namespace Tests\Classes;

use Traversable;
use IteratorAggregate;

use InvalidArgumentException;

/**
 * @template T
 *
 * @implements IteratorAggregate<int, T>
 */
class Collection implements IteratorAggregate
{
    /**
     * @var (callable(): iterable<T>) | iterable<T>
     */
    private $source;

    /**
     * @param (callable(): iterable<T>) | iterable<T> $source
     */
    public function __construct($source = [])
    {
        $this->source = $source;
    }
    
    /**
     * @template S
     *
     * @param (callable(): iterable<S>) | iterable<S> $source
     * @return Collection<S>
     */
    public static function from($source = [])
    {
        return new self($source);
    }
    
    /**
     * @return Traversable<T>
     */
    public function getIterator(): Traversable
    {
        $source = $this->source;

        yield from (is_callable($source) ? $source() : $source);
    }

    /**
     * @return T[]
     */
    public function toArray(): array
    {
        return iterator_to_array($this);
    }

    /**
     * @template S
     * @param callable(T, int): S $mapper
     * @return Collection<S>
     */
    public function map(callable $mapper): self
    {
        return new self(function () use ($mapper) {
            foreach ($this as $index => $element) {
                yield $mapper($element, $index);
            }
        });
    }

    /**
     * @param callable(T, int): bool $filter
     * @return Collection<T>
     */
    public function filter(callable $filter): self
    {
        return new self(function () use ($filter) {
            foreach ($this as $index => $element) {
                if ($filter($element, $index)) {
                    yield $element;
                }
            }
        });
    }

    /**
     * @template S
     *
     * @param callable(S, T, int | null ): S $reducer
     * @param S $initial
     * @return S
     */
    public function reduce(callable $reducer, $initial)
    {
        foreach ($this as $index => $element) {
            $initial = $reducer($initial, $element, $index);
        }

        return $initial;
    }

    /**
     * @template S
     *
     * @param class-string<S> $target
     * @return Collection<S>
     */
    public function mapInto(string $target)
    {
        return $this->map(fn ($e) => new $target($e));
    }

    /**
     * @return mixed
     */
    public function __get(string $name)
    {
        return new HigherOrderCollectionProxy($this, $name);
    }
}
