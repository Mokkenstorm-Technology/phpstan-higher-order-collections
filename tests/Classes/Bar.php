<?php

namespace Tests\Classes;

class Bar
{
    public string $value;

    public function __construct(string $value = 'foo')
    {
        $this->value = $value;
    }

    public function bar(): string
    {
        return $this->value;
    }

    public function baz(): string
    {
        return $this->value;
    }
}
