<?php

namespace Tests\Classes;

class Foo
{
    public string $value;

    public function __construct(string $value = 'foo')
    {
        $this->value = $value;
    }

    public function foo(): string
    {
        return $this->value;
    }

    public function baz(): string
    {
        return $this->value;
    }
}
