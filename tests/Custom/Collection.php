<?php

namespace Tests\Custom;

use Traversable;
use IteratorAggregate;

use InvalidArgumentException;

/**
 * @template K
 * @template T
 *
 * @implements IteratorAggregate<K, T>
 */
class Collection implements IteratorAggregate
{
    /**
     * @var (callable(): iterable<K, T>) | iterable<K, T>
     */
    private $source;

    /**
     * @param (callable(): iterable<K, T>) | iterable<K, T> $source
     */
    public function __construct($source = [])
    {
        $this->source = $source;
    }
    
    /**
     * @template SKey
     * @template S
     *
     * @param (callable(): iterable<SKey, S>) | iterable<SKey, S> $source
     * @return self<SKey, S>
     */
    public static function from($source = [])
    {
        return new self($source);
    }
    
    /**
     * @return Traversable<K, T>
     */
    public function getIterator(): Traversable
    {
        $source = $this->source;

        yield from (is_callable($source) ? $source() : $source);
    }

    /**
     * @return array<K, T>
     */
    public function toArray(): array
    {
        return iterator_to_array($this);
    }

    /**
     * @template S
     * @param callable(T, K): S $mapper
     * @return Collection<K, S>
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
     * @param callable(T, K): bool $filter
     * @return Collection<K, T>
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
     * @param callable(S, T, K): S $reducer
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
     * @return Collection<K, S>
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
