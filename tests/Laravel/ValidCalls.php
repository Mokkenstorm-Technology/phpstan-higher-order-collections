<?php

namespace Tests\Files;

use Illuminate\Support\Collection;

use Tests\Common\Foo;
use Tests\Common\Bar;

$equal  = Collection::make([ new Foo(''), new Foo ]);
$mixed  = new Collection([ new Foo, new Bar]);

$equal->map->foo()->toArray();

$equal->map->value->toArray();

$equal->map->foo()->toArray();

$equal->map->value->toArray();

$equal->map->value->mapInto(Bar::class)->map->value->toArray();

$equal->map->value->mapInto(Bar::class)->map->bar()->toArray();

$equal->filter(fn () : bool => true)->map->foo()->toArray();

$equal->filter->foo()->map->foo()->toArray();

$mixed->map->baz()->toArray();

$mixed->map->value->toArray();

$equal->each->baz();

$equal->contains->foo();

$equal->some->foo();

$equal->every->foo();

$equal->reject->foo();

$equal->first->foo();

$equal->unique->foo();
