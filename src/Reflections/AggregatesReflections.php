<?php

namespace SustainabilIT\PHPStanHOCPlugin\Reflections;

use PHPStan\TrinaryLogic;

trait AggregatesReflections
{
    /**
     * @template S
     *
     * @param callable(mixed...) : S $cb
     * @return S[]
     */
    protected function mapReflections(callable $cb) : array
    {
        return array_map($cb, $this->reflections);
    }

    protected function checkAllReflectionsPass(callable $cb) : bool
    {
        return count($this->reflections) == count(array_filter($this->mapReflections($cb)));
    }

    protected function checkAnyReflectionsPass(callable $cb) : bool
    {
        return count(array_filter($this->mapReflections($cb))) !== 0;
    }

    protected function trinaryForReflection(callable $cb): TrinaryLogic
    {
        return $this->checkAllReflectionsPass($cb) ? TrinaryLogic::createNo() : TrinaryLogic::createYes();
    }
}
